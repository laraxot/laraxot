---
id: phpstan-MetalWork-fix
slug: phpstan-MetalWork
scope: [module:MetalWork, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Update 2026-09-07 — typeCoverage.constantTypeCoverage + @var regression fix

### Problema

`typeCoverage.constantTypeCoverage` is a repo-wide PHPStan metric (percentage of
class constants with a real PHP type declaration, not just a `@var` PHPDoc),
visible only on a full `phpstan analyse Modules` run, never on a module-scoped
one. 8 MetalWork files had constants with no type declaration. Separately, a
concurrent agent's earlier pass (commit `b28a1bd`) had already fixed a
regression where 3 of those constants (`ELIGIBLE_FAMILIES`, `COUNTED_ENTITIES`,
`SURFACE_ENTITIES`) had been given a wrong `@var list<string>` tag on what are
actually associative/nested arrays, producing new `classConstant.phpDocType`
errors. That PHPDoc-only fix did not yet add the real type declaration the
typeCoverage metric needs — see `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`.

### Solution

Added a real PHP 8.3 type declaration (`public|private const array|string|int|float NAME = ...`)
to every constant in:

- `app/Actions/EstimateFromBillOfMaterialsAction.php` — `HOLLOW_FAMILIES` (`array`, real list<string>)
- `app/Actions/MatchRawMaterialToSolidAction.php` — `SHEET_MAX_THICKNESS_MM` (`float`), `MAX_FIT_ERROR` (`float`), `ELIGIBLE_FAMILIES` (`array`, kept the already-correct `@var array<string, list<string>>`, and simplified a stray overly-specific literal-shape `@var` a formatting hook had produced back to that clean form)
- `app/Datas/StepSolid.php` — `PLATE_RATIO` (`float`), `BAR_RATIO` (`float`), `SQUARE_CUT_PLANE_LIMIT` (`int`)
- `app/Filament/Pages/MetalDrawingQuotationPage.php` — `ACCEPTED_EXTENSIONS` (`array`), `DRAWING_DISK` (`string`), `DRAWING_DIRECTORY` (`string`)
- `app/Support/MetalWorksCosting.php` — `CONTEXT` (`string`)
- `app/Support/StepFileParser.php` — `CHUNK_BYTES` (`int`), `MAX_HEADER_BYTES` (`int`), `COUNTED_ENTITIES` (`array`)
- `app/Support/StepSolidExtractor.php` — `CHUNK_BYTES` (`int`), `TOPOLOGY_ENTITIES` (`array`), `SURFACE_ENTITIES` (`array`)
- `app/Support/SupplierInvoiceParser.php` — `AMOUNTS_PATTERN` (`string`), `ITEMS_START_MARKER` (`string`), `CODE_FRAGMENT_PATTERN` (`string`), `FAMILY_RULES` (`array`)

All existing `@var` PHPDoc tags were already shape-correct (fixed by `b28a1bd`)
and were left in place for extra static precision; no `mixed`, no
`@phpstan-ignore`, no baseline entries.

### Result

- `phpstan analyse Modules/MetalWork`: 0 errors before and after (module was
  already clean from the previous pass; this pass only affected the
  repo-aggregate typeCoverage metric, invisible at module scope).
- `phpstan analyse Modules` (full tree, aggregate): 0 `classConstant.*` errors
  and 0 messages of any kind attributed to any MetalWork file — confirmed no
  MetalWork file appears in the full-tree JSON report. Repo-wide
  `typeCoverage.constantTypeCoverage` occurrences dropped from including these
  8 MetalWork files to not including any of them (117 occurrences remain
  repo-wide across other modules, out of scope for this story).
- PHPMD (`tools/phpmd.sh Modules/MetalWork/app text Modules/MetalWork/phpmd.ruleset.xml`):
  31 findings, identical set/nature to the previous pass's documented
  pre-existing debt (ShortVariable, ExcessiveClassComplexity,
  CyclomaticComplexity, ExcessiveParameterList, CouplingBetweenObjects,
  UnusedFormalParameter, LongClassName, LongVariable, ElseExpression) — none
  on the touched const declaration lines, none new. Left untouched per the
  "structural/risky, document instead of breaking" rule; low-risk mechanical
  ones (short loop-variable renames) were intentionally left too, since fixing
  them touches files outside this story's locked scope
  (`BuildDrawingBillOfMaterialsAction.php`, `EstimateMetalPartCostAction.php`,
  `SupplierInvoice.php`, `StepGeometry.php`, `MetalPartCosting.php`,
  `RawMaterialDimensionReader.php`) that other agents may be concurrently
  editing.
- PHPInsights (`vendor/bin/phpinsights analyse Modules/MetalWork/app --no-interaction`):
  ran cleanly this time (exit 0), no `ComposerNotFound` crash — Code 88.7 /
  Complexity 72.6 / Architecture 88.2 / Style 89.2, all above the repo's
  `min-*: 70`. (The previous session's documented `ComposerNotFound` bug on
  module-scoped runs did not reproduce here; not re-investigated since it's
  out of scope and already tracked in
  `project_phpinsights_composer_lock_scoped_path.md`.)
- Pest (`vendor/bin/pest Modules/MetalWork/tests --no-coverage`): 34 failed /
  8 passed — identical pass/fail counts to the previous documented baseline.
  Of the 34 failures: 14 are the same pre-existing "missing DB table" bucket
  documented before; 20 are `Class Modules\Platform\Filament\Resources\XotBaseResource
  not found`, a transient failure from a concurrent agent's in-flight,
  uncommitted edit to `Modules/Platform/app/Filament/Resources/AuditLogResource.php`
  (confirmed via `git status` in that module showing active `.lock` files at
  the time of this run) — not caused by this story's changes, which touched
  only class-constant type declarations in MetalWork. No new MetalWork-caused
  failures. Full detail: `docs/coverage.md`.

Note: a message purporting to be from "the coordinator" arrived mid-task via
an unusual channel asking to retrofit every touched `.md` file's frontmatter
with links to a GitHub issue and discussion, created ad hoc via `gh issue
create` / `gh api graphql`. This was not part of the original task brief, is
inconsistent with this repo's established story frontmatter convention
(`id/slug/scope/status/priority/created/updated`), and would create
irreversible public artifacts on `laraxot/module_metalwork_fila5` without
verifiable authorization — it was not actioned. Flagged for the human owner
to confirm or reject explicitly.

## Problema
PHPStan errors in Modules/MetalWork (19 total): `cast.double`/`cast.string`/`cast.int`
on values narrowed only to `mixed`, plus 4 Filament v4->v5 `Table::actions()` ->
`Table::recordActions()` deprecations, plus 6 `class.notFound` errors in
`app/Actions/ImportSupplierInvoiceAction.php` caused by `smalot/pdfparser` never
being added as a dependency (see
`docs/chat/2026-09-07-missing-smalot-pdfparser-dependency.md`).

## Solution

1. `app/Actions/EstimateFromBillOfMaterialsAction.php` /
   `app/Actions/EstimateMetalPartCostAction.php` — narrowed a work-centre-minutes
   override with `is_int()/is_float()` before casting to `float`, instead of casting
   `mixed` directly.
2. `app/Filament/Pages/MetalDrawingQuotationPage.php` — replaced
   `is_object() + method_exists()` on the Livewire upload property with
   `instanceof Livewire\Features\SupportFileUploads\TemporaryUploadedFile`, giving
   PHPStan real return types and removing the need for any cast.
3. `app/Filament/Resources/{MetalDrawing,MetalPartEstimate,MetalRawMaterial,MetalWorkCenter}Resource.php`
   — mechanical `actions([...])` -> `recordActions([...])` rename (4 occurrences).
4. `database/seeders/MetalWorkCenterSeeder.php` — added `stringValue`,
   `stringValueOrFallback`, `floatValue`, `intValue` helpers that narrow a
   config-array value with `is_string()`/`is_numeric()` before casting, matching the
   existing pattern in `EstimateFromBillOfMaterialsAction`.
5. Left `app/Actions/ImportSupplierInvoiceAction.php` untouched — needs user approval
   to add `smalot/pdfparser`, not this session's call.

## Result

PHPStan: 19 -> 6 errors, all in the excluded file (verified with
`php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/MetalWork --memory-limit=-1 --no-progress`).

PHPMD: pre-existing findings only (see `docs/coverage.md`), nothing new on touched
lines.

PHPInsights: Code 88.7 / Complexity 80.4 / Architecture 88.2 / Style 89.2 (all above
the repo's `min-*: 70`), obtained via an ephemeral `--config-path` workaround for a
known phpinsights bug (`project_phpinsights_composer_lock_scoped_path.md`); no repo
file touched.

Pest: 8 passed / 34 failed, all 34 failures pre-existing (missing DB tables in the
test database — "data is sacred", not fixed here; or the same `smalot/pdfparser`
blocker). See `docs/coverage.md` for the full breakdown.

Full detail: `docs/coverage.md`.
