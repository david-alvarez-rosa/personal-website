import html
import re

SIGN_OFF = "Best,\nd."

SIGNATURE = """--
David Álvarez Rosa

web    david.alvarezrosa.com
email  david@alvarezrosa.com
tel    +34 647 13 39 30"""


def _linkify(match):
    text = match.group("mdtext")
    url = match.group("mdurl") or match.group("bare")
    if text is None:
        text = re.sub(r"^https?://", "", url)
    return f'<a href="{url}">{text}</a>'


def to_html(text):
    paragraphs = []
    for p in html.escape(text.strip()).split("\n\n"):
        p = p.replace("\n", " ")
        p = re.sub(
            r"\[(?P<mdtext>[^\]]+)\]\((?P<mdurl>https?://[^)]+)\)|(?P<bare>https?://\S+)",
            _linkify,
            p,
        )
        paragraphs.append(f"<p>{p}</p>")
    return "\n".join(paragraphs)


def email_html(body, footer="", ps=""):
    sign_off = html.escape(SIGN_OFF).replace("\n", "<br>")
    return f"""
<html>
  <head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>a {{ color:#003366; text-decoration:none }} </style>
  </head>
  <body>
    {to_html(body)}
    <p>{sign_off}</p>
    {to_html(ps) if ps else ""}
    <pre style="font-family:monospace">{html.escape(SIGNATURE)}</pre>
    {footer}
  </body>
</html>"""


def footer_html(unsub):
    return f"""
<p style="margin-top:1em;font-size:0.75em;color:#bbbbbb">
  <a href="{html.escape(unsub)}" style="color:#bbbbbb;text-decoration:underline">Unsubscribe</a>
</p>
"""
