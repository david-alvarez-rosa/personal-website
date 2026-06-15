import hmac
from datetime import datetime, timezone
from email.message import EmailMessage

from fastapi import FastAPI, Form, HTTPException
from fastapi.responses import RedirectResponse
from pydantic import EmailStr
from sqlmodel import Session, SQLModel, select
from starlette.status import HTTP_303_SEE_OTHER

from .core import (
    API_BASE,
    EMAIL_POLICY,
    FROM,
    SITE_BASE,
    Subscription,
    engine,
    sign,
    smtp_login,
)
from .mail import SIGNATURE, email_html

SQLModel.metadata.create_all(engine)
app = FastAPI()


def verify(purpose, email, token):
    if not hmac.compare_digest(sign(purpose, email), token):
        raise HTTPException(400)


def get_subscription(session, email):
    return session.exec(select(Subscription).where(Subscription.email == email)).first()


@app.post("/subscribe")
def subscribe(email: EmailStr = Form()):
    email = email.strip().lower()
    link = f"{API_BASE}/confirm?email={email}&token={sign('confirm', email)}"
    msg = EmailMessage(policy=EMAIL_POLICY)
    msg["From"] = FROM
    msg["To"] = email
    msg["Subject"] = "Confirm your subscription to david.alvarezrosa.com"
    intro = "Almost there! Confirm your email to subscribe:"
    outro = "If you didn't sign up, just ignore this email."
    text_body = f"""{intro}

{link}

{outro}"""
    html_body = f"""{intro}

[Confirm subscription]({link})

{outro}"""
    msg.set_content(f"{text_body}\n{SIGNATURE}")
    msg.add_alternative(email_html(html_body), subtype="html")
    with smtp_login() as smtp:
        smtp.send_message(msg)
    return RedirectResponse(
        f"{SITE_BASE}/subscription-pending",
        status_code=HTTP_303_SEE_OTHER,
    )


@app.get("/confirm")
def confirm(email: str, token: str):
    email = email.strip().lower()
    verify("confirm", email, token)
    with Session(engine) as session:
        sub = get_subscription(session, email) or Subscription(email=email)
        sub.unsubscribed_at = None
        session.add(sub)
        session.commit()
    return RedirectResponse(f"{SITE_BASE}/subscription", status_code=HTTP_303_SEE_OTHER)


@app.api_route("/unsubscribe", methods=["GET", "POST"])
def unsubscribe(email: str, token: str):
    email = email.strip().lower()
    verify("unsub", email, token)
    with Session(engine) as session:
        sub = get_subscription(session, email)
        if sub:
            sub.unsubscribed_at = datetime.now(timezone.utc)
            session.add(sub)
            session.commit()
    return RedirectResponse(f"{SITE_BASE}/unsubscribed", status_code=HTTP_303_SEE_OTHER)
