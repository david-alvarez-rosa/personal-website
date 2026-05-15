#!/usr/bin/env -S uv run --script
# /// script
# requires-python = ">=3.12"
# dependencies = [
#     "fonttools[woff]>=4.55",
# ]
# ///
"""Generate per-letter woff2 subsets of GoudyInitialen for drop caps.

The full font ships ~30 KB of glyphs, but each page only uses one letter
(the drop cap of the first paragraph). This script produces one subset
per uppercase letter A-Z, each containing a single glyph.

Paired with @font-face rules carrying unicode-range, the browser fetches
only the file matching the letter actually rendered on the page.

Run from the repo root whenever the source font changes:

    ./scripts/subset-dropcap-font.py
"""

from __future__ import annotations

from pathlib import Path

from fontTools.subset import Options, Subsetter
from fontTools.ttLib import TTFont

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
SOURCE = SCRIPT_DIR / "GoudyInitialen.woff2"
OUT_DIR = REPO_ROOT / "static" / "fonts"
LETTERS = [chr(c) for c in range(ord("A"), ord("Z") + 1)]


def subset_letter(letter: str) -> int:
    font = TTFont(SOURCE)
    options = Options()
    options.flavor = "woff2"
    options.with_zopfli = True
    options.desubroutinize = True
    options.drop_tables += ["DSIG", "GSUB", "GPOS"]
    options.layout_features = []
    options.name_IDs = []
    options.notdef_outline = False
    options.recommended_glyphs = False
    options.hinting = False
    options.legacy_kern = False
    subsetter = Subsetter(options=options)
    subsetter.populate(unicodes=[ord(letter)])
    subsetter.subset(font)
    out_path = OUT_DIR / f"GoudyInitialen-{letter}.woff2"
    font.flavor = "woff2"
    font.save(out_path)
    return out_path.stat().st_size


def main() -> None:
    if not SOURCE.exists():
        raise SystemExit(f"source font not found: {SOURCE}")
    total = 0
    for letter in LETTERS:
        size = subset_letter(letter)
        total += size
        print(f"  {letter}: {size:>5} B")
    print(f"\ntotal: {total} B across {len(LETTERS)} files")


if __name__ == "__main__":
    main()
