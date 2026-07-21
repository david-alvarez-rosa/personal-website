#!/usr/bin/env sh
set -eux

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
REMOTE="${API_HOST:-root@ssh.alvarezrosa.com}"

if [ -n "${DB_PATH:-}" ]; then
    snapshot="$(cd "$(dirname "$DB_PATH")" && pwd)/$(basename "$DB_PATH")"
    echo "Using $snapshot, not pulling from $REMOTE."
else
    snapshot="$(mktemp)"
    trap 'rm -f "$snapshot"' EXIT
    echo "Pulling live subscriber list from $REMOTE..."
    scp -q "$REMOTE:/var/lib/api/subscriptions.db" "$snapshot"
fi

set -a
. "$ROOT/.env"
set +a

cd "$ROOT"
DB_PATH="$snapshot" uv run python -m api.send
