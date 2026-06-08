import os
import sys
import smtplib
from email.message import EmailMessage

from sqlmodel import Session, select

from backend import engine, Subscription, sign

with open(sys.argv[1]) as f:
    subject = f.readline().strip()
    body = f.read().lstrip("\n")

with Session(engine) as session:
    emails = [
        s.email
        for s in session.exec(select(Subscription).where(Subscription.confirmed))
    ]

with smtplib.SMTP("email-smtp.eu-west-1.amazonaws.com", 587) as smtp:
    smtp.starttls()
    smtp.login(os.environ["SES_SMTP_USER"], os.environ["SES_SMTP_PASS"])
    for email in emails:
        unsub = f"https://api.alvarezrosa.com/unsubscribe?email={email}&token={sign('unsub', email)}"
        msg = EmailMessage()
        msg["From"] = "David Álvarez Rosa <david@alvarezrosa.com>"
        msg["To"] = email
        msg["Subject"] = subject
        msg["List-Unsubscribe"] = f"<{unsub}>"
        msg["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click"
        msg.set_content(f"{body}\n\n--\nUnsubscribe: {unsub}\n")
        smtp.send_message(msg)
