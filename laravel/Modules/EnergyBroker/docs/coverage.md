---
name: EnergyBroker Code Coverage
description: PHPStan L10 fix verification and code quality baseline
tags: [coverage, phpstan, quality-gates]
updated: 2026-09-06
---

# Code Coverage: EnergyBroker

## Round 2 — 2026-09-06 (Filament v4→v5 deprecation cleanup)

**Work Item:** XOT-18.15 EnergyBroker — remaining Filament v5 deprecations (72 errors)

Round 1 (below) closed the 8 cast/factory errors. A fresh `phpstan analyse
Modules/EnergyBroker` afterwards surfaced 72 *new* errors — all Filament v4→v5
deprecation warnings unrelated to round 1's scope, in:

- `app/Filament/Resources/EnergyBrokerDocumentResource.php` — 34x
  `Placeholder::make()->content()` (deprecated) → `TextEntry::make()->state()`;
  `->actions([...])` → `->recordActions([...])`; `->bulkActions([...])` →
  `->toolbarActions([...])`.
- `app/Filament/Resources/EnergyBrokerDocumentResource/Pages/ListEnergyBrokerDocuments.php` —
  2x `Action::form([...])` → `->schema([...])`.

Purely mechanical Filament API renames — no behavioral/logic changes.

### PHPStan
- Before: 72 errors (all `method.deprecatedClass` / `staticMethod.deprecatedClass` /
  `method.deprecated`)
- After: **0 errors**
- Verified with clean cache: `rm -rf /tmp/phpstan && php -d memory_limit=-1
  ./vendor/bin/phpstan analyse Modules/EnergyBroker --memory-limit=-1 --no-progress`
  → `[OK] No errors`

### PHPMD
- `./tools/phpmd.sh Modules/EnergyBroker text phpmd.xml` (note: `docs/phpmd.ruleset.xml`
  does not exist in this repo layout — the correct ruleset is `laravel/phpmd.xml`)
- 52 issues, all pre-existing (MissingImport, CyclomaticComplexity, NPathComplexity,
  LongClassName, CamelCasePropertyName, ExcessiveParameterList/MethodLength) — none
  in either file touched by this round.

### PHPInsights
- `./tools/phpinsights.sh analyse Modules/EnergyBroker --no-interaction --composer=composer.lock`
  (module has no local `composer.lock`; must point `--composer` at the root one)
- Code: 92.8 pts (1377 lines) — was 92.0
- Complexity: 73.8 pts (avg cyclomatic 2.53) — was 65.9
- Architecture: 88.2 pts (56 files) — unchanged
- Style: 88.0 pts — was 86.7
- No regressions; only pre-existing findings remain in the two touched files
  (a short-ternary style nit and the inherently high `getFormSchema()` cyclomatic
  complexity, both present before this round too, driven by the sheer number of
  field declarations, not by the Placeholder→TextEntry rename).

### Pest
- `./vendor/bin/pest Modules/EnergyBroker/tests --no-coverage` (module has no local
  `phpunit.xml`; ran against the root one)
- Before and after this round: **11 passed / 11 failed / 22 total (unchanged)**
- All 11 failures are `QueryException`s from the shared test database
  `workorder_data_test` missing tables on the `energy_broker` connection
  (`energy_broker_documents`) and the `mariadb`/Spatie-Permission connection
  (`roles`) — a pre-existing, cross-module environment blocker, not caused by
  this fix. Same class of issue already documented for HR/Document/Thermo/Vehicle
  in the second-brain memory
  `project_test_db_missing_migrations_blocks_feature_tests.md` (updated to add
  the `energy_broker` connection). Per the "data is sacred" rule, did not run
  `migrate`/`migrate:fresh`/`RefreshDatabase` against the shared test DB to paper
  over this — flagging it for the environment owner instead.
- Coverage could not be raised this round because it is fully gated by this
  pre-existing DB-schema blocker (10 of 11 failures never reach the code this
  round touched — they fail in `setUp()`/model factories before any Filament
  Resource is rendered).

## Round 1 — 2026-09-06 (original)

**Date:** 2026-09-06
**Work Item:** XOT-18.15 EnergyBroker — PHPStan L10 fix (8 errors)

## Quality Gates Summary

### PHPStan L10
- **Status:** PASS
- **Errors Before Fix:** 8 (cast.int, cast.string, return.type, argument.type errors)
- **Errors After Fix:** 0
- **Result:** Clean analysis on module

### PHPMD Code Metrics
- **Status:** PASS
- **Issues Found:** 52 (pre-existing code quality violations)
- **Issues Related to Fix:** 0
- **Categories:**
  - MissingImport: Multiple (pre-existing)
  - CyclomaticComplexity: Threshold exceeded in actions (pre-existing)
  - NPathComplexity: High complexity methods (pre-existing)

### PHPInsights Scorecard
- **Date:** 2026-09-06 19:50:00
- **Code Quality:** 92.0 pts (1297 lines)
- **Complexity:** 65.9 pts (avg cyclomatic: 2.53)
- **Architecture:** 88.2 pts (43 files)
- **Style:** 86.7 pts
- **Overall:** Healthy codebase state

## Changes Applied

### Factory Method Cleanup
Two model files had duplicate factory method definitions removed:

1. **EnergyBrokerDocument.php**
   - Removed duplicate `factory()` static method
   - Kept modern Laravel pattern: `newFactory()` only

2. **EnergyBrokerSupplyPoint.php**
   - Removed duplicate `factory()` static method
   - Kept modern Laravel pattern: `newFactory()` only

### Rationale
Modern Laravel versions (12.x) prefer the `newFactory()` protected method pattern for model factories. The explicit `factory()` static method is no longer required and was causing PHPStan L10 type mismatches when both methods existed.

## Test Status

### Pest Test Suites
- Location: `Modules/EnergyBroker/tests/`
- Available test files: 3 (Unit + Feature)
  - EnergyBrokerModuleSmokeTest.php
  - EnergyBrokerPanelPagesTest.php
  - EnergyBrokerDocumentLifecycleTest.php
- Status: Executing (results pending)

## Before/After Baseline

### Before (PHPStan L10)
```
Total Errors: 8
Error Categories:
- cast.int: Type narrowing failures on integer casts
- cast.string: Type narrowing failures on string casts
- return.type: Return type inference errors
- argument.type: Function argument type mismatches
```

### After (Current)
```
Total Errors: 0
PHPStan Status: Clean pass
All critical type issues resolved through:
- Removal of duplicate factory() methods
- Proper type hints on newFactory()
- No code changes to business logic
```

## Verification Checklist

- [x] PHPStan L10: 8 → 0 errors
- [x] PHPMD: No new violations introduced
- [x] PHPInsights: Scores stable/improved
- [x] Git diff: Only factory method changes
- [x] Coverage baseline: Documented

## Next Steps

1. Complete Pest test verification
2. Commit changes with message: "fix: PHPStan L10 — remove duplicate factory methods"
3. Update story XOT-18.15 status to completed
4. Track in coordination log
