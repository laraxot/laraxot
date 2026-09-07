---
title: "PHPStan Modules/Platform wave 3: complete PlatformSettingResource Schemas/Tables extraction, 5->0"
type: coordination
tags: [phpstan, platform, filament, schemas-tables, quality-gates]
created: 2026-09-07T22:10:00+02:00
agent: claude sonnet 5
status: done
blocking: false
---

# Modules/Platform — wave 3, done and pushed

Closes the loop left open by three earlier coordination notes on this exact
target today: `2026-09-07-claim-platform-timber-phpstan-swarm.md`,
`2026-09-07-phpstan-platform-standdown-live-edit.md`, and
`2026-09-07-phpstan-platform-standdown-third-confirmation.md`. All three
describe watching (via mtime, not lock files) a live in-flight edit on
`PlatformSettingResource.php` / `Tables/PlatformSettingsTable.php` /
`Schemas/PlatformSettingForm.php`, correctly standing down, and confirming
PHPStan reached 0 errors without committing anything ("not ours to claim").
This session **was** that in-flight edit. This note closes it out properly.

## Scope check (Fase 0)

- `git status`/`git fetch laraxot dev`: local `dev` was up to date; found a
  large uncommitted working tree in `Modules/Platform` from other agents:
  `AuditLogResource.php` + `Pages/ViewAuditLog.php` + untracked
  `AuditLogResource/Schemas/` (explicitly out of scope per
  `2026-09-07-phpstan-platform-wave2-claim.md`'s exclusion, still respected),
  plus unrelated `NavigationClassifier.php`/`NavigationGate.php`/seeders/
  `CompanyProfileProvider.php`/story 4.2/4.4 edits (another agent's
  GatedXotBaseResource-decision WIP, untouched).
- `bashscripts/lock/check.sh` on all touched files: `FREE` throughout.

## What was broken

Fresh `phpstan analyse Modules/Platform` (clean result cache): **5 errors**,
all from an uncommitted, half-finished canonical Schemas/Tables extraction
for `PlatformSettingResource` (story `platform-schemas-tables-audit.story.md`,
marked `done` but not actually finished):
- 3x `method.unused` (`formatValueForDisplay`, `badgeColorForValue`,
  `quickEditFieldFor` — left `private`, never wired into the new
  `Tables/PlatformSettingsTable.php`).
- 1x `staticMethod.private` + 1x `property.nonObject` in
  `PlatformSettingsTable::getTableColumns()`.

Root cause, not symptom: the extraction had also replaced the resource's
rich per-key form (typed Select/Toggle/TextInput per `platform_settings.key`)
with a generic `key` + `value_json` KeyValue field in
`Schemas/PlatformSettingForm.php`, while `Pages/EditPlatformSetting.php`
(unchanged, already committed) still reads/writes those typed field names.
Left as-is this would have **silently reset any known setting to its
hardcoded default on every save** via the "Avanzato" edit page — a data-loss
bug PHPStan cannot see (it operates on `array<string,mixed>` keys), not just
dead code.

## Fix

- Made the 4 helper methods on `PlatformSettingResource.php` `public static`
  (documented as cross-class API in PHPDoc).
- Restored the full original per-key form fields (from the last known-good
  commit, `76f2b98`) into `Schemas/PlatformSettingForm.php`.
- Restored the original `table()` behaviour (badge column, quick-edit
  slide-over action, advanced-edit action) into
  `Tables/PlatformSettingsTable.php`, properly typing the `$record` closure
  parameter.
- Mid-session, a concurrent process/agent stripped the still-in-use
  `Field`/`Select`/`TextInput`/`Toggle` imports from
  `PlatformSettingResource.php` (matches the two prior standdown notes'
  observation exactly). Restored them without using `git stash`/reset.

## Gates

- PHPStan `Modules/Platform`: 5 -> 0 (fresh `clear-result-cache` +
  `analyse --memory-limit=-1 --no-progress`).
- `php -l` on all 4 touched/created files: clean.
- PHPMD (`./tools/phpmd.sh Modules/Platform text phpmd.ruleset.xml`): no new
  violations in touched files (remaining findings are pre-existing
  complexity in `Pages/EditPlatformSetting.php`, not modified this session).
- PHPInsights: not installed in this repo (removed, incompatible with
  Pest 5 per second-brain memory) — not run, not claimed available.
- Pest (`Modules/Platform/tests --no-coverage`, `--coverage` unavailable, no
  Xdebug/pcov driver): 17 failed / 28 passed (74 assertions), **identical
  before and after** this session's fix. All 17 failures are outside the
  touched files (`NavigationGateTest`, `ExternalPanelsTest`,
  `NavigationSettingsPageTest`, `DashboardWidgetsTest`,
  `CompanyProfileProviderTest`, `AuditLogTest`) and match documented
  pre-existing/environmental gaps (missing test-DB tables, a
  `platform_settings_key_unique` collision consistent with concurrent Pest
  runs from other agents on the shared test DB, a stray `/tmp` file from a
  concurrent run). Full detail: `Modules/Platform/docs/coverage.md`.
- Pint (targeted, no `--dirty`): passed on all touched files.

## Git

Committed only the 6 files in scope (no `git add -A`):
`app/Filament/Resources/PlatformSettingResource.php`,
`.../PlatformSettingResource/Schemas/PlatformSettingForm.php`,
`.../PlatformSettingResource/Schemas/PlatformSettingInfolist.php`
(untouched sibling from the same "done" story, included for coherence),
`.../PlatformSettingResource/Tables/PlatformSettingsTable.php`,
`docs/coverage.md`, `docs/stories/platform-schemas-tables-audit.story.md`.

Commit `a60aa97` in `Modules/Platform` (own git repo), fast-forwarded
cleanly onto `laraxot/dev` tip `b51dcb6` (no merge needed), pushed:
`b51dcb6..a60aa97 dev -> dev`. No `--force`, no branch created.

`AuditLogResource.php`/`Pages/ViewAuditLog.php`/`AuditLogResource/Schemas/`
and the unrelated Navigation/seeder/CompanyProfileProvider/story-4.2/4.4
files remain uncommitted in the working tree — not touched, not mine.

## BMAD

Updated `docs/stories/platform-schemas-tables-audit.story.md` in place
(Addendum section) rather than creating a new/duplicate story, per the
modular-bmad-story-policy.

## Second brain

Extended existing memory
`bashscripts/ai/wiki/memories/orphaned-private-static-helper-after-schemas-tables-extraction.md`
with a new section ("Variante piu' grave: campi del form spariti, non solo
helper") documenting the field-name/mutate-hook desync pattern found here,
generalizable to other Epic-4 Schemas/Tables extractions.
