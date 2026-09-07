---
name: production-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Production — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Production — quality gate status (2026-09-07)

## PHPStan

- Before: 3 errors — Filament v4->v5 `Table::actions()`/`bulkActions()` deprecated,
  replaced by `recordActions()`/`toolbarActions()`.
- Fix: mechanical rename in `ProductionOrderResource.php`,
  `ProductionPhasesRelationManager.php`.
- After: **0 errors** (`phpstan analyse Modules/Production`, clean cache).

## PHPMD

No findings at all in this module.

## Pest

Not run for this module in this pass (mechanical, zero-behavior-change rename;
verified via PHPStan + `php -l` only). No functional change to test coverage.
