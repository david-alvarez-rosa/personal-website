#!/usr/bin/env sh

set -eux

ENGINE="${CONTAINER_ENGINE:-podman}"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"

if ! "$ENGINE" image inspect personal-website >/dev/null 2>&1; then
  "$ENGINE" build -t personal-website - < "$ROOT/Dockerfile"
fi

status=0
"$ENGINE" run --rm --shm-size=1g \
  -v "$ROOT":/work:Z \
  -v personal-website-node_modules:/work/node_modules \
  -w /work personal-website \
  sh -c 'pnpm install --frozen-lockfile --store-dir /work/node_modules/.pnpm-store --config.confirm-modules-purge=false && exec pnpm exec playwright test "$@"' -- "$@" || status=$?

[ "$status" -ne 0 ] && [ -z "${CI:-}" ] && pnpm exec playwright show-report "$ROOT/playwright-report" || true
exit "$status"
