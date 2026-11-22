#!/usr/bin/env -S uv run --script
# /// script
# requires-python = ">=3.12"
# dependencies = [
#     "fonttools[woff]>=4.55",
# ]
# ///
from __future__ import annotations

import hashlib
from pathlib import Path

from fontTools.subset import Options, Subsetter
from fontTools.ttLib import TTFont

FONTS = Path(__file__).resolve().parent.parent / "static" / "fonts"
ASCII = set(range(0x20, 0x7F))
LATIN = (
    ASCII
    | {0xA0, 0x2009, 0x200A, 0x2010, 0x2011, 0x202F}
    | set(map(ord, "¡¿§©«»·µ×÷°±½¼¾€£áéíóúñüçàèìòùâêîôûäëïöÁÉÍÓÚÑÜÇ–—‘’“”„…†‡•′″−"))
)
CAPS = set(range(0x41, 0x5B)) | set(map(ord, "ÁÉÍÓÚÑÜÇ"))
INVISIBLE = (
    set(range(0x00, 0x20))
    | set(range(0x7F, 0xA0))
    | {0xAD, 0xFEFF}
    | set(range(0x200B, 0x2010))
    | set(range(0x2028, 0x202F))
    | set(range(0x2060, 0x2070))
)
GOOGLE_LATIN = (
    set(range(0x00, 0x100))
    | {0x131, 0x152, 0x153, 0x2BB, 0x2BC, 0x2C6, 0x2DA, 0x2DC, 0x304, 0x308, 0x329}
    | set(range(0x2000, 0x2070))
    | {0x20AC, 0x2122, 0x2191, 0x2193, 0x2212, 0x2215, 0xFEFF, 0xFFFD}
)
FEATURES = ["kern", "liga", "ccmp", "locl", "mark", "mkmk", "rvrn", "calt", "clig", "rlig"]
FACES = {
    "alegreya-normal": LATIN,
    "alegreya-italic": LATIN,
    "alegreya-sc-normal-500": LATIN - CAPS,
    "alegreya-sc-normal-700": LATIN - CAPS,
    "alegreya-sans-normal-400": ASCII,
    "alegreya-sans-normal-500": LATIN,
    "alegreya-sans-normal-700": LATIN,
    "inconsolata-normal": ASCII,
}


def unicode_range(codepoints: set[int]) -> str:
    parts = []
    points = sorted(codepoints)
    start = prev = points[0]
    for point in points[1:] + [None]:
        if point is not None and point == prev + 1:
            prev = point
            continue
        parts.append(f"U+{start:04X}" if start == prev else f"U+{start:04X}-{prev:04X}")
        start = prev = point
    return ", ".join(parts)


def subset(face: str, wanted: set[int]) -> None:
    source = FONTS / f"{face}-latin.woff2"
    font = TTFont(source, recalcTimestamp=False)
    cmap = set(font.getBestCmap())
    codepoints = wanted & cmap
    core_range = codepoints | INVISIBLE | (GOOGLE_LATIN - cmap)
    options = Options()
    options.flavor = "woff2"
    options.layout_features = FEATURES
    options.hinting = True
    options.notdef_outline = True
    options.name_IDs = [1, 2, 3, 4, 6]
    options.drop_tables += ["DSIG"]
    subsetter = Subsetter(options=options)
    subsetter.populate(unicodes=sorted(codepoints))
    subsetter.subset(font)
    font.flavor = "woff2"
    for stale in FONTS.glob(f"{face}-core.*.woff2"):
        stale.unlink()
    tmp = FONTS / f"{face}-core.tmp"
    font.save(tmp)
    digest = hashlib.sha256(tmp.read_bytes()).hexdigest()[:8]
    out = tmp.rename(FONTS / f"{face}-core.{digest}.woff2")
    print(f"{out.name}  {source.stat().st_size} -> {out.stat().st_size} B")
    print(f"    unicode-range: {unicode_range(core_range)};")
    print(f"    unicode-range: {unicode_range(GOOGLE_LATIN - core_range)};")


def main() -> None:
    for face, wanted in FACES.items():
        subset(face, wanted)


if __name__ == "__main__":
    main()
