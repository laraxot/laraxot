---
name: platform-pest-coverage
description: "Pest run log for Modules/Platform, tracked across PHPStan-fix sessions"
metadata:
  node_type: doc
  type: coverage-log
---

# Modules/Platform — Pest coverage log

## 2026-09-07 — PHPStan wave 3: complete the Schemas/Tables extraction (PlatformSettingResource)

**Scope:** finishing an uncommitted, half-done canonical Schemas/Tables
extraction found already in the working tree for `PlatformSettingResource`
(see `docs/stories/platform-schemas-tables-audit.story.md`, status `done` but
incomplete). `AuditLogResource.php`/`Pages/ViewAuditLog.php` and unrelated
files (`NavigationClassifier.php`, `NavigationGate.php`, seeders,
`CompanyProfileProvider.php`) were left untouched — other agents' live WIP.

**PHPStan:** `Modules/Platform` 5 -> 0 errors (fresh `clear-result-cache` +
`analyse Modules/Platform --memory-limit=-1 --no-progress`):
- 3x `method.unused` on `PlatformSettingResource::formatValueForDisplay()` /
  `badgeColorForValue()` / `quickEditFieldFor()` — dead after the form/table
  extraction moved their only callers into
  `PlatformSettingResource\Tables\PlatformSettingsTable`, without exposing the
  methods (`private` -> `public static`) or wiring the table's badge
  column/quick-edit action to call them.
- 1x `staticMethod.private` + 1x `property.nonObject` in
  `PlatformSettingsTable::getTableColumns()` — same visibility gap plus an
  untyped `$record` closure parameter.

**Root cause, not symptom:** the extraction had also silently dropped the
resource's rich per-key form (Select/Toggle/TextInput fields for each known
`platform_settings.key`) in favour of a generic `key` + `value_json` KeyValue
field in `Schemas/PlatformSettingForm.php`, while
`Pages/EditPlatformSetting.php` (unchanged, already committed at `76f2b98`)
still reads/writes those typed field names in
`mutateFormDataBeforeFill()`/`mutateFormDataBeforeSave()`. Left as-is, saving
any known setting (e.g. `inventory.trace_mode`, `billing.einvoice.provider`)
through the "Avanzato" edit page would have silently reset it to the
hardcoded fallback default instead of persisting the admin's input — a data
corruption bug, not just dead code. Restored the full original field set
(from `git show 76f2b98:.../PlatformSettingResource.php`) into
`Schemas/PlatformSettingForm.php`, and the original `table()` behaviour
(badge column, quick-edit slide-over, advanced edit action) into
`Tables/PlatformSettingsTable.php`, making the four helper methods on the
Resource `public static` for cross-class use. Second-brain memory updated:
`bashscripts/ai/wiki/memories/orphaned-private-static-helper-after-schemas-tables-extraction.md`
("Variante piu' grave: campi del form spariti, non solo helper").

**Incident during the fix:** mid-session, a concurrent process/agent stripped
the (still in-use) `Field`/`Select`/`TextInput`/`Toggle` imports from
`PlatformSettingResource.php` (likely an automated unused-import pass racing
on the same file). Restored them; confirmed with `php -l` + a fresh PHPStan
run. No `git stash`/reset was used to recover — pattern noted in second-brain
as a hazard on this shared tree.

**Pest** (`./vendor/bin/pest Modules/Platform/tests --no-coverage`, no
module-local `phpunit.xml`, root config used; `--coverage` unavailable, no
Xdebug/pcov coverage driver enabled in this environment):
- Before (this session, on the pre-existing dirty tree, before my edits):
  17 failed / 28 passed (74 assertions).
