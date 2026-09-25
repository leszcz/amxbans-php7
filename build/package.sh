#!/usr/bin/env bash
# Builds the production package of AMXBans.
#
#   build/package.sh            -> dist/amxbans/            (directory, used by the deploy workflow)
#   build/package.sh --zip      -> dist/amxbans-<version>.zip (release asset, for FTP uploads)
#
# The version is read from AMXB_VERSION in include/bootstrap.php.
# Files listed in build/package-filter.txt are not included.
set -euo pipefail

cd "$(dirname "$0")/.."
VERSION=$(sed -n "s/.*define('AMXB_VERSION', '\([^']*\)').*/\1/p" include/bootstrap.php)
OUT=dist/amxbans

rm -rf dist
mkdir -p "$OUT"
# Only files tracked by git (ignored local files such as vendor/**/tests or db.config are never packaged).
git ls-files -z | rsync -a --from0 --files-from=- --filter="merge build/package-filter.txt" ./ "$OUT/"

# Runtime directories must exist (they are writable on the server).
mkdir -p "$OUT/templates_c" "$OUT/include/files" "$OUT/include/backup" "$OUT/temp"

echo "AMXBans $VERSION packaged in $OUT ($(find "$OUT" -type f | wc -l) files)"

if [[ "${1:-}" == "--zip" ]]; then
    (cd dist && zip -qr9 "amxbans-$VERSION.zip" amxbans)
    echo "dist/amxbans-$VERSION.zip ($(du -h "dist/amxbans-$VERSION.zip" | cut -f1))"
fi
