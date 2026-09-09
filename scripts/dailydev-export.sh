#!/usr/bin/env bash
set -euo pipefail

BASE_URL="https://david.alvarezrosa.com"
COMPOSER="https://daily.dev/squads/create"

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
commentary=$(grep -m1 '^subtitle = ' "$src" | sed -E 's/^subtitle = "(.*)"/\1/' || true)

if [[ -z "$commentary" ]]; then
  commentary=$(awk 'NR==1 && /^\+\+\+/ {f=1; next} f && /^\+\+\+/ {f=0; next}
                    !f && /^## / {exit}
                    !f && NF {p=1; print; next}
                    !f && p {exit}' "$src" \
    | sed -E 's/\[\^fn:[0-9]+\]//g; s/\[([^]]*)\]\([^)]*\)/\1/g; s/[_*`]//g' \
    | paste -sd' ' - | sed -E 's/[[:space:]]+/ /g; s/^ //; s/ $//')
fi

link=$(python3 -c 'import sys,urllib.parse; print(urllib.parse.quote(sys.argv[1], safe=""))' "$url")
body=$(python3 -c 'import sys,urllib.parse; print(urllib.parse.quote(sys.argv[1][:10000], safe=""))' "$commentary")

composer="${COMPOSER}?link=${link}&body=${body}"
[[ -z "${DAILYDEV_SQUAD:-}" ]] || composer="${composer}&sid=$(python3 -c 'import sys,urllib.parse; print(urllib.parse.quote(sys.argv[1], safe=""))' "$DAILYDEV_SQUAD")"

if command -v xclip >/dev/null; then
  printf '%s' "$commentary" | xclip -selection clipboard
  echo "Copied the commentary to clipboard." >&2
fi
command -v firefox >/dev/null && firefox "$composer" >/dev/null 2>&1 &

echo "Title:      $title" >&2
echo "Link:       $url" >&2
echo "Commentary: $commentary" >&2
echo >&2
echo "The composer opens on the Share tab with link + commentary prefilled; press Post." >&2
echo "daily.dev pulls the card title, description and image from the page's OG tags." >&2
echo "Set DAILYDEV_SQUAD=<squad-handle> to post into a Squad instead of your profile." >&2
