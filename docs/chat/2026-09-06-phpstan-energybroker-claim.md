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

Working now on the mechanical Placeholder→TextEntry / recordActions / toolbarActions /
Action::schema() rewrite. Will post final status (PHPStan/PHPMD/PHPInsights/Pest +
git sync) here on completion.
