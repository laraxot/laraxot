---
name: bom-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Bom — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Bom — quality gate status (2026-09-07)

## PHPStan

- Before: 3 errors — Filament v4->v5 `Table::actions()`/`bulkActions()` deprecated,
  replaced by `recordActions()`/`toolbarActions()`.
- Fix: mechanical rename in `BomResource.php`, `BomItemsRelationManager.php`.
- After: **0 errors** (`phpstan analyse Modules/Bom`, clean cache).

## PHPMD

No new findings in touched files (pre-existing `ElseExpression` note in
`ViewBom.php`, unrelated to this fix).

## Pest

Not run for this module in this pass (mechanical, zero-behavior-change rename;
verified via PHPStan + `php -l` only). No functional change to test coverage.

## Update 2026-09-07 — story 2.8 typeCoverage.constantTypeCoverage

Project-wide `typeCoverage.constantTypeCoverage` (tomasvotruba/type-coverage,
threshold 99%, computed only on a full `phpstan analyse Modules` run — a
scoped `Modules/Bom` run never shows this rule, see
`TomasVotruba\TypeCoverage\Configuration\ScopeConfigurationResolver::areFullPathsAnalysed`).

- Before (fresh full-tree scan today): 300 total constants, 227 typed, 75.6%.
  4 occurrences attributed to Bom:
  - `app/Filament/Resources/BomResource.php:36` `ACCOUNT_FEATURE_ADMIN`
  - `app/Filament/Resources/BomResource.php:39` `ACCOUNT_FEATURE_OPERATOR`
  - `app/Filament/Resources/BomResource.php:42` `ACCOUNT_FEATURE_OPERATOR_DEFAULT`
  - `app/Filament/Resources/BomResource/Pages/ViewBom.php:23` `MAX_TREE_DEPTH`
- Fix: added native PHP 8.3+ const type declarations (no `@var` PHPDoc —
  matches the already-fixed sibling pattern in
  `Production/app/Filament/Resources/ProductionOrderResource.php` and
  `Customer/app/Filament/Resources/ResellerResource.php`):
  - `protected const string ACCOUNT_FEATURE_ADMIN = 'admin.bom';`
  - `protected const ?string ACCOUNT_FEATURE_OPERATOR = null;`
  - `protected const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;`
  - `private const int MAX_TREE_DEPTH = 8;`
  Note: `ViewBom.php` had a stale uncommitted WIP diff from a prior session
  that had added `/** @var int */` above `MAX_TREE_DEPTH` — the wrong pattern
  documented in `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`
  (PHPDoc alone does not move the metric, only a native type does). Removed
  and replaced with the native `const int` declaration.
- After: `phpstan analyse Modules/Bom` clean-cache run → 0 errors. Bom's own
  4 constants now moved out of the project-wide "missing type" pool (does not
  by itself flip the global rule to 0 — other modules still have gaps, project
  total was 73/300 missing at start of this pass).
- PHPMD (`./tools/phpmd.sh Modules/Bom text Modules/Bom/phpmd.ruleset.xml`):
  8 pre-existing violations, none in the touched lines/files' changed code
  (`CouplingBetweenObjects` in `BomResource.php:27`, `ElseExpression` in
  `ViewBom.php:154`, `UnusedFormalParameter` x6 in `BomItemPolicy.php`/`BomPolicy.php`).
  Not touched — out of scope for this task.
- Pest (`./vendor/bin/pest Modules/Bom/tests --no-coverage`): 2 passed, 4
  assertions, no regression.
- phpinsights: not run — tool removed from this repo (incompatible with
  Pest 5, confirmed by repo hook this session), not available.

## Update 2026-09-07 (later) — story 4.14.11 canonical-structure completion + regression fix

Found the module mid-refactor: an uncommitted Epic4 filament-canonical-structure
WIP (`Schemas/BomForm.php`, `Schemas/BomInfolist.php`, `Tables/BomsTable.php`,
slimmed `BomResource.php`/`ViewBom.php`, story `4.14.11.bom-canonical.story.md`)
had been left mid-edit (files untouched for 40+ min, lock free, no active claim
in `docs/chat/`). Completed it as part of closing PHPStan on this module rather
than leaving it half-done (2 real errors), because the two current errors were
direct fallout of the incomplete extraction:

