import html
import re

SIGN_OFF = "Best,\nd."

SIGNATURE = (
    "-- \n"
    + """David Álvarez Rosa

web    david.alvarezrosa.com
email  david@alvarezrosa.com
tel    +34 647 13 39 30
irc    dalvrosa @ Libera.Chat

I'm sending this message at a time that suits me.  Please know that I
don't expect you to read, reply, or take action on this outside of your
regular working hours.

[ To any NSA and FBI agents reading my email: please consider    ]
[ whether defending the US Constitution against all enemies,     ]
[ foreign or domestic, requires you to follow Snowden's example. ]"""
)


def finalize(msg):
    for part in msg.iter_parts():
        del part["MIME-Version"]
    return msg


def _linkify(match):
    text = match.group("mdtext")
    url = match.group("mdurl") or match.group("bare")
    if text is None:
        text = re.sub(r"^https?://", "", url)
    return f'<a href="{url}">{text}</a>'


def to_text(text):
    return re.sub(r"\[[^\]]+\]\((https?://[^)]+)\)", r"\1", text)


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


def _bracket(side):
    return f'<td style="width:4px;border:1px solid;border-{side}:0">&nbsp;</td>'


def signature_html():
    blocks = []
    for block in SIGNATURE.split("\n\n"):
        lines = block.split("\n")
        if all(line.startswith("[ ") and line.endswith(" ]") for line in lines):
            text = html.escape(" ".join(line[2:-2].strip() for line in lines))
            blocks.append(
                '<table role="presentation" cellpadding="0" cellspacing="0" '
                'style="border-collapse:collapse;font-family:monospace;margin:0 0 1em">'
                f'<tr>{_bracket("right")}<td style="padding:0 0.5em">{text}</td>'
                f"{_bracket('left')}</tr></table>"
            )
        elif max(map(len, lines)) < 60:
            text = "<br>".join(html.escape(line) for line in lines)
            blocks.append(f'<p style="margin:0 0 1em;white-space:pre-wrap">{text}</p>')
        else:
            blocks.append(
                f'<p style="margin:0 0 1em">{html.escape(" ".join(lines))}</p>'
            )
    return f'<div style="font-family:monospace;margin-top:1em;max-width:66ch">{"".join(blocks)}</div>'


def email_html(body, footer="", ps="", title=""):
    sign_off = html.escape(SIGN_OFF).replace("\n", "<br>")
    return f"""<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{html.escape(title)}</title>
    <style>a {{ color:#003366; text-decoration:underline }} </style>
  </head>
  <body>
    {to_html(body)}
    <p>{sign_off}</p>
    {to_html(ps) if ps else ""}
    {signature_html()}
    {footer}
  </body>
</html>"""


def footer_html(unsub):
    return f"""
<p style="margin-top:1em;font-size:0.75em;color:#bbbbbb">
  <a href="{html.escape(unsub)}" style="color:#bbbbbb;text-decoration:underline">Unsubscribe</a>
</p>
"""
