import hmac
import logging
from datetime import UTC, datetime, timedelta
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
    Mailed,
    Subscription,
    engine,
    imap_connect,
    make_token,
    read_token,
    sign,
    smtp_connect,
)
from .mail import SIGN_OFF, SIGNATURE, email_html, finalize, footer_html, to_text

SQLModel.metadata.create_all(engine)
app = FastAPI()
logging.basicConfig(level=logging.INFO, format="%(levelname)s:     %(name)s: %(message)s")
log = logging.getLogger(__name__)
JS_TOKEN = "subscribe-v1"
MAIL_INTERVAL = timedelta(hours=24)


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
    text = f"{to_text(body)}\n\n{SIGN_OFF}\n\n{SIGNATURE}\n"
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
    body = f"""Almost there! Confirm your email to subscribe to the
david.alvarezrosa.com newsletter:

[Confirm my subscription]({link})

If you didn't sign up, just ignore this email."""
    deliver(background, email, subject, body, "confirm:optin:alvarezrosa.com")


def send_already_subscribed(background, email):
    unsub = f"{API_BASE}/unsubscribe/{make_token('unsub', email)}"
    subject = "You're already subscribed to david.alvarezrosa.com"
    body = """Someone just signed up with this address, but it's already
subscribed, so nothing has changed.

If this wasn't you, just ignore this email."""
    deliver(background, email, subject, body, "already:optin:alvarezrosa.com", unsub)


def mailed_recently(session, email):
    mailed = session.get(Mailed, email)
    if mailed is None:
        return False
    return datetime.now(UTC) - mailed.sent_at.replace(tzinfo=UTC) < MAIL_INTERVAL


def record_mailed(email):
    with Session(engine) as session:
        session.merge(Mailed(email=email, sent_at=datetime.now(UTC)))
        session.commit()


@app.post("/subscribe")
def subscribe(
    background: BackgroundTasks,
    email: EmailStr = Form(),
    website: str = Form(""),
    js: str = Form(""),
):
    if website or js != JS_TOKEN:
        log.info("dropped subscribe for %s: website=%r js=%r", email, website, js)
    else:
        email = email.strip().lower()
        with Session(engine) as session:
            sub = get_subscription(session, email)
            subscribed = sub is not None and sub.unsubscribed_at is None
            throttled = mailed_recently(session, email)
        if throttled:
            log.info("throttled subscribe mail to %s", email)
        elif subscribed:
            send_already_subscribed(background, email)
            record_mailed(email)
        else:
            send_confirm(background, email)
            record_mailed(email)
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
            sub.unsubscribed_at = datetime.now(UTC)
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
