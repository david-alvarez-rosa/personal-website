#!/usr/bin/env bash
set -euo pipefail

BASE_URL="https://david.alvarezrosa.com"

latest_published() {
  local dir now f d e
  dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/../content/posts" && pwd)"
  now=$(date +%s)
  for f in "$dir"/*.md; do
    [[ "$(basename "$f")" == _index.md ]] && continue
    grep -q '^draft = false' "$f" || continue
    d=$(grep -m1 '^date = ' "$f" | sed -E 's/^date = //')
    e=$(date -d "$d" +%s 2>/dev/null) || continue
    (( e <= now )) && printf '%s\t%s\n' "$e" "$f"
  done | sort -rn | head -1 | cut -f2-
}

if [[ $# -ge 1 ]]; then
  src="$1"
else
  src="$(latest_published)"
  [[ -n "$src" ]] || { echo "No published post found." >&2; exit 1; }
  echo "Defaulting to latest published post: $src" >&2
fi
slug="$(basename "$src" .md)"
root="$(cd "$(dirname "$src")/../.." && pwd)"
out="${TMPDIR:-/tmp}/medium-${slug}.html"
tabledir="$HOME/tmp"
title=$(grep -m1 '^title = ' "$src" | sed -E 's/^title = "(.*)"/\1/')
subtitle=$(grep -m1 '^subtitle = ' "$src" | sed -E 's/^subtitle = "(.*)"/\1/' || true)
pubdate=$(date -d "$(grep -m1 '^date = ' "$src" | sed -E 's/^date = //')" '+%B %-d, %Y')

if grep -qE '^\|' "$src"; then
  (cd "$root" && node scripts/medium-tables.mjs "$slug")
fi

fmt="markdown-implicit_figures"
mathfix() { cat; }
if grep -q '^latex = true' "$src"; then
  fmt="$fmt+tex_math_single_backslash"
  mathfix() { sed -E 's/\\\\/\\/g; s/\\_/_/g'; }
fi

{
  echo "# ${title}"
  [[ -n "$subtitle" ]] && echo -e "\n${subtitle}\n"
  awk 'NR==1 && /^\+\+\+/ {f=1; next} f && /^\+\+\+/ {f=0; next} !f' "$src" \
    | mathfix \
    | awk '/^```/ {c=!c; print; next} c && /^[ \t]*$/ {print " "; next} {print}' \
    | awk -v base="$BASE_URL" '
        /\{\{<[ ]*figure/ {
          l=$0; sub(/[ ]*>}}[ ]*$/, "", l)
          s=l; sub(/.*src="/, "", s); sub(/".*/, "", s)
          sub(/^\.\/assets/, "", s); if (s ~ /^\//) s = base s
          c=""
          if (l ~ /caption="/) {
            c=l; sub(/.*caption="/, "", c); sub(/"$/, "", c)
            gsub(/\\"/, "\"", c)
            gsub(/<span class="figure-number">[^<]*<\/span>/, "", c)
          }
          print ""; print "![](" s ")"
          if (c != "") {print ""; print c}
          print ""
          next
        } {print}' \
    | awk '
        /^\|/ {if (!t) {t=1; n++; print ""; print "**\xe2\x9f\xa8 paste table " n " screenshot \xe2\x9f\xa9**"; print ""} next}
        {t=0; print}' \
    | sed -E "s#\./assets/images/#${BASE_URL}/images/#g" \
    | sed -E "s/^\[\^fn:([0-9]+)\]: /[\1] /; s/\[\^fn:([0-9]+)\]/[\1]/g" \
    | awk '/^\[[0-9]+\] / {if (!seen) {print "\n---\n\n**Notes**"; seen=1} print ""} {print}'
  echo -e "\n---\n"
  echo "*Originally published at ${BASE_URL} on ${pubdate}.*"
} | pandoc --from="$fmt" --to=html --no-highlight -o "$out"

firefox "$out"

echo "In Medium: fix subtitle + captions; paste table screenshots from $tabledir."
