# Signage — quality gates (2026-09-07)

## PHPStan

Scope: `Modules/Signage` (fresh result-cache clear before each run).

- Before: 26 errors.
- After: 10 errors — all in `app/Actions/ExtractSignComponentsFromArtworkAction.php`
  (`Smalot\PdfParser\Parser` is not installed anywhere in this repo; see
  `docs/chat/2026-09-07-missing-smalot-pdfparser-dependency.md`). Out of scope —
  requires user approval to add a new composer dependency.
- Every other error fixed in code (no `phpstan.neon`/baseline/ignore changes):
  - `app/Actions/PriceSignSpecAction.php:161` — `cast.string` on a `preg_split()`
    loop variable: narrowed with `is_string()` instead of casting `mixed`.
  - `app/Filament/Pages/SignArtworkQuotationPage.php:258,269` — `cast.string` on
    `$this->artwork` (declared `mixed`): retyped the property to
    `?TemporaryUploadedFile` (the real Livewire upload type, same pattern already
    used in `Modules/EnergyBroker` and `Modules/AI`) and replaced
    `is_object()+method_exists()` with `instanceof TemporaryUploadedFile`, which
    also let the `(string)` casts be dropped (both methods already return `string`).
  - `app/Filament/Resources/Sign{CatalogItem,EstimationRule,PriceListItem,PriceList,WorkCenter}Resource.php`
    — Filament v4→v5 mechanical rename: `Table::actions()` → `recordActions()`,
    `Table::bulkActions()` → `toolbarActions()`.
  - `database/factories/SignSpecFactory.php:39` — `cast.int` on
    `Faker::randomElement()` (declared `mixed`): narrowed with `is_int()`.
  - `database/seeders/SignWorkCenterSeeder.php:35,36,43` — `cast.string`/`cast.int`
    on raw `config('signage.work_centers')` entries: extracted a private
    `normalizeCenter(mixed $center): array{...}|null` helper that narrows every
    field with `is_string()`/`is_int()`/`is_numeric()` before use. Also fixes a
    PHPMD `CyclomaticComplexity` regression the inline narrowing would otherwise
    have introduced in `run()` (10, at the configured threshold of 10) by moving
    the branching into its own method.
  - `tests/Feature/SignArtworkQuotationPageTest.php:66,99` — `cast.int` on
    `$page->get('specId')`: replaced with `Assert::assertIsInt($specId)`, the
    idiom the same file already used elsewhere in this suite.

## PHPMD (`./tools/phpmd.sh Modules/Signage text cleancode,codesize,design,naming,unusedcode`)

No new findings introduced by this work. `SignWorkCenterSeeder.php` briefly hit a
`CyclomaticComplexity` violation from the type-narrowing fix; resolved by
extracting `normalizeCenter()` (see above) — confirmed clean in isolation
(`./tools/phpmd.sh Modules/Signage/database/seeders/SignWorkCenterSeeder.php text codesize`
returns nothing). Remaining findings across the module (StaticAccess,
CouplingBetweenObjects, pre-existing CyclomaticComplexity in
`ArtworkTextLayout::fromDataTm()` and `SignTypeCodeDecoder::decode()`,
ShortVariable, LongVariable) all pre-date this session and are outside its scope.

## PHPInsights (`./tools/phpinsights.sh analyse Modules/Signage --composer=composer.lock`)

(`--composer=composer.lock` needed: the tool's default `getCommonPath()` looks for
`composer.lock` under `Modules/Signage/`, which doesn't exist there — it's a
repo-root file. Not a code issue, a scoped-analysis quirk of the tool itself.)

| Category | Before | After |
|---|---|---|
| Code | 90.7 | 90.7 |
| Complexity | 83.6 | 84.1 |
| Architecture | 88.2 | 88.2 |
| Style | 88.0 | 89.2 |

No regressions; Complexity and Style both improved from the `SignWorkCenterSeeder`
refactor. Remaining findings (mixed type hints in pre-existing files, missing
property/return type hints, long functions, brace/style nits, etc.) all pre-date
this session.

## Pest (`./vendor/bin/pest Modules/Signage/tests --no-coverage`)

Identical result before and after this session's fixes: **53 failed, 1 passed**.
Verified by stashing this session's diff and re-running. All failures are
pre-existing missing-DB-table/column errors, not caused by this work:

- `Base table or view not found: ... 'roles' doesn't exist`
- `Base table or view not found: ... 'sign_price_list_items' doesn't exist`
- `UserIdColumnsAcceptUuidTest`: `La colonna sign_specs.created_by_user_id non esiste`

Per the "data is sacred" rule, `migrate:fresh`/`migrate:refresh` were not run to
"fix" this — it would be a destructive, out-of-scope DB operation. Whoever owns
the Signage test database/migrations needs to run its migrations against
`workorder_data_test` for this suite to be exercised at all.
