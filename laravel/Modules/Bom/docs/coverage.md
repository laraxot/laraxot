---
name: bom-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Bom — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Bom — quality gate status (2026-09-07)

## PHPStan

- Before: 3 errors — Filament v4->v5 `Table::actions()`/`bulkActions()` deprecated,
  replaced by `recordActions()`/`toolbarActions()`.
- Fix: mechanical rename in `BomResource.php`, `BomItemsRelationManager.php`.
- After: **0 errors** (`phpstan analyse Modules/Bom`, clean cache).

## PHPMD

No new findings in touched files (pre-existing `ElseExpression` note in
`ViewBom.php`, unrelated to this fix).

## Pest

Not run for this module in this pass (mechanical, zero-behavior-change rename;
verified via PHPStan + `php -l` only). No functional change to test coverage.
