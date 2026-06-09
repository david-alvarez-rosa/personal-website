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
SIGNATURE_HTML = (
    "<p>Best,<br>d.</p>\n"
    '<div style="margin-top:1.5em;text-align:center">'
    '<div style="font-size:16px;color:#555555">'
    '<a href="https://david.alvarezrosa.com" '
    'style="color:#003366;text-decoration:none">david.alvarezrosa.com</a>'
    " &middot; "
    '<a href="mailto:david@alvarezrosa.com" '
    'style="color:#003366;text-decoration:none">david@alvarezrosa.com</a>'
    " &middot; "
    "+34 647 13 39 30"
    "</div></div>"
)


def _linkify(match):
    text = match.group("mdtext")
    url = match.group("mdurl") or match.group("bare")
    if text is None:
        text = re.sub(r"^https?://", "", url)
    return f'<a href="{url}" style="color:#003366;text-decoration:none">{text}</a>'


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
    return (
        "<html><body>\n"
        '<div style="background:#fcfcfc">\n'
        '<div style="max-width:34em;margin:0 auto;padding:0 20px;'
        "font-family:Alegreya,Georgia,'Times New Roman',serif;"
        'color:#111111;font-size:18px;line-height:1.55">\n'
        f"{to_html(body)}\n{SIGNATURE_HTML}\n{footer}"
        "\n</div>\n</div>\n</body></html>"
    )


def footer_html(unsub):
    return (
        '<p style="margin-top:1em;font-size:12px;color:#bbbbbb">'
        f'<a href="{html.escape(unsub)}" '
        'style="color:#bbbbbb;text-decoration:underline">Unsubscribe</a></p>'
    )
