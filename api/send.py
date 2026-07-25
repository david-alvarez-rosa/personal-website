import os
import re
import textwrap
import time
import tomllib
import webbrowser
from email.message import EmailMessage
from email.utils import formatdate, make_msgid
from pathlib import Path

from sqlmodel import Session, col, select

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
    smtp_connect,
)
from .mail import SIGN_OFF, SIGNATURE, email_html, finalize, footer_html


def clean(md):
    md = re.sub(r"(?ms)^<(\w+)[^>]*>.*?^</\1>[^\n]*\n?", "", md)
    md = re.sub(r"(?m)^<[^\n]*\n?", "", md)
    md = re.sub(r"(?ms)^\[\^[^\]]+\]:.*?(?=\n\n|\Z)", "", md)
    md = re.sub(r"\[\^[^\]]+\]", "", md)
    md = re.sub(r"\[([^\]]+)\]\([^)]*\)", r"\1", md)
    md = re.sub(r"`([^`]*)`", r"\1", md)
    md = re.sub(r"(?<!\w)([_*]{1,2})(.+?)\1(?!\w)", r"\2", md)
    md = re.sub(r"\n{3,}", "\n\n", md)
    return md.replace("---", "—").strip()


def wrap(text, width=72):
    return "\n\n".join(
        textwrap.fill(p.replace("\n", " "), width, break_on_hyphens=False)
        for p in text.split("\n\n")
    )


posts = []
for path in Path("content/posts").glob("*.md"):
    front, body = path.read_text().split("+++", 2)[1:]
    meta = tomllib.loads(front)
    if not meta.get("draft") and "date" in meta:
        posts.append((meta["date"], meta["title"], path.stem, body))

_, subject, slug, body = max(posts)
lead = clean(body).split("\n\n")[0]
url = f"{SITE_BASE}/posts/{slug}/"
link = f"{url}?utm_source=newsletter&utm_medium=email&utm_campaign={slug}"
text_body = f"""{wrap(f'''Hi,

{lead}

Continue reading—{subject}'''.replace("—", "--"))}
{url}"""
html_body = f"""Hi,

{lead}

Continue reading—[{subject}]({link})."""

ps = os.environ.get("PS", "").strip()
letter = "\n\n".join(filter(None, [text_body, SIGN_OFF, wrap(ps), SIGNATURE]))

with Session(engine) as session:
    emails = [
        s.email
        for s in session.exec(
            select(Subscription).where(col(Subscription.unsubscribed_at).is_(None))
        )
    ]

preview = "/tmp/newsletter_preview.html"
sample = f"{API_BASE}/unsubscribe/{make_token('unsub', 'you@example.com')}"
Path(preview).write_text(email_html(html_body, footer_html(sample), ps, subject))
webbrowser.open(f"file://{preview}")

print(f"Subject: {subject}\n")
print(letter)
print(f"Sending to {len(emails)} addresses:")
for e in emails:
    print(f"  {e}")
if input("Send? (y/n) ").strip().lower() != "y":
    raise SystemExit

sent, failed = [], []
with smtp_connect() as smtp, imap_connect() as imap:
    for email in emails:
        unsub = f"{API_BASE}/unsubscribe/{make_token('unsub', email)}"
        msg = EmailMessage(policy=EMAIL_POLICY)
        msg["From"] = FROM
        msg["To"] = email
        msg["Subject"] = subject
        msg["Date"] = formatdate(localtime=True)
        msg["Message-ID"] = make_msgid(domain="alvarezrosa.com")
        msg["List-Id"] = LIST_ID
        msg["List-Unsubscribe"] = f"<{unsub}>"
        msg["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click"
        msg["Feedback-ID"] = f"{slug}:newsletter:alvarezrosa.com"
        msg["X-Mailer"] = MAILER
        msg.set_content(f"{letter}\n\nUnsubscribe: {unsub}\n", cte="quoted-printable")
        msg.add_alternative(
            email_html(html_body, footer_html(unsub), ps, subject),
            subtype="html",
            cte="quoted-printable",
        )
        finalize(msg)
        try:
            smtp.send_message(msg)
        except Exception as exc:
            failed.append(email)
            print(f"  FAILED  {email}: {exc}")
            continue
        sent.append(email)
        print(f"  sent    {email}")
        try:
            imap.append("Sent", "\\Seen", None, bytes(msg))
        except Exception as exc:
            print(f"  (not archived to Sent: {exc})")
        time.sleep(2)

print(f"\n{len(sent)} sent, {len(failed)} failed.")
if failed:
    print("Failed addresses:")
    for email in failed:
        print(f"  {email}")
    raise SystemExit(1)
