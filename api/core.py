import hashlib
import hmac
import imaplib
import os
import smtplib
from datetime import datetime
from email import policy
from pathlib import Path

from pydantic import EmailStr
from sqlmodel import Field, SQLModel, create_engine

SMTP_PASS = os.environ["SMTP_PASS"]
NEWSLETTER_SECRET = os.environ["NEWSLETTER_SECRET"]
FROM = "David Álvarez Rosa <david@alvarezrosa.com>"
API_BASE = os.environ.get("API_BASE", "https://api.alvarezrosa.com")
SITE_BASE = os.environ.get("SITE_BASE", "https://david.alvarezrosa.com")
EMAIL_POLICY = policy.SMTP.clone(max_line_length=998)


class Subscription(SQLModel, table=True):
    id: int | None = Field(default=None, primary_key=True)
    email: EmailStr = Field(index=True)
    unsubscribed_at: datetime | None = Field(default=None)


DB_PATH = os.environ.get(
    "DB_PATH", Path(__file__).resolve().parent.parent / "subscriptions.db"
)
engine = create_engine(f"sqlite:///{DB_PATH}")


def sign(purpose, email):
    return hmac.new(
        NEWSLETTER_SECRET.encode(),
        f"{purpose}:{email}".encode(),
        hashlib.sha256,
    ).hexdigest()


def smtp_connect():
    smtp = smtplib.SMTP_SSL("mail.alvarezrosa.com", 465)
    smtp.login("david@alvarezrosa.com", SMTP_PASS)
    return smtp


def imap_connect():
    imap = imaplib.IMAP4_SSL("mail.alvarezrosa.com", 993)
    imap.login("david@alvarezrosa.com", SMTP_PASS)
    return imap
