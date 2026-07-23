#!/usr/bin/env bash
# PHPMD wrapper — prefers local phar (tools/phpmd.phar) if present, else global phpmd (phive install phpmd --global)
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
if [[ -f "${ROOT}/tools/phpmd.phar" ]]; then
  exec php "${ROOT}/tools/phpmd.phar" "$@"
fi
if command -v phpmd >/dev/null 2>&1; then
  exec phpmd "$@"
fi
echo "phpmd not found: neither tools/phpmd.phar nor a global 'phpmd' binary exist. Install with: sudo phive install phpmd --global" >&2
exit 127
