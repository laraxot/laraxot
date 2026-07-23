#!/usr/bin/env bash
set -euo pipefail
ROOT="/var/www/_bases/base_techplanner_fila5"
cd "$ROOT"
FORBIDDEN=(
  _docs
  scripts
  bashscripts
  docs/archive
  docs/archived
  docs/legacy
  docs/workbench
  .circleci
  .claude-audit
  tests/.claude-audit
  _bmad-output
  test-results
  .devcontainer
  .kilocode
  .kiro
  .ralph
)
for gi in $(find laravel/Modules -maxdepth 2 -name ".gitignore" | sort); do
  lock="${gi}.lock"
  if [[ -f "$lock" ]]; then
    echo "SKIP locked: $gi"
    continue
  fi
  touch "$lock"
  tmp="$(mktemp)"
  cat > "$tmp" <<'INNER'
# Auto-generated forbidden paths — do not edit manually
# Reason: root cleanup policy (see docs/wiki/rules/root-cleanup-policy.md)

INNER
  for p in "${FORBIDDEN[@]}"; do
    echo "$p"
  done | sort -u >> "$tmp"
  echo "" >> "$tmp"
  if [[ -f "$gi" ]]; then
    grep -v -E '^#?(_docs|scripts|bashscripts|docs/archive|docs/archived|docs/legacy|docs/workbench|\.circleci|\.claude-audit|tests/\.claude-audit|_bmad-output|test-results|\.devcontainer|\.kilocode|\.kiro|\.ralph)' "$gi" 2>/dev/null >> "$tmp" || true
  fi
  awk '!seen[$0]++' "$tmp" > "$gi"
  rm -f "$tmp" "$lock"
  echo "UPDATED: $gi"
done
for gi in $(find laravel/Themes -maxdepth 2 -name ".gitignore" | sort); do
  lock="${gi}.lock"
  if [[ -f "$lock" ]]; then
    echo "SKIP locked: $gi"
    continue
  fi
  touch "$lock"
  tmp="$(mktemp)"
  cat > "$tmp" <<'INNER'
# Auto-generated forbidden paths — do not edit manually
# Reason: root cleanup policy (see docs/wiki/rules/root-cleanup-policy.md)

INNER
  for p in "${FORBIDDEN[@]}"; do
    echo "$p"
  done | sort -u >> "$tmp"
  echo "" >> "$tmp"
  if [[ -f "$gi" ]]; then
    grep -v -E '^#?(_docs|scripts|bashscripts|docs/archive|docs/archived|docs/legacy|docs/workbench|\.circleci|\.claude-audit|tests/\.claude-audit|_bmad-output|test-results|\.devcontainer|\.kilocode|\.kiro|\.ralph)' "$gi" 2>/dev/null >> "$tmp" || true
  fi
  awk '!seen[$0]++' "$tmp" > "$gi"
  rm -f "$tmp" "$lock"
  echo "UPDATED: $gi"
done