- Before (fresh scan, WIP as found): **2 errors**.
  - `ViewBom::renderBomTree() is unused` (`method.unused`) — the extraction had
    removed `ViewBom::getInfolistSchema()` (moved to `BomInfolist`) but left the
    whole private tree-rendering method chain (`renderBomTree`,
    `renderBomItemsTree`, `getActiveBomForProduct`, `formatQuantity`) orphaned
    in `ViewBom.php`. This was a **silent functional regression**: the new
    `BomInfolist::getInfolistSchema()` only had flat fields (id/product/name/
    is_active/updated_at) — the entire recursive BOM-tree visualization feature
    (with circular-reference guard and max-depth guard) was dropped, not just
    "unused code".
  - `BomForm::getFormSchema() should return array<string, Component> but
    returns array<int, Section>` (`return.type`) — the extracted form schema
    returned a plain indexed array instead of a keyed one, unlike the sibling
    `BomInfolist`/`BomsTable` extractions in the same WIP.
  - Also found (via `git diff`, not itself a PHPStan error but a real
    regression): `BomResource.php`'s 3 account-feature constants had been
    reverted from the native-typed declarations fixed in the story-2.8 pass
    (commit `ef168ad`) back to the `/** @var T */`-only anti-pattern flagged in
    `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`.
    Also found the story-2.8 section of *this same file* (the one above) had
    been silently deleted by the WIP's stale base — restored here.
- Fix:
  - Moved the full tree-rendering logic (all 4 private methods + the
    `MAX_TREE_DEPTH` constant) into `Schemas/BomInfolist.php`, merged with the
    newer flat fields into a `'summary'` + `'tree'` two-section schema
    (`array<string, Component>`), replacing `$this->getRecord()` (Page-only
    API) with Filament's closure-injected `$record` parameter (matches the
    `Comment`/`Quotation` sibling Infolist convention) and
    `$bom->loadMissing('product', 'items.componentProduct')` for eager loading.
  - `ViewBom.php` slimmed to just `getMaxContentWidth()` + `getHeaderActions()`
    (dead code removed after the logic was relocated, not simply deleted).
  - `BomForm.php`: keyed the returned `Section` as `'main' => ...`.
  - `BomResource.php`: restored native const types
    (`protected const string ACCOUNT_FEATURE_ADMIN = 'admin.bom';` etc.) and
    dropped now-unused imports (`Select`/`TextInput`/`Toggle`/`Section`/
    `Component`/`Tables`/`Table`/`DeleteBulkAction`/`EditAction`/`ViewAction`)
    left over from the pre-extraction inline `getFormSchema()`/`table()`.
- After: `phpstan analyse Modules/Bom` (clean result-cache) → **0 errors**.
- Pint: `--test` scoped to the 4 touched app files → passed (repo-wide
  `--dirty` was not used — it reformats unrelated in-flight files from other
  concurrent agents/modules, see `feedback_pint_dirty_scope_multi_agent`).
- PHPMD (`./tools/phpmd.sh Modules/Bom text phpmd.xml`): same 10 pre-existing
  violations as before (`Policies/*`, `Providers/*`), none in touched files.
- PHPInsights (`./tools/phpinsights.sh analyse Modules/Bom -c <tmp config
  removing ForbiddenSecurityIssues, see phpinsights_composer_lock_scoped_path
  memory> --summary`): Code 94.8, Complexity 98.6, Architecture 88.2,
  Style 89.2 — all above the 70 gate.
- Pest: **2 → 6 tests passed (4 → 12 assertions)**. Added
  `tests/Unit/BomInfolistTreeRenderTest.php` covering the recovered tree logic
  directly via reflection on `BomInfolist::renderBomTree()` (empty-state
  message, nested quantity computation across 2 BOM levels, circular-reference
  guard, and the `array<string, Component>` schema-key contract for both
  `BomInfolist` and `BomForm`) — this is new coverage for logic that would
  otherwise have shipped silently removed.
- `php -l` clean on all 5 touched files.
