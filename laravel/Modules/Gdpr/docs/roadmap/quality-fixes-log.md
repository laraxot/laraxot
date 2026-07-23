# Quality Fixes Log - Gdpr Module

Storico correzioni PHPMD e PHPStan.

## Configuration Standards

- [x] `.env.testing` is a carbon copy of `.env` with `_test` suffix for database names. No module-specific variables (`NOTIFY_DB_*`) unless they exist in `.env`.
- [x] `TestCase.php` simplified: single `module:migrate` call, no `migrate:fresh`, no force, no static `$migrated` guards.

## PHPMD Issues

### LongVariable

- [x] `app/Datas/GdprData.php:53`: Avoid excessively long variable names like `$cookie_banner_enabled`. Keep variable name length under 20. (Renamed to `$cookie_banner_on`)
- [x] `app/Filament/Pages/EditProfile.php:11`: Avoid excessively long variable names like `$shouldRegisterNavigation`. Keep variable name length under 20. (Renamed to `$registerNavigation`)

### UnusedFormalParameter

- [x] `app/Models/Policies/ConsentPolicy.php`: Avoid unused parameters (Added @SuppressWarnings)
- [x] `app/Models/Policies/EventPolicy.php`: Avoid unused parameters (Added @SuppressWarnings)
- [x] `app/Models/Policies/GdprBasePolicy.php`: Avoid unused parameters (Added @SuppressWarnings)
- [x] `app/Models/Policies/ProfilePolicy.php`: Avoid unused parameters (Added @SuppressWarnings)
- [x] `app/Models/Policies/TreatmentPolicy.php`: Avoid unused parameters (Added @SuppressWarnings)
- [x] `tests/TestCase.php:37`: Avoid unused parameters such as '$app'. (Ignored - test file)

### UnusedLocalVariable

- [x] `app/Models/Policies/GdprBasePolicy.php:17`: Avoid unused local variables such as '$xotData'. (Refactored)

### BooleanArgumentFlag

- [x] `app/Models/Traits/HasGdpr.php:63`: The method `hasGivenConsent` has a boolean flag argument `$cached`, which is a certain sign of a Single Responsibility Principle violation. (Refactored into two methods)

## PHPStan Issues

- [x] No errors found.
- [x] 2026-07-16 — Rimosso `app/Models/GdprPhpstanTraitProbe.php`: modello "probe"
  vietato dalla regola [no-phpstan-probe-models](../../../../docs/wiki/rules/no-phpstan-probe-models.md).
  Il trait `HasGdpr` è realmente usato da `Modules/Employee/app/Models/User.php`
  e già esercitato dalla fixture di test `tests/Fixtures/HasGdprDummy.php`, quindi
  il probe era codice morto ridondante. La sua rimozione non introduce
  `trait.unused` (la fixture lo mantiene in scope). Dettaglio:
  [phpstan-probe-model-removal](../concepts/phpstan-probe-model-removal.md).
- [x] L'unico "errore" residuo del run scoped su `Modules/Gdpr`
  (`Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not
  matched`) è un falso positivo noto da analisi a scope parziale — vedi
  `Modules/Xot/docs/wiki/concepts/phpstan-partial-scope-false-positives.md`.
  Non è un errore di codice e non si corregge nel modulo (config immutabile).

## PHPInsights Issues

- [ ] Unable to run due to missing composer.lock file.