- After (with the fixes above applied): **17 failed / 28 passed (74
  assertions)** — unchanged. All 17 failures are outside the files touched in
  this session (`NavigationGateTest`, `ExternalPanelsTest`,
  `NavigationSettingsPageTest`, `DashboardWidgetsTest`,
  `CompanyProfileProviderTest`, `AuditLogTest`) and match documented
  pre-existing/environmental gaps:
  - missing-table `QueryException`s (`roles`, `customers`) on the shared
    `workorder_data_test` DB — `project_test_db_missing_migrations_blocks_feature_tests.md`.
  - a `UniqueConstraintViolationException` on `platform_settings_key_unique`
    (`billing.einvoice.api_key`) — consistent with concurrent Pest runs from
    other agents hitting the same shared test DB right now, not a code
    regression from this session's diff.
  - `CompanyProfileProviderTest` asserting a `/tmp/logo*.bin` path is null —
    stray temp file from a concurrent test run polluting shared `/tmp`;
    `CompanyProfileProvider.php` was not touched by this session.
  No test in this run exercises `PlatformSettingResource` /
  `PlatformSettingsTable` / `PlatformSettingForm` directly (none exist yet);
  correctness was verified via PHPStan (0 errors), `php -l` on every touched
  file, PHPMD (no new violations), and manual parity check against the last
  known-good committed version (`76f2b98`).

**Coverage delta:** pass/fail counts unchanged (17/28); no regression, no
improvement measurable without a dedicated `PlatformSettingResource` test
(flagged in the story as a follow-up, out of scope for a PHPStan-zero pass).

## 2026-09-07 — PHPStan wave 2 (this session)

**Scope:** PHPStan/Filament-v5 fixes only (see
`docs/stories/01.Platform-phpstan-fix.story.md`), no test files were edited except
verifying `tests/Feature/AuditLogTest.php` against an independent factory fix in
`Modules/Customer`.

**PHPStan:** `Modules/Platform` 84 errors -> 0 errors (confirmed with
`rm -rf /tmp/phpstan && phpstan analyse Modules/Platform --memory-limit=-1
--no-progress`).

**Pest** (`./vendor/bin/pest Modules/Platform/tests --no-coverage`, no
module-local `phpunit.xml`, root config used):

- Before: not independently re-measured this session (no test files were touched
  by the PHPStan fixes; behavior of tested code paths is unchanged — Filament v5
  API renames and `mixed`-narrowing guards are structurally equivalent to the
  prior `(string)`/`(int)`/`(float)` casts for all valid runtime inputs).
- After: **31 passed, 14 failed** (77+ assertions). All 14 failures are
  **pre-existing and environmental**, confirmed via `git stash` A/B on the one
  file that could plausibly affect a failing test (`Modules/Customer/app/Models/Customer.php`):
  the exact same failures reproduce with that file stashed out.
  - 12 failures: `QueryException` — missing tables (`navigation_preferences`,
    `roles`) on the shared `workorder_data_test` DB. Matches the documented
    pattern in second-brain `project_test_db_missing_migrations_blocks_feature_tests.md`
    — not a module bug, owner must migrate the test DB once.
  - 2 failures (`tests/Feature/AuditLogTest.php`, both cases): a **different,
    newly-documented** pre-existing issue — `Modules\Customer\Models\Customer`
    (and every other model relying solely on `Modules\Xot\Models\Traits\HasXotFactory`)
    never gets Laravel's real `HasFactory` trait, so `Customer::factory()` throws
    `BadMethodCallException` at runtime regardless of the `newFactory()` override
    added in this session (that override only fixes Larastan's static analysis,
    per `project_larastan_factory_mixed_needs_newfactory_override.md`). See
    second-brain `project_hasxotfactory_missing_static_factory_method.md` for the
    full root cause and why it's out of scope for a single-module PHPStan task.

**Coverage delta:** not measurably changed by this session's edits (no
production logic branches were added/removed — only type-narrowing guards that
are behaviorally identical for well-formed inputs, plus a `newFactory()`
override that only affects PHPStan's view of the code, not runtime paths).
The two pre-existing `AuditLogTest` failures block that file's assertions from
ever exercising the redaction logic; fixing them requires the sitewide
`HasFactory` decision flagged above, not a Platform-local change.
