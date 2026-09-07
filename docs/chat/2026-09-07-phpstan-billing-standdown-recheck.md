---
title: "PHPStan Billing — standdown re-check #4, still 0 errors, WIP idle but not clean"
type: coordination
tags: [phpstan, billing, standdown, epic4, quality-gates]
created: 2026-09-07T21:54:00Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/Billing
blocking: false
see_also:
  - 2026-09-07-phpstan-billing-standdown.md
  - 2026-09-07-claim-billing-phpstan-swarm.md
  - billing-phpstan-regression-restore.md
  - filament-canonical-structure-billing-4.1.md
---

# Modules/Billing — PHPStan zero campaign, 4th independent standdown

Assigned: bring `./vendor/bin/phpstan analyse Modules/Billing --no-progress
--memory-limit=-1` to zero following `bashscripts/docs/prompts/03-quality-gates.md`
Fase 0-7. Dispatch pointed at `2026-09-07-filament-canonical-structure-billing-4.1.md`
("13 errori residui da fixare, 4.1.1") as the presumed starting point.

## Recon

- `git -C laravel/Modules/Billing status`: up to date with `laraxot/dev`
  (`git fetch laraxot dev` clean), but same large uncommitted WIP already described
  in the 3 prior Billing notes today (`2026-09-07-phpstan-billing-standdown.md`,
  `2026-09-07-claim-billing-phpstan-swarm.md`, `billing-phpstan-regression-restore.md`):
  Datas/Queries const-doc changes (story 5.71, `in_progress`), DeliveryNoteResource/
  SupplierInvoiceResource Schemas+Tables extraction (story 4.1, structure done),
  docs churn (4.3/4.5/README/module.md), plus a small `lang/it/delivery_note.php`
  tweak and `DeliveryNoteResource.php` slimming not present in the earlier notes.
- `bash bashscripts/lock/check.sh laravel/Modules/Billing` → `FREE`.
- Newest file mtime (`lang/it/delivery_note.php`) is **~1h47m** before this check —
  i.e. the WIP looks paused/abandoned, not a live race like the earlier sessions
  today (which saw edits ~30-75s old).
- Story 4.1.1 (`docs/stories/4.1.1.billing-phpstan-fixes.story.md`) is already
  `status: done`, matches the already-pushed commits `90af443` / `4efe093`.
  Story 5.71 (`docs/stories/5.71.phpstan-typecoverage-constants.story.md`) is
  `status: in_progress`, scope = 5 files (`ReconcileSupplierInvoiceAction.php`,
  `AgingBuckets.php`, `PreInvoiceLine.php`, `ReconciliationDiscrepancy.php`,
  `GetJobMarginsQuery.php`).

## Verification

`phpstan analyse Modules/Billing --no-progress --memory-limit=-1` on the current
tree (including all uncommitted WIP above) → **`[OK] No errors`**. 4th independent
confirmation today of 0 real errors on this module.

Checked the `GatedXotBaseResource`/`CustomerPanelProvider` NavigationGate risk
flagged in `2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md`:
`grep -rl GatedXotBaseResource app/` → no hits left in Billing. `InvoiceResource.php`
(already committed at HEAD, story 4.1.1) replaced it with an explicit private
`isAvailableInCurrentPanel()` guard used in `shouldRegisterNavigation()`/
`canViewAny()`/`canCreate()`/`canEdit()`/etc. — the risk was already mitigated by
the earlier fix, nothing to do here.

Spot-checked the uncommitted Schemas/Tables WIP (`DeliveryNoteResource.php` +
its new `Schemas/`, `Tables/`, existing `RelationManagers/LinesRelationManager.php`)
against `Modules/Xot/app/Filament/Resources/XotBaseResource.php`'s default
`getPages()`/`getRelations()` convention (class-name + folder glob based): the
stripped-down `DeliveryNoteResource.php` (no more `getPages()`/`getRelations()`/
`getFormSchema()`/`table()`) resolves correctly through those defaults — Pages
and RelationManager classes exist with the exact expected names. `php -l` clean
on every touched/untracked `.php` file. Not broken, just not committed and not
yet `pint`-formatted (one stray whitespace-only line).

## New finding (not fixed, out of this task's scope)

`app/Datas/PreInvoiceLine.php`'s uncommitted diff (story 5.71) has a **duplicated
`/** @var string */` docblock** above each of its 4 constants (copy-paste bug,
visible in `git diff`). More importantly, per `bashscripts/docs/prompts/03-quality-gates.md`
§"About typeCoverage Warnings", `const STATUS = 'active'` (inferred type) already
counts as *typed* for `typeCoverage.constantTypeCoverage` — the `/** @var */`
annotations story 5.71 is adding don't change that metric at all, and the metric
itself is currently non-gating anyway (Larastan/Pest extensions are commented out
in `phpstan.neon`, per `project_phpstan_neon_larastan_disabled_2026_09_07`
second-brain entry, owner-confirmed keep-as-is). Cosmetic, not blocking, not
touched — flagged here for whoever owns/resumes story 5.71.

## Decision: standdown (again)

Same stop condition as the 3 prior Billing sessions today: 0 real PHPStan errors
already, and the module is mid-refactor under WIP that isn't mine to commit
(not authored by this session, spans doc/story files I'd have to git-add blindly).
The WIP is idle (~1h47m) but internally coherent for the parts checked (Schemas/
Tables extraction) — closing it out (running phpmd/phpinsights/pest, committing,
pushing) would mean adopting someone else's multi-file structural + docs change
under this session's authorship, which is bigger than "fix real PHPStan errors"
and risks colliding with whichever agent left it mid-flight. Left entirely as-is.

**No files touched, no commit, no push.**

## Report

| Modulo | Errori iniziali | Errori finali | File toccati | Coverage | `mixed` residui | Story | Commit |
|---|---|---|---|---|---|---|---|
| Billing | 0 (verified fresh, WIP present) | 0 | 0 | non misurata (nessuna modifica) | nessuno introdotto | 4.1.1 done (pre-existing) / 5.71 in_progress (not owned) | nessuno |
