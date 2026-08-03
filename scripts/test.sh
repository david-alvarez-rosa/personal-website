#!/usr/bin/env sh

set -eux

ENGINE="${CONTAINER_ENGINE:-podman}"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"

HASH="$(sha256sum "$ROOT/Dockerfile" | cut -d' ' -f1)"
if [ "$("$ENGINE" image inspect personal-website --format '{{index .Labels "dockerfile-sha"}}' 2>/dev/null)" != "$HASH" ]; then
  "$ENGINE" build --label "dockerfile-sha=$HASH" -t personal-website - < "$ROOT/Dockerfile"
fi

status=0
"$ENGINE" run --rm --shm-size=1g \
  -v "$ROOT":/work:Z \
  -v personal-website-node_modules:/work/node_modules \
  -w /work personal-website \
  sh -c 'pnpm install --frozen-lockfile --store-dir /work/node_modules/.pnpm-store --config.confirm-modules-purge=false && exec pnpm exec playwright test "$@"' -- "$@" || status=$?

[ "$status" -ne 0 ] && [ -z "${CI:-}" ] && pnpm exec playwright show-report "$ROOT/playwright-report" || true
exit "$status"
