import hashlib
import hmac
import os
import smtplib
from email.message import EmailMessage

from fastapi import FastAPI, Form, HTTPException
from fastapi.responses import RedirectResponse
from pydantic import EmailStr
from sqlmodel import SQLModel, Field, Session, create_engine, select
from starlette.status import HTTP_303_SEE_OTHER


class Subscription(SQLModel, table=True):
    id: int | None = Field(default=None, primary_key=True)
    email: EmailStr = Field(index=True)
    confirmed: bool = Field(default=False)


engine = create_engine("sqlite:///subscriptions.db")
SQLModel.metadata.create_all(engine)
app = FastAPI()


def sign(purpose, email):
    return hmac.new(
        os.environ["NEWSLETTER_SECRET"].encode(),
        f"{purpose}:{email}".encode(),
        hashlib.sha256,
    ).hexdigest()


@app.post("/subscribe")
def subscribe(email: EmailStr = Form()):
    email = email.strip().lower()
    link = f"https://api.alvarezrosa.com/confirm?email={email}&token={sign('confirm', email)}"
    msg = EmailMessage()
    msg["From"] = "David Álvarez Rosa <david@alvarezrosa.com>"
    msg["To"] = email
    msg["Subject"] = "Confirm your subscription to david.alvarezrosa.com"
    msg.set_content(
        "Hi,\n\n"
        "You signed up for David Álvarez Rosa's newsletter at "
        "david.alvarezrosa.com. Click below to confirm your subscription:\n\n"
        f"{link}\n\n"
        "If you didn't sign up, just ignore this email — nothing will happen.\n\n"
        "— David\n"
    )
    with smtplib.SMTP("email-smtp.eu-west-1.amazonaws.com", 587) as smtp:
        smtp.starttls()
        smtp.login(os.environ["SES_SMTP_USER"], os.environ["SES_SMTP_PASS"])
        smtp.send_message(msg)
    return RedirectResponse(
        "https://david.alvarezrosa.com/subscription-pending",
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
        sub.confirmed = True
        session.add(sub)
        session.commit()
    return RedirectResponse(
        "https://david.alvarezrosa.com/subscription", status_code=HTTP_303_SEE_OTHER
    )


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
            session.delete(sub)
            session.commit()
    return RedirectResponse(
        "https://david.alvarezrosa.com/unsubscribed", status_code=HTTP_303_SEE_OTHER
    )
