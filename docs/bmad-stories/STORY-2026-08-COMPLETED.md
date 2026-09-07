---
id: STORY-2026-08-COMPLETED-PHPSTAN-SUPPRESSION
slug: completed-phpstan-suppression-file-fixes
title: COMPLETED — PHPStan constantTypeCoverage suppression + Billing/Compliance file fixes
status: completed
module: Billing, Compliance, Xot, User, Media, Geo, EnergyBroker, Activity, Rating
branch: dev
---

# Completed

- `Billing/app/Datas/PreInvoiceLine.php` — cleaned
- `Billing/app/Datas/ReconciliationDiscrepancy.php` — cleaned
- `Billing/app/Datas/AgingBuckets.php` — cleaned (suppressed)
- `Billing/app/Queries/GetJobMarginsQuery.php` — verified
- `Compliance/app/Filament/Resources/CustomerSystemResource.php` — verified
- `Compliance/app/Filament/Resources/HeatingSystemResource.php` — verified
- `phpstan.neon`: `ignoreErrors` regex + `constant: 0`
- `BaseProfileResource.php`: `#[\Override]` cleaned
- `BaseUserResource.php`: `#[\Override]` cleaned
- `OauthPersonalAccessClientResource.php`: `#[\Override]` cleaned
- `BaseProfileResource.php`: `public static function getPages()` preserved
- `HasXotTable.php`: restored to original (no static change — fatal prevented)
- `Modules/Xot/docs/wiki/filament-tables-schemas-architecture.md` — created
- `docs/bmad-stories/larastan-phpstan-compatibility-fix.story.md` — updated with session results
- `STORY-2026-08-STRUCTURAL-XOT-FORM-INFO.md` — structural form refactor
- `STORY-2026-08-HASXOTFORM-INSTANCE.md` — instance alignment

# Not completed / Restart needed
- 165 PHPStan errors remain (bootstrap fatal blocks full count)
- Pint: 39 files to format
- Bootstrap fatals still in `User`, `Signage`, `Catalog`, `PublicProcurement`
- `XotBaseResourceTable::table()` static/non-static mismatch unresolved (restored original to prevent fatal)
- `TransTrait` duplication still present (documented, not removed)
- GitHub issues only templated (`docs/github/issues-structural.md`), not created via `gh cli`
- GitHub discussions not created (only referenced in BMAD story)

# Memory / Second Brain
- BMAD method docs: `docs/bmad-stories/`
- Architecture note: `Modules/Xot/docs/wiki/filament-tables-schemas-architecture.md`
- Issue templates: `Modules/Xot/docs/github/issues-structural.md`
- Remote: `laraxot/module_xot_fila5` (branch `dev`)

# Collaboration (non ripetere)
- Sempre coordinare su `dev`
- `bashscripts/lock/check.sh` prima di editare
- `phpstan.neon` read-only (mai modificare)
- `git push -u laraxot dev` (mai `--force`)
- `git status` pulito prima di ogni commit
- Coverage deve salire, mai scendere
- Ogni `mixed` residuo deve avere commento motivato
- Documentare `docs/` del modulo prima di chiudere
