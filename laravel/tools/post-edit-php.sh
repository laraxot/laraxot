#!/usr/bin/env bash
# Post-edit quality gate: phpstan + phpmd + phpinsights + pest (scope file or module tests).
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${ROOT}"

if [[ $# -lt 1 ]]; then
  echo "Usage: ./tools/post-edit-php.sh <file.php> [pest-path]"
  echo "Example: ./tools/post-edit-php.sh Modules/TechPlanner/database/migrations/2026_02_22_000000_create_profiles_table.php Modules/TechPlanner/tests"
  exit 1
fi

FILE="$1"
PEST_PATH="${2:-}"

if [[ ! -f "${FILE}" ]]; then
  echo "FAIL: file not found: ${FILE}"
  exit 1
fi

PHPMD="${PHPMD:-./tools/phpmd.sh}"
fail=0

run() {
  local label="$1"
  shift
  echo ""
  echo "━━━ ${label} ━━━"
  if "$@"; then
    echo "OK: ${label}"
  else
    echo "FAIL: ${label}"
    fail=1
  fi
}

if [[ "${FILE}" == *"/tests/"* || "${FILE}" == *"/Tests/"* ]]; then
  run "Pest file" ./vendor/bin/pest "${FILE}" --compact
else
  run "PHPStan" ./vendor/bin/phpstan analyse "${FILE}" --memory-limit=-1
fi

if [[ -x "${PHPMD}" ]] || command -v "${PHPMD}" >/dev/null 2>&1; then
  run "phpmd" "${PHPMD}" "${FILE}" text phpmd.xml
else
  echo "SKIP: phpmd not found (${PHPMD})"
fi

if [[ -f ./vendor/bin/phpinsights ]]; then
  echo ""
  echo "━━━ phpinsights ━━━"
  ./vendor/bin/phpinsights analyse "${FILE}" --no-interaction || true
fi

if [[ -n "${PEST_PATH}" && -d "${PEST_PATH}" ]]; then
  run "Pest ${PEST_PATH}" ./vendor/bin/pest "${PEST_PATH}" --compact
fi

echo ""
if [[ "${fail}" -eq 0 ]]; then
  echo "✅ Post-edit gate passed (phpstan + phpmd${PEST_PATH:+, pest})"
  exit 0
fi

echo "❌ Post-edit gate failed"
exit 1
