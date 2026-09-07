---
title: "Platform PHPStan fix — wave 2 claim"
type: coordination
tags: [phpstan, platform, multi-agent, claim]
created: 2026-09-07T00:00:00Z
agent: claude (sonnet 5)
status: in-progress
blocking: false
---

# Platform PHPStan fix — wave 2

Second attempt on `Modules/Platform` after the 2026-09-06 standdown. Live lock
check (`bashscripts/lock/check.sh`) at start shows all target files FREE.

**Explicitly out of scope — not touching, not committing:**
- `app/Filament/Resources/AuditLogResource.php` (modified, uncommitted — other
  agent's mid-flight `table()` -> `XotBaseResourceTable` extraction)
- `app/Filament/Resources/AuditLogResource/Tables/AuditLogsTable.php`
  (untracked — same refactor)

**In scope (verified via fresh `phpstan analyse Modules/Platform`, 84 errors
at start, matches the assigned list exactly):**
- `app/Filament/Resources/PlatformSettingResource.php`
- `app/Filament/Resources/PlatformSettingResource/Pages/EditPlatformSetting.php`
- `database/seeders/BoutiqueIdraulicaDemoSeeder.php`
- `database/seeders/IdroclimaRecaldiniDemoSeeder.php`
- `database/seeders/ImpiantiElettricIDemoSeeder.php`
- `database/seeders/SyntheticDataSeeder.php`
- `tests/Feature/AuditLogTest.php` (independent of the AuditLogResource
  refactor — factory/model issue, see below)

## Result: DONE

**PHPStan:** `Modules/Platform` 84 -> 0 errors, confirmed with a clean-cache
`phpstan analyse Modules/Platform --memory-limit=-1 --no-progress` run.

**Files fixed (all in scope, none touching AuditLogResource):**
- `app/Filament/Resources/PlatformSettingResource.php` — Placeholder->TextEntry,
  actions()->recordActions(), Action::form()->schema(), added
  `toStringValue()`/`stringOrDefault()` private static helpers to narrow
  Filament `$get()`/`data_get()` `mixed` values instead of bare casts.
- `app/Filament/Resources/PlatformSettingResource/Pages/EditPlatformSetting.php`
  — same narrowing pattern (`stringOrDefault()`) for form-data casts.
- `database/seeders/BoutiqueIdraulicaDemoSeeder.php`,
  `IdroclimaRecaldiniDemoSeeder.php`, `ImpiantiElettricIDemoSeeder.php`,
  `SyntheticDataSeeder.php` — added private static `toInt()`/`toFloat()`/
  `toStringValue()` helpers guarding `DB::table()->value()`, Faker
  `randomElement()`, stdClass row properties, and `array<string,mixed>`
  lookups before casting.
- `tests/Feature/AuditLogTest.php` — **not edited directly**; its
  `method.nonObject`/`property.nonObject`/`cast.string` errors came from
  `Modules\Customer\Models\Customer` using `HasXotFactory` with no explicit
  `newFactory()` override (known pattern, see second-brain
  `project_larastan_factory_mixed_needs_newfactory_override.md`). Confirmed
  independent of the `AuditLogResource` refactor. Fixed in
  `Modules/Customer/app/Models/Customer.php` (separate git repo, verified free
  of locks, committed+pushed separately: `5ab95a8`).

**Skipped, explicitly out of scope:**
`app/Filament/Resources/AuditLogResource.php` and
`AuditLogResource/Tables/AuditLogsTable.php` — left completely untouched
(confirmed via `git status` before/after: still shows as the other agent's
uncommitted modified/untracked files, not staged, not committed by me).

**Gates:**
- PHPMD (`./tools/phpmd.sh Modules/Platform text cleancode,codesize,design,naming,unusedcode`):
  no new violations in any file/method I touched (checked by grepping the
  output for my new helper methods and all touched filenames); remaining
  findings are pre-existing (god-method seeders, StaticAccess-to-facades
  convention, coupling) untouched by this session.
- Pest (`./vendor/bin/pest Modules/Platform/tests --no-coverage`, no
  module-local `phpunit.xml`): 31 passed / 14 failed. All 14 failures
  confirmed pre-existing via `git stash` A/B — 12 are missing-table
  `QueryException`s (documented pattern), 2 are `AuditLogTest.php` hitting a
  **separate, newly-documented** runtime gap: `HasXotFactory` never pulls in
  Laravel's real `HasFactory` trait, so `Customer::factory()` itself is
  undefined at runtime regardless of the `newFactory()` override (which only
  fixes PHPStan's static view). New second-brain memory:
  `project_hasxotfactory_missing_static_factory_method.md`. Full details in
  `Modules/Platform/docs/coverage.md`.

**BMAD:** updated `Modules/Platform/docs/stories/phpstan-Platform-fix.md` with
full problem/fix/verification detail (status now `Done`); left
`01.Platform-phpstan-fix.story.md` as-is (thin stub, not duplicated).

**Git sync:**
- `Modules/Platform`: commit `76f2b98`, pushed to `laraxot/dev` clean
  (`668c398..76f2b98`).
- `Modules/Customer`: commit `5ab95a8`, pushed to `laraxot/dev` clean
  (`15550ac..5ab95a8`).
- No `--force`, no branch created, stayed on `dev` in both repos.
