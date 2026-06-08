import hashlib
import hmac
import os
import smtplib
from datetime import datetime, timezone
from email.message import EmailMessage

from fastapi import FastAPI, Form, HTTPException
from fastapi.responses import RedirectResponse
from pydantic import EmailStr
from sqlmodel import SQLModel, Field, Session, create_engine, select
from starlette.status import HTTP_303_SEE_OTHER

SES_SMTP_HOST = "email-smtp.eu-west-2.amazonaws.com"
SES_SMTP_PORT = 587
SES_SMTP_USER = os.environ["SES_SMTP_USER"]
SES_SMTP_PASS = os.environ["SES_SMTP_PASS"]
NEWSLETTER_SECRET = os.environ["NEWSLETTER_SECRET"]
FROM = "David Álvarez Rosa <david@alvarezrosa.com>"
API_BASE = "https://api.alvarezrosa.com"
SITE_BASE = "https://david.alvarezrosa.com"
SIGNATURE = """
--
David Álvarez Rosa

web    david.alvarezrosa.com
email  david@alvarezrosa.com
tel    +34 647 13 39 30
"""


class Subscription(SQLModel, table=True):
    id: int | None = Field(default=None, primary_key=True)
    email: EmailStr = Field(index=True)
    unsubscribed_at: datetime | None = Field(default=None)


engine = create_engine("sqlite:///subscriptions.db")
SQLModel.metadata.create_all(engine)
app = FastAPI()


def sign(purpose, email):
    return hmac.new(
        NEWSLETTER_SECRET.encode(),
        f"{purpose}:{email}".encode(),
        hashlib.sha256,
    ).hexdigest()


def smtp_login():
    smtp = smtplib.SMTP(SES_SMTP_HOST, SES_SMTP_PORT)
    smtp.starttls()
    smtp.login(SES_SMTP_USER, SES_SMTP_PASS)
    return smtp


@app.post("/subscribe")
def subscribe(email: EmailStr = Form()):
    email = email.strip().lower()
    link = f"{API_BASE}/confirm?email={email}&token={sign('confirm', email)}"
    msg = EmailMessage()
    msg["From"] = FROM
    msg["To"] = email
    msg["Subject"] = "Confirm your subscription to david.alvarezrosa.com"
    msg.set_content(
        "Confirm your subscription to David Álvarez Rosa's newsletter:\n\n"
        f"{link}\n\n"
        "If you didn't sign up, ignore this email.\n"
        f"{SIGNATURE}"
    )
    with smtp_login() as smtp:
        smtp.send_message(msg)
    return RedirectResponse(
        f"{SITE_BASE}/subscription-pending",
        status_code=HTTP_303_SEE_OTHER,
    )


@app.get("/confirm")
def confirm(email: str, token: str):
    email = email.strip().lower()
    if not hmac.compare_digest(sign("confirm", email), token):
        raise HTTPException(400)
    with Session(engine) as session:
        sub = session.exec(
            select(Subscription).where(Subscription.email == email)
        ).first() or Subscription(email=email)
        sub.unsubscribed_at = None
        session.add(sub)
        session.commit()
    return RedirectResponse(f"{SITE_BASE}/subscription", status_code=HTTP_303_SEE_OTHER)


@app.api_route("/unsubscribe", methods=["GET", "POST"])
def unsubscribe(email: str, token: str):
    email = email.strip().lower()
    if not hmac.compare_digest(sign("unsub", email), token):
        raise HTTPException(400)
    with Session(engine) as session:
        sub = session.exec(
            select(Subscription).where(Subscription.email == email)
        ).first()
        if sub:
            sub.unsubscribed_at = datetime.now(timezone.utc)
            session.add(sub)
            session.commit()
    return RedirectResponse(f"{SITE_BASE}/unsubscribed", status_code=HTTP_303_SEE_OTHER)
