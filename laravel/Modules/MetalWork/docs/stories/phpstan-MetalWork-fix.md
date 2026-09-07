---
id: phpstan-MetalWork-fix
slug: phpstan-MetalWork
scope: [module:MetalWork, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

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
