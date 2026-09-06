---
title: "EnergyBroker — PHPStan Filament v4→v5 deprecation fix (claim)"
created: 2026-09-06
agent: claude sonnet 5 (worker, scoped to Modules/EnergyBroker only)
---

# Claim: Modules/EnergyBroker

Prior work already committed and confirmed present in `laravel/Modules/EnergyBroker`
(remote `laraxot`, branch `dev`):

- `8ed1aff` — remove redundant `factory()` override (Xot `HasXotFactory` trait covers it)
- `d3684d5` — PHPStan L10, remove duplicate factory methods
- `d27a384` — EnergyBroker parallel fix, bmad story, docs/stories

Lock check before starting (both FREE, no `.lock` present):

```
bash bashscripts/lock/check.sh laravel/Modules/EnergyBroker/app/Filament/Resources/EnergyBrokerDocumentResource.php
bash bashscripts/lock/check.sh laravel/Modules/EnergyBroker/app/Filament/Resources/EnergyBrokerDocumentResource/Pages/ListEnergyBrokerDocuments.php
```

Current `phpstan analyse Modules/EnergyBroker` baseline (before this claim's work):
**72 errors**, entirely Filament v4→v5 deprecations in two files:

- `app/Filament/Resources/EnergyBrokerDocumentResource.php` — 34x
  `Placeholder::make()->content()` → must become `TextEntry::make()->state()`
  (`method.deprecatedClass` / `staticMethod.deprecatedClass`), plus
  `Table::actions()` → `recordActions()` and `Table::bulkActions()` → `toolbarActions()`.
- `app/Filament/Resources/EnergyBrokerDocumentResource/Pages/ListEnergyBrokerDocuments.php` — 2x
  `Action::form()` → `schema()` (lines 43, 267).

Note: `docs/stories/phpstan-fix-EnergyBroker-1788725043.story.md` and the earlier
`docs/coverage.md` (claiming "PHPStan 0 errors" from the factory-only fix) predate
this deprecation batch — that claim was accurate for its own scope (the 8
cast/factory errors) but the module has 72 unrelated deprecation errors surfaced
after a Filament v5 upgrade. Updating `Modules/Xot/docs/stories/18.15.energybroker-phpstan-l10-fix.story.md`
and `Modules/EnergyBroker/docs/coverage.md` as part of this work to reflect the
full picture.

## Outcome (2026-09-06, completed)

Fixed:
- `app/Filament/Resources/EnergyBrokerDocumentResource.php` — 34x
  `Placeholder::make()->content()` → `TextEntry::make()->state()`, import fixed;
  `->actions([...])` → `->recordActions([...])`; `->bulkActions([...])` →
  `->toolbarActions([...])`.
- `app/Filament/Resources/EnergyBrokerDocumentResource/Pages/ListEnergyBrokerDocuments.php` —
  2x `Action::form([...])` → `->schema([...])`.
- `./vendor/bin/pint` run scoped to both files (repo-wide `--dirty` from `laravel/`
  doesn't see this module — it's its own git repo — so scoped explicitly).

**PHPStan** (`phpstan analyse Modules/EnergyBroker`, clean-cache verified twice,
once before Pint and once after): **72 → 0 errors**.

**PHPMD** (`./tools/phpmd.sh Modules/EnergyBroker text phpmd.xml` — note:
`docs/phpmd.ruleset.xml` from the task template does not exist in this repo; the
real ruleset is `laravel/phpmd.xml`): 52 pre-existing issues, none in the two
touched files, no new violations.

**PHPInsights** (coordinator corrected mid-task: it IS installed, not removed;
ran `./tools/phpinsights.sh analyse Modules/EnergyBroker --no-interaction
--composer=composer.lock` — module has no local composer.lock, must point
`--composer` at the root one): Code 92.8 / Complexity 73.8 / Architecture 88.2 /
Style 88.0 — stable/improved vs the prior round's 92.0/65.9/88.2/86.7. Only
pre-existing findings remain in the touched files (short-ternary style nit,
inherent `getFormSchema()` complexity from field count).

**Pest** (`./vendor/bin/pest Modules/EnergyBroker/tests --no-coverage` — module has
no local `phpunit.xml`, used root one): 11 passed / 11 failed, **unchanged**
before/after. All failures are a pre-existing shared-test-DB (`workorder_data_test`)
missing-tables blocker (`energy_broker_documents`, `roles` — same class of issue
as HR/Document/Thermo/Vehicle, see second-brain memory
`project_test_db_missing_migrations_blocks_feature_tests.md`, updated to add the
`energy_broker` connection). Did not run `migrate`/`migrate:fresh`/`RefreshDatabase`
per the "data is sacred" rule — flagged for the environment owner instead.
Coverage could not be raised this round since it's fully gated by this
pre-existing blocker, unrelated to the Filament fix.

**Concurrency note:** mid-task, a concurrent process/agent committed
`935cbfe` (`fix: PHPStan EnergyBroker, bmad story, docs/stories`) directly in
this same working directory, containing the exact same one-line import fix
(`Placeholder` → `TextEntry`) I was also making — confirms this module's working
tree is shared across swarm sessions, not per-agent-isolated (same pattern
already noted for `lang/it/rating.php` in
`2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md`). No conflict, no
lost work — `git merge laraxot/dev` was a clean fast-forward-free
"Already up to date" (linear history), final state re-verified with a fresh
PHPStan run (0 errors) after the merge/Pint pass.

**Docs updated:** `Modules/EnergyBroker/docs/coverage.md` (before/after appended
as "Round 2"), `Modules/EnergyBroker/docs/stories/phpstan-EnergyBroker-fix.md`
(Pending → Done), `Modules/Xot/docs/stories/18.15.energybroker-phpstan-l10-fix.story.md`
(ready-for-dev → done).

**Git sync:** committed `94d767b` on `dev`, fetched/merged `laraxot/dev` (already
up to date, no conflicts), pushed clean: `935cbfe..94d767b  dev -> dev`. No force,
no branch created.
