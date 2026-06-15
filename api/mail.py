import html
import re

SIGNATURE = """
Best,
d.

--
David Álvarez Rosa

web    david.alvarezrosa.com
email  david@alvarezrosa.com
tel    +34 647 13 39 30
"""


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


def email_html(body, footer=""):
    return f"""
<html>
  <head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>a {{ color:#003366; text-decoration:none }} </style>
  </head>
  <body>
    {to_html(body)}
    <p>Best,<br>David</p>
    {footer}
  </body>
</html>"""


def footer_html(unsub):
    return f"""
<p style="margin-top:1em;font-size:0.75em;color:#bbbbbb">
  <a href="{html.escape(unsub)}" style="color:#bbbbbb;text-decoration:underline">Unsubscribe</a>
</p>
"""
