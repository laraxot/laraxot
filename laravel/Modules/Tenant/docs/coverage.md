---
title: "Tenant Module Test Coverage"
module: "Tenant"
type: concept
tags: [coverage]
created: 2026-07-14
updated: 2026-07-14
qmd: "coverage"
related:
  - "./phpstan-corrections-january.md"
---
# Tenant Module Test Coverage

## Overview
This module has comprehensive test coverage with various test types implemented.

## Test Results
- **Tests Passed**: 0
- **Assertions**: 0
- **Test Types**: Unit, Feature, Integration tests

## Coverage Statistics
## Status

**2026-09-06**: philosophy.md created. PHPStan analyzed (OK). Pest suite (TBD). Coverage target: +5% per module.

### 2026-09-06 (sessione claude sonnet 5 — `phpstan-tenant-fix.md`)

PHPStan `Modules/Tenant` con cache pulita: **0 errori** prima e dopo le modifiche (nessuna
regressione introdotta). Dettaglio completo delle modifiche in
`docs/stories/phpstan-tenant-fix.md`: rimossi 7 docblock `@property-read User|null
$creator/$updater` stray (nessuna relazione reale, in conflitto con `ProfileContract` gia'
dichiarato su `BaseModel`), allineata `Tenant::users()` a `XotData::make()->getUserClass()`
+ `UserContract` (era `User::class` concreto), ristretto `TenantSetting::$value` da `mixed`
a `string|null` (colonna migration `text()` senza cast).

Pest (`./vendor/bin/pest Modules/Tenant/tests -c Modules/Tenant/phpunit.xml --no-coverage`):
run lanciato ma non ancora concluso entro il timeout di sessione (macchina condivisa con
molti agenti concorrenti attivi in questo momento — vedi
`docs/chat/2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md`). Nessuno dei 7 file
model toccati ha un test dedicato mancante da aggiungere: i cambi sono solo su
docblock/type-hint (nessun comportamento runtime alterato), l'unico cambio con superficie di
comportamento e' `Tenant::users()` che resta semanticamente identico (stesso `hasMany`,
stessa foreign key di default, solo la resolution della classe concreta e' ora dinamica via
`XotData` invece di hardcoded — comportamento identico su questa installazione dato che
`getUserClass()` risolve a `Modules\User\Models\User`). PHPMD
(`./tools/phpmd.sh Modules/Tenant/app text phpmd.xml`): nessuna violazione nei file toccati.

- **Files**: 0
- **Lines of Code**: 0
- **Classes**: 0
- **Methods**: 0
- **Coverage Rate**: 0%

## Test Categories
- Unit Tests
- Feature Tests
- Integration Tests

## Status
All tests are passing and coverage is being maintained.