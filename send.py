import re
import textwrap
import tomllib
import webbrowser
from email.message import EmailMessage
from pathlib import Path

from sqlmodel import Session, col, select

from backend import (
    API_BASE,
    EMAIL_POLICY,
    FROM,
    SITE_BASE,
    Subscription,
    engine,
    sign,
    smtp_login,
)
from mail import SIGNATURE, email_html, footer_html


def clean(md):
    md = re.sub(r"\[\^[^\]]+\]", "", md)
    md = re.sub(r"\[([^\]]+)\]\([^)]*\)", r"\1", md)
    md = re.sub(r"`([^`]*)`", r"\1", md)
    md = re.sub(r"(?<!\w)([_*]{1,2})(.+?)\1(?!\w)", r"\2", md)
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
excerpt = clean(body.split("\n## ", 1)[0])
url = f"{SITE_BASE}/posts/{slug}/"
intro = """Hi!

I just published a new post."""
prose = f"""{intro}

{excerpt}

Continue reading—{subject}""".replace("—", "--")
text_body = f"""{wrap(prose)}
{url}"""
html_body = f"""{intro}

{excerpt}

Continue reading—[{subject}]({url})."""

with Session(engine) as session:
    emails = [
        s.email
        for s in session.exec(
            select(Subscription).where(col(Subscription.unsubscribed_at).is_(None))
        )
    ]

preview = "/tmp/newsletter_preview.html"
sample = f"{API_BASE}/unsubscribe?email=you@example.com&token=sample"
Path(preview).write_text(email_html(html_body, footer_html(sample)))
webbrowser.open(f"file://{preview}")

print(f"Subject: {subject}\n")
print(f"{text_body}\n{SIGNATURE}")
print(f"Sending to {len(emails)} addresses:")
for e in emails:
    print(f"  {e}")
if input("Send? (y/n) ").strip().lower() != "y":
    raise SystemExit

with smtp_login() as smtp:
    for email in emails:
        unsub = f"{API_BASE}/unsubscribe?email={email}&token={sign('unsub', email)}"
        msg = EmailMessage(policy=EMAIL_POLICY)
        msg["From"] = FROM
        msg["To"] = email
        msg["Subject"] = subject
        msg["List-Unsubscribe"] = f"<{unsub}>"
        msg["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click"
        msg.set_content(f"{text_body}\n{SIGNATURE}\nUnsubscribe: {unsub}\n")
        msg.add_alternative(email_html(html_body, footer_html(unsub)), subtype="html")
        smtp.send_message(msg)
