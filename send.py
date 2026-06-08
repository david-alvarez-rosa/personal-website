import sys
from email.message import EmailMessage

from sqlmodel import Session, col, select

from backend import (
    API_BASE,
    FROM,
    SIGNATURE,
    Subscription,
    engine,
    sign,
    smtp_login,
)

with open(sys.argv[1]) as f:
    subject = f.readline().strip()
    body = f.read().lstrip("\n")

with Session(engine) as session:
    emails = [
        s.email
        for s in session.exec(
            select(Subscription).where(col(Subscription.unsubscribed_at).is_(None))
        )
    ]

with smtp_login() as smtp:
    for email in emails:
        unsub = f"{API_BASE}/unsubscribe?email={email}&token={sign('unsub', email)}"
        msg = EmailMessage()
        msg["From"] = FROM
        msg["To"] = email
        msg["Subject"] = subject
        msg["List-Unsubscribe"] = f"<{unsub}>"
        msg["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click"
        msg.set_content(f"{body}{SIGNATURE}\nUnsubscribe: {unsub}\n")
        smtp.send_message(msg)
