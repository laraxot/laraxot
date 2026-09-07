---
title: "PHPStan Bom — completed Epic4 canonical-structure WIP + recovered BOM-tree infolist regression (2 -> 0 errors)"
type: coordination
tags: [phpstan, bom, epic4, canonical-structure, story-4.14.11, done]
created: 2026-09-07T20:20:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
---

# Bom — PHPStan zero + completed in-flight canonical-structure extraction

Claimed via `03-quality-gates.md` swarm assignment. Lock (`bashscripts/lock/check.sh
laravel/Modules/Bom`) was FREE; no active claim found for `4.14.11`/`bom-canonical`
in this index. Note: this claim note is filed retroactively (after the fix/commit/push,
not before) — investigation moved directly into completing the found WIP; flagging the
process gap for future sessions rather than hiding it.

## What was found

The module's working tree had an **uncommitted, mid-edit** Epic4
filament-canonical-structure extraction (story `4.14.11.bom-canonical.story.md`,
`status: active`), files last touched 40+ minutes before this session started, no
lock held. `phpstan analyse Modules/Bom` on that WIP showed 2 real errors:

- `ViewBom::renderBomTree() is unused` (`method.unused`)
- `BomForm::getFormSchema()` return type mismatch (`array<int, Section>` vs
  required `array<string, Component>`)

Digging into the `method.unused` root cause found more than dead code: the
extraction's `getInfolistSchema()` → `Schemas/BomInfolist.php` move had **silently
dropped the entire recursive BOM-tree visualization feature** (circular-reference
guard, max-depth guard, quantity roll-up across nested BOMs) that the original
committed `ViewBom.php` implemented, replacing it with flat fields only. The
tree-rendering method chain (`renderBomTree`/`renderBomItemsTree`/
`getActiveBomForProduct`/`formatQuantity`) was left orphaned in `ViewBom.php`
instead of being moved.

Also found via `git diff` (not itself a PHPStan error): `BomResource.php`'s 3
account-feature constants had regressed from the native-typed declarations fixed
in story 2.8 (commit `ef168ad`) back to the `/** @var T */`-only anti-pattern
(`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`), and the
WIP's stale base had silently deleted the story-2.8 section of
`Modules/Bom/docs/coverage.md`.

## Fix

- Moved the tree-rendering logic (not deleted) into `Schemas/BomInfolist.php`,
  merged with the newer flat fields into `'summary'` + `'tree'` sections;
  replaced `$this->getRecord()` (Page-only API) with Filament's closure-injected
  `$record` parameter, matching the `Comment`/`Quotation` sibling Infolist
  convention.
- `BomForm.php`: keyed the returned `Section` as `'main'`.
- `BomResource.php`: restored native const types, dropped now-unused imports.
- `docs/coverage.md`: restored the deleted story-2.8 section, appended today's
  gate results.
- Added `tests/Unit/BomInfolistTreeRenderTest.php` (reflection-based, matching
  the `Activity` module's established convention for testing private Filament
  schema logic) covering: empty-state message, nested quantity roll-up across 2
  BOM levels, circular-reference guard, and the `array<string, Component>`
  schema-key contract for both `BomInfolist` and `BomForm`.

## Result

- PHPStan: 2 → **0 errors** (`phpstan analyse Modules/Bom`, clean result-cache,
  re-verified after every edit).
- Pint: `--test` scoped to touched files → passed. Did **not** run `--dirty`
  (repo-wide, would have reformatted unrelated in-flight files from other
  concurrent agents — see `feedback_pint_dirty_scope_multi_agent`).
- PHPMD: no new violations (10 pre-existing, all in `Policies/`/`Providers/`,
  untouched).
- PHPInsights (installed and working in this repo despite an in-session hook
  claiming otherwise — see `feedback_phpinsights_removed_use_exact_gate_commands`;
  scoped to a single module needs an ephemeral `-c /tmp/...` config removing
  `ForbiddenSecurityIssues`, see `phpinsights_composer_lock_scoped_path`):
  Code 94.8 / Complexity 98.6 / Architecture 88.2 / Style 89.2 — all above the
  70 gate.
- Pest: 2 → 6 tests passed, 4 → 12 assertions.
- Auto-generated `lang/it/bom_form.php` / `lang/it/bom_infolist.php` appeared
  during the Pest run (Xot/Lang `AutoLabelAction`, same pattern already
  committed for `Comment`/`comment_form.php`/`comment_infolist.php`) — committed
  as-is (raw placeholder text), matching repo convention.

## Git

Commit `aa8ad30` on `laravel/Modules/Bom` (independent per-module repo,
remote `laraxot`, `git@github.com:laraxot/module_bom_fila5.git`). Fetched +
compared against `laraxot/dev` before and after commit: no divergence (0 behind
both times), no merge needed. Pushed `ef168ad..aa8ad30 dev -> dev`.

## Known gap left open

The original story's plan mentioned a `TernaryFilter::make('is_active')` for
`Tables/BomsTable.php` that was never implemented in the actual WIP file. Not a
PHPStan error, not touched by the regression found here — left as a documented
gap for a future story rather than expanded scope.
