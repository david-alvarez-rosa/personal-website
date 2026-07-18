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
canonical="${BASE_URL}/posts/${slug}/"
out="${TMPDIR:-/tmp}/devto-${slug}.md"

title=$(grep -m1 '^title = ' "$src" | sed -E 's/^title = "(.*)"/\1/')
subtitle=$(grep -m1 '^subtitle = ' "$src" | sed -E 's/^subtitle = "(.*)"/\1/' || true)
# dev.to tags: lowercase alphanumeric, comma-separated, max 4. Drop the "blog" tag.
tags=$(grep -m1 '^tags = ' "$src" | grep -oE '"[^"]+"' | tr -d '"' \
  | tr '[:upper:]' '[:lower:]' | sed -E 's/[^a-z0-9]//g' | grep -vxE 'blog|' \
  | head -4 | paste -sd, - || true)

# dev.to renders KaTeX via Liquid tags, not \\( \\) / \\[ \\].
mathfix() { cat; }
if grep -q '^latex = true' "$src"; then
  mathfix() {
    sed -E 's/\\\\\[/{% katex %}/g; s/\\\\\]/{% endkatex %}/g;
            s/\\\\\(/{% katex inline %}/g; s/\\\\\)/{% endkatex %}/g;
            s/\\_/_/g'
  }
fi

body="${out%.md}.body"
awk 'NR==1 && /^\+\+\+/ {f=1; next} f && /^\+\+\+/ {f=0; next} !f' "$src" \
  | mathfix \
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
        if (c != "") {print ""; print "*" c "*"}
        print ""
        next
      } {print}' \
  | sed -E "s#\./assets/images/#${BASE_URL}/images/#g" \
  | sed -E 's/\[\^fn:([0-9]+)\]/[^\1]/g' \
  | awk '
      function flush() { if (buf != "") { print buf; buf = "" } }
      /^```/ { flush(); print; incode = !incode; next }
      incode { print; next }
      /^\{% katex %\}$/ { flush(); print; inkatex = 1; next }
      inkatex { print; if ($0 ~ /^\{% endkatex %\}$/) inkatex = 0; next }
      /^[[:space:]]*$/ { flush(); print ""; next }
      /^\|/ || /^#{1,6} / || /^>/ || /^!\[/ { flush(); print; next }
      /^[[:space:]]*<[a-zA-Z\/!]/ { flush(); print; next }
      /^[[:space:]]*([-*+]|[0-9]+\.)[[:space:]]/ { flush(); print; next }
      /^(-{3,}|\*{3,}|_{3,})[[:space:]]*$/ { flush(); print; next }
      /^\[\^[0-9]+\]: / { flush(); buf = $0; next }
      { line = $0; sub(/^[[:space:]]+/, "", line)
        buf = (buf == "" ? line : buf " " line) }
      END { flush() }' > "$body"

# Cover image: same rule as og:image in layouts/baseof.html — first image in
# the post, else fall back to the home illustration.
cover=$(grep -oE '!\[[^]]*\]\([^)]+\)' "$body" | head -1 | sed -E 's/.*\(([^)]+)\)$/\1/' || true)
[[ -n "$cover" ]] || cover="${BASE_URL}/images/home-illustration.png"

{
  echo "---"
  echo "title: ${title}"
  echo "published: true"
  echo "description: ${subtitle}"
  echo "tags: ${tags}"
  echo "canonical_url: ${canonical}"
  echo "cover_image: ${cover}"
  echo "# Use a ratio of 100:42 for best results."
  echo "---"
  echo ""
  cat "$body"
} > "$out"
rm -f "$body"

if command -v xclip >/dev/null; then
  xclip -selection clipboard < "$out"
  echo "Copied dev.to markdown to clipboard." >&2
fi
command -v firefox >/dev/null && firefox "https://dev.to/new" >/dev/null 2>&1 &

echo "Wrote $out" >&2
echo "In dev.to: select-all in the editor, paste, then check tags + add a cover image." >&2
echo "canonical_url is set to $canonical (no duplicate-content penalty)." >&2
