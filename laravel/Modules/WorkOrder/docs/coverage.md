---
name: workorder-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/WorkOrder — 2026-09-06 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# WorkOrder — quality gate status (2026-09-06/07)

## PHPStan

- Before: 75 real errors (Filament v4→v5 deprecations + `cast.*` on mixed + 1
  `generics.notGeneric` on `WorkOrder.php` + cross-module `mixed` cascade in
  `PeriodicSummaryTest.php`).
- After: **0 errors** (`phpstan analyse Modules/WorkOrder`, clean cache, verified
  twice).

## PHPMD

Ran with built-in rulesets (`cleancode,codesize,design,naming,unusedcode`) — no
module-specific ruleset XML exists for WorkOrder. Remaining findings are pre-existing
style notes (`StaticAccess` on Filament/Facade static calls — standard pattern
throughout this codebase, `TooManyPublicMethods`/`CouplingBetweenObjects` on
`WorkOrder.php`/`WorkOrderPolicy.php`, `UnusedFormalParameter` on Filament relation
manager lifecycle hooks) — none introduced by this fix pass, left as-is (out of scope
for a PHPStan cleanup).

## Pest

No module-local `phpunit.xml` (uses root config). Full-module run:
**7 passed / 45 failed / 15 skipped** before and after this fix pass (unchanged) — all
45 failures are `SQLSTATE[42S02]: Base table or view not found` against the
`workorder_data_test` database (missing `customers`, `roles`, etc. — the whole test DB
schema is out of sync with current migrations). This is a pre-existing environment
blocker, not something introduced or fixable via this PHPStan pass — per the
"data is sacred, no `migrate:fresh`/`refresh`" rule, not touched. See second-brain
`project_test_db_missing_migrations_blocks_feature_tests.md`. Coverage could not be
meaningfully increased while this blocker stands; no regression introduced.

## PHPInsights

Not run in this pass (a repo hook incorrectly claims it's uninstalled — it actually
is, see second-brain `feedback_phpinsights_removed_use_exact_gate_commands.md`).
Follow-up: run `./tools/phpinsights.sh analyse Modules/WorkOrder` when convenient.
