---
id: phpstan-Platform-fix
slug: phpstan-Platform
scope: [module:Platform, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema

PHPStan errors in `Modules/Platform`: 84 errors across
`PlatformSettingResource.php` (+ its `EditPlatformSetting` page), four demo
seeders (`BoutiqueIdraulicaDemoSeeder`, `IdroclimaRecaldiniDemoSeeder`,
`ImpiantiElettricIDemoSeeder`, `SyntheticDataSeeder`), and
`tests/Feature/AuditLogTest.php`. Mostly `cast.string`/`cast.int`/`cast.double`
on `mixed` values (Filament form `$get()` closures, `DB::table()->value()`,
Faker `randomElement()`, stdClass query-builder rows, array-shaped `mixed`
lookups) plus Filament v4->v5 deprecations (`Placeholder`->`TextEntry`,
`actions()`->`recordActions()`, `Action::form()`->`->schema()`).

`app/Filament/Resources/AuditLogResource.php` and its sibling
`Tables/AuditLogsTable.php` were excluded — mid-refactor by another agent
(`table()` extraction into a dedicated `XotBaseResourceTable` subclass),
untouched here.

## Solution

1. `PlatformSettingResource.php` / `EditPlatformSetting.php`: added private
   static narrowing helpers (`toStringValue()`, `stringOrDefault()`) instead of
   bare casts on `mixed` values coming out of Filament `$get()` closures and
   `data_get()`; migrated `Placeholder::make()->content()` to
   `TextEntry::make()->state()`, `->actions()` to `->recordActions()`,
   `Action::form()` to `Action::schema()`.
2. Seeders: added per-class private static `toInt()`/`toFloat()`/
   `toStringValue()` helpers (guard with `is_int()`/`is_numeric()`/`is_string()`
   before ever casting) replacing bare `(int)`/`(float)`/`(string)` casts on
   `DB::table()->value()`, Faker `randomElement()`, stdClass row properties, and
   `array<string, mixed>` value lookups.
3. `tests/Feature/AuditLogTest.php`: root cause was
   `Modules\Customer\Models\Customer` using `HasXotFactory` with no explicit
   `newFactory()` override — Larastan's `Model::factory()` dynamic-return-type
   extension can't follow `GetFactoryAction`'s runtime resolution and falls back
   to `mixed`. Added `protected static function newFactory(): CustomerFactory`
   to `Modules/Customer/app/Models/Customer.php` (separate module, separate git
   repo — confirmed free of locks/claims before editing). This is independent of
   the `AuditLogResource` refactor.

## Verification

- `phpstan analyse Modules/Platform --memory-limit=-1 --no-progress`: **84 -> 0
  errors**.
- `./tools/phpmd.sh Modules/Platform text cleancode,codesize,design,naming,unusedcode`:
  no new violations introduced by this session's edits (checked touched files
  specifically); remaining findings are pre-existing (god-method seeders,
  StaticAccess-to-facades convention, class coupling) unrelated to this fix.
- `./vendor/bin/pest Modules/Platform/tests --no-coverage`: 31 passed / 14
  failed. All 14 failures confirmed pre-existing via `git stash` A/B (12 are
  missing-table `QueryException`s on the shared test DB, matching
  `project_test_db_missing_migrations_blocks_feature_tests.md`; 2 are
  `AuditLogTest.php` hitting a separate, newly-documented runtime gap —
  `Customer::factory()` itself is undefined because `HasXotFactory` never pulls
  in Laravel's real `HasFactory` trait, see second-brain
  `project_hasxotfactory_missing_static_factory_method.md`). See
  `Modules/Platform/docs/coverage.md` for full before/after.
- Git: committed in `Modules/Platform` (own repo) and separately in
  `Modules/Customer` (own repo) — see `docs/chat/2026-09-07-phpstan-platform-wave2-claim.md`
  for the sync report.

## Not fixed / explicitly out of scope

- `app/Filament/Resources/AuditLogResource.php` +
  `AuditLogResource/Tables/AuditLogsTable.php` — another agent's in-progress
  `table()` extraction, left untouched per instructions.
- The runtime `AuditLogTest.php` failures (Customer::factory() /
  BadMethodCallException) — fixing for real means adding Laravel's
  `HasFactory` trait to the shared `XotBaseModel`/`HasXotFactory`, a sitewide
  change across every module's model hierarchy; out of scope for a
  single-module PHPStan task.
