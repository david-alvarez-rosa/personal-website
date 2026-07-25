import hmac
import logging
from datetime import datetime, timezone
from email.message import EmailMessage
from email.utils import formatdate, make_msgid

from fastapi import BackgroundTasks, FastAPI, Form, HTTPException
from fastapi.responses import RedirectResponse
from pydantic import EmailStr
from sqlmodel import Session, SQLModel, select
from starlette.status import HTTP_303_SEE_OTHER

from .core import (
    API_BASE,
    EMAIL_POLICY,
    FROM,
    LIST_ID,
    MAILER,
    SITE_BASE,
    Subscription,
    engine,
    imap_connect,
    make_token,
    read_token,
    sign,
    smtp_connect,
)
from .mail import SIGN_OFF, SIGNATURE, email_html, finalize, footer_html

SQLModel.metadata.create_all(engine)
app = FastAPI()
log = logging.getLogger(__name__)


def archive(msg):
    try:
        with imap_connect() as imap:
            imap.append("Sent", "\\Seen", None, bytes(msg))
    except Exception:
        log.exception("could not archive %s to Sent", msg["Subject"])


def verify(purpose, email, token):
    if not hmac.compare_digest(sign(purpose, email), token):
        raise HTTPException(400)


def resolve(purpose, token):
    email = read_token(purpose, token)
    if email is None:
        raise HTTPException(400)
    return email


def get_subscription(session, email):
    return session.exec(select(Subscription).where(Subscription.email == email)).first()


def deliver(background, email, subject, body, feedback_id, unsub=None):
    msg = EmailMessage(policy=EMAIL_POLICY)
    msg["From"] = FROM
    msg["To"] = email
    msg["Subject"] = subject
    msg["Date"] = formatdate(localtime=True)
    msg["Message-ID"] = make_msgid(domain="alvarezrosa.com")
    msg["List-Id"] = LIST_ID
    msg["Feedback-ID"] = feedback_id
    msg["X-Mailer"] = MAILER
    text = f"{body}\n\n{SIGN_OFF}\n\n{SIGNATURE}\n"
    if unsub:
        msg["List-Unsubscribe"] = f"<{unsub}>"
        msg["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click"
        text += f"\nUnsubscribe: {unsub}\n"
    msg.set_content(text, cte="quoted-printable")
    msg.add_alternative(
        email_html(body, footer_html(unsub) if unsub else "", title=subject),
        subtype="html",
        cte="quoted-printable",
    )
    finalize(msg)
    with smtp_connect() as smtp:
        smtp.send_message(msg)
    background.add_task(archive, msg)


def send_confirm(background, email):
    link = f"{API_BASE}/confirm/{make_token('confirm', email)}"
    subject = "Confirm your subscription to david.alvarezrosa.com"
    body = f"""Almost there! Confirm your email to subscribe:

{link}

If you didn't sign up, just ignore this email."""
    deliver(background, email, subject, body, "confirm:optin:alvarezrosa.com")


def send_already_subscribed(background, email):
    unsub = f"{API_BASE}/unsubscribe/{make_token('unsub', email)}"
    subject = "You're already subscribed to david.alvarezrosa.com"
    body = f"""Someone just signed up with this address, but it's already
subscribed, so nothing has changed.

If you'd rather not receive the newsletter, unsubscribe here:

{unsub}

If this wasn't you, just ignore this email."""
    deliver(background, email, subject, body, "already:optin:alvarezrosa.com", unsub)


@app.post("/subscribe")
def subscribe(
    background: BackgroundTasks, email: EmailStr = Form(), website: str = Form("")
):
    if not website:
        email = email.strip().lower()
        with Session(engine) as session:
            sub = get_subscription(session, email)
        if sub and sub.unsubscribed_at is None:
            send_already_subscribed(background, email)
        else:
            send_confirm(background, email)
    return RedirectResponse(
        f"{SITE_BASE}/subscription-pending",
        status_code=HTTP_303_SEE_OTHER,
    )


def do_confirm(email):
    with Session(engine) as session:
        sub = get_subscription(session, email) or Subscription(email=email)
        sub.unsubscribed_at = None
        session.add(sub)
        session.commit()
    return RedirectResponse(f"{SITE_BASE}/subscription", status_code=HTTP_303_SEE_OTHER)


def do_unsubscribe(email):
    with Session(engine) as session:
        sub = get_subscription(session, email)
        if sub:
            sub.unsubscribed_at = datetime.now(timezone.utc)
            session.add(sub)
            session.commit()
    return RedirectResponse(f"{SITE_BASE}/unsubscribed", status_code=HTTP_303_SEE_OTHER)


@app.get("/confirm/{token}")
def confirm_link(token: str):
    return do_confirm(resolve("confirm", token))


@app.api_route("/unsubscribe/{token}", methods=["GET", "POST"])
def unsubscribe_link(token: str):
    return do_unsubscribe(resolve("unsub", token))


@app.get("/confirm")
def confirm(email: str, token: str):
    email = email.strip().lower()
    verify("confirm", email, token)
    return do_confirm(email)


@app.api_route("/unsubscribe", methods=["GET", "POST"])
def unsubscribe(email: str, token: str):
    email = email.strip().lower()
    verify("unsub", email, token)
    return do_unsubscribe(email)
