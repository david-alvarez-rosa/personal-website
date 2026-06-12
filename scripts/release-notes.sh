#!/usr/bin/env sh

set -eux

repo="https://github.com/david-alvarez-rosa/personal-website"
tag="$1"
prev="$(git tag --sort=creatordate | awk -v t="$tag" '$0 == t { print p } { p = $0 }')"

printf "## What's changed\n\n"
git log --format='- %s' "${prev:+$prev..}$tag"

if [ -n "$prev" ]; then
  printf '\n**Full Changelog**: %s/compare/%s...%s\n' "$repo" "$prev" "$tag"
else
  printf '\n**Full Changelog**: %s/commits/%s\n' "$repo" "$tag"
fi
