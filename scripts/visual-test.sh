#!/bin/sh

set -eux

ENGINE="${CONTAINER_ENGINE:-podman}"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"

# In CI the image is built separately with a layer cache, so allow skipping it.
if [ -z "${SKIP_BUILD:-}" ]; then
  "$ENGINE" build -t personal-website-visual - < "$ROOT/Dockerfile"
fi

status=0
"$ENGINE" run --rm --shm-size=1g \
  -v "$ROOT":/work:Z \
  -v personal-website-node_modules:/work/node_modules \
  -w /work personal-website-visual \
  sh -c 'pnpm install --frozen-lockfile --store-dir /work/node_modules/.pnpm-store --config.confirm-modules-purge=false && exec pnpm exec playwright test "$@"' -- "$@" || status=$?

[ "$status" -ne 0 ] && [ -z "${CI:-}" ] && pnpm exec playwright show-report "$ROOT/playwright-report" || true
exit "$status"
