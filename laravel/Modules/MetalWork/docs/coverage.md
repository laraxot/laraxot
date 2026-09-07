# Coverage — MetalWork

## 2026-09-07 — PHPStan zero (except pre-approved skip) + Filament v4->v5 deprecations

Story: `docs/stories/phpstan-MetalWork-fix.md`.

### PHPStan

Before: 19 errors (`Modules/MetalWork`).
After: 6 errors — all in `app/Actions/ImportSupplierInvoiceAction.php`, explicitly
out of scope (depends on `Smalot\PdfParser\Parser`, a package never added to any
`composer.json` in this repo; see
`docs/chat/2026-09-07-missing-smalot-pdfparser-dependency.md`, needs user approval
to add the dependency, not this session's call).

Fixed (13 errors):
- `app/Actions/EstimateFromBillOfMaterialsAction.php:237` and
  `app/Actions/EstimateMetalPartCostAction.php:247` — `cast.double` on a
  work-centre-minutes override read from `array<string, mixed>`. Narrowed with
  `is_int()/is_float()` before casting instead of casting `mixed` directly.
- `app/Filament/Pages/MetalDrawingQuotationPage.php:372,383` — `cast.string` on
  `$this->drawingFile` (typed `mixed`, a Livewire upload property). Replaced the
  `is_object() + method_exists()` duck-typing with
  `instanceof Livewire\Features\SupportFileUploads\TemporaryUploadedFile`, which
  gives PHPStan real return types for `getClientOriginalName(): string` and
  `getRealPath(): string|false`, so no cast is needed at all.
- `app/Filament/Resources/{MetalDrawing,MetalPartEstimate,MetalRawMaterial,MetalWorkCenter}Resource.php`
  — mechanical Filament v4->v5 `Table::actions([...])` -> `Table::recordActions([...])`
  rename (4 occurrences, none had `bulkActions()` in the same chain).
- `database/seeders/MetalWorkCenterSeeder.php:31,36,38,40,42` — `cast.string` /
  `cast.double` / `cast.int` on config-array values typed `mixed` once narrowed only
  by `is_array($center)`. Added four private helpers
  (`stringValue`, `stringValueOrFallback`, `floatValue`, `intValue`) that read a key
  and narrow with `is_string()`/`is_numeric()` before casting, falling back to a safe
  default otherwise — same pattern already used in
  `EstimateFromBillOfMaterialsAction::stringValue()`/`floatOption()`.

Verification: `php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/MetalWork --memory-limit=-1 --no-progress` → 6 errors, all in the excluded file.

### PHPMD

`./tools/phpmd.sh Modules/MetalWork text cleancode,codesize,design,naming,unusedcode`
reports pre-existing style/complexity findings (ShortVariable, StaticAccess,
ExcessiveClassComplexity/CyclomaticComplexity in `EstimateMetalPartCostAction`,
`StepFileParser`, `StepSolidExtractor`, etc., ExcessiveParameterList on the `Datas`
value objects). None of these are on the lines touched by this pass; no new
findings introduced by the fixes above (checked the diff against the full report).

### PHPInsights

`./vendor/bin/phpinsights analyse Modules/MetalWork` cannot run as documented
(`./tools/phpinsights.sh analyse Modules/MetalWork`) — PHPInsights's
`ForbiddenSecurityIssues` global insight resolves `composer.lock` relative to the
scanned path's common-path prefix (`PathShortener::extractCommonPath()`), which for
a single-subdirectory scan becomes `Modules/MetalWork/composer.lock` — a file that
does not and will never exist in this monorepo layout (only `laravel/composer.lock`
at the project root does). The root `phpinsights.php` already sets
`disable-security-check: true`, but that flag only suppresses the final failing
exit code (`AnalyseCommand.php:88`) — it does not stop the insight's `getResult()`
from throwing `ComposerNotFound` earlier in the pipeline, so the whole run crashes
before producing a report. This reproduces for any module-scoped invocation, not
just MetalWork or something specific to this session/concurrent edits.

Worked around locally (not committed) with an ephemeral `--config-path` overriding
`remove` to include `NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues::class`
— functionally identical to what `disable-security-check: true` already declares as
intent for this repo. Result:

- Code: 88.7
- Complexity: 80.4
- Architecture: 88.2
- Style: 89.2

All four above the repo's `min-*: 70` thresholds in `phpinsights.php`. Remaining
style findings are pre-existing (import ordering, class-member ordering, blank line
after opening brace) on files not touched by this pass.

Second-brain: this exact bug was already documented in
`project_phpinsights_composer_lock_scoped_path.md`; updated it with the working
`--config-path` workaround used here (ephemeral `/tmp` config, no repo file
touched) rather than creating a duplicate memory.

### Pest

`./vendor/bin/pest Modules/MetalWork/tests --no-coverage` (no per-module
`phpunit.xml` exists for MetalWork, so the `-c` form from other modules'
instructions does not apply here):

**34 failed, 8 passed** (21 assertions). All 34 failures fall into two pre-existing,
out-of-scope buckets — none caused by this session's changes:

1. `QueryException` — `Base table or view not found: metalwork_raw_materials` (and
   related MetalWork tables) in the `metal_work` test connection /
   `workorder_data_test` database. Pre-existing missing-DB-table/connection issue;
   per "data is sacred" this is not fixed via `migrate:fresh`/`--force`, just
   documented here.
2. `Error: Class "Smalot\PdfParser\Parser" not found` — every test that exercises
   `ImportSupplierInvoiceAction` (`ImportSupplierInvoiceTest`,
   `DrawingToQuotationTest`'s import-flow cases). Same missing-dependency blocker as
   the PHPStan skip above.

Passing tests (`StepFileParserTest`, geometry/solid measurement tests) do not touch
the DB or the PDF parser and are green, confirming the fixes above did not regress
anything reachable without those two blockers.
