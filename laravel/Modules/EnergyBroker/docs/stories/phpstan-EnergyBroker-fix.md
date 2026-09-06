---
id: phpstan-EnergyBroker-fix
slug: phpstan-EnergyBroker
scope: [module:EnergyBroker, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06
---

## Problema
PHPStan errors in Modules/EnergyBroker

## Solution
1. Analyze with phpstan
2. Fix pattern errors
3. Verify with phpmd + phpinsights + pest
4. Git sync

## Outcome (2026-09-06)
Done in two rounds — see `Modules/Xot/docs/stories/18.15.energybroker-phpstan-l10-fix.story.md`
(canonical story) and `Modules/EnergyBroker/docs/coverage.md` (full before/after):

- Round 1: removed redundant `factory()` overrides (8 errors).
- Round 2: fixed 72 Filament v4→v5 deprecations (`Placeholder`→`TextEntry`,
  `Table::actions/bulkActions`→`recordActions/toolbarActions`, `Action::form`→`schema`).

`phpstan analyse Modules/EnergyBroker` → 0 errors. PHPMD/PHPInsights/Pest verified,
no regressions. Pest has a pre-existing unrelated test-DB blocker (documented).
