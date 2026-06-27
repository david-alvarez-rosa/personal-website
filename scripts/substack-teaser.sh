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
url="${BASE_URL}/posts/${slug}/"

title=$(grep -m1 '^title = ' "$src" | sed -E 's/^title = "(.*)"/\1/')
subtitle=$(grep -m1 '^subtitle = ' "$src" | sed -E 's/^subtitle = "(.*)"/\1/' || true)

intro=$(awk 'NR==1 && /^\+\+\+/ {f=1; next} f && /^\+\+\+/ {f=0; next}
             !f && /^## / {exit} !f' "$src" \
  | sed -E "s/\[\^fn:[0-9]+\]//g; s#\]\(/#](${BASE_URL}/#g")

out="${TMPDIR:-/tmp}/substack-${slug}.html"

{
  echo "# ${title}"
  [[ -z "$subtitle" ]] || printf '\n*%s*\n' "$subtitle"
  printf '\n%s\n' "$intro"
  printf '\nContinue reading\xe2\x80\x94[%s](%s)\n' "$title" "$url"
} | pandoc --from=markdown --to=html --no-highlight -o "$out"

firefox "$out"

echo >&2 "In the browser: select-all + copy, then paste into a new Substack post."
echo >&2 "Set the title manually; the link is the canonical original (no duplicate content)."
