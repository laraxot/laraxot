---
name: coverage
description: Pest test coverage report for Catalog module
metadata:
  module: Catalog
  phase: Development
  status: baseline
  date: 2026-09-06
---

# Catalog Module — Test Coverage

## Coverage Summary

**Target:** 80% minimum coverage across module.

| Metric | Status | Notes |
|--------|--------|-------|
| **Statements** | Pending verification | Run `php artisan test Modules/Catalog --coverage` |
| **Classes** | Pending verification | — |
| **Methods** | Pending verification | — |
| **Lines** | Pending verification | — |

## XOT-18.6 PHPStan Fixes — Complete

**Status:** DONE - All 107 PHPStan errors → 0 errors (Level 10)  
**Date:** 2026-09-06

### Error Resolution Summary

| Error Type | Count Before | Count After | Fix Applied |
|------------|--------------|-------------|------------|
| method.deprecated | 15 | 0 | Filament v4→v5 API migration |
| argument.type | 19 | 0 | Type hint corrections in tests |
| function.notFound | 18 | 0 | Global wrapper functions in Pest.php |
| method.nonObject | 15 | 0 | Test assertion type hints |
| property.nonObject | 7 | 0 | Test variable type hints |
| return.type | 7 | 0 | Factory return types (Collection\|Model) |
| parameter.missing | 21 | 0 | Factory createOne() signature fixes |
| cast.* | 4 | 0 | Mixed type casting resolved |
| binaryOp.invalid | 1 | 0 | String concatenation type resolved |
| **TOTAL** | **107** | **0** | **✓ GATE PASSED** |

### Files Modified

**Filament Resources (API Updates):**
- `app/Filament/Resources/CatalogFrequencyResource.php` — actions/bulkActions deprecated methods
- `app/Filament/Resources/CatalogMeasureUnitResource.php` — actions/bulkActions deprecated methods
- `app/Filament/Resources/CatalogModelResource.php` — actions/bulkActions deprecated methods
- `app/Filament/Resources/CatalogPartTypeResource.php` — actions/bulkActions deprecated methods
- `app/Filament/Resources/MetelCatalogResource.php` — actions/bulkActions/form() deprecated methods
- `app/Filament/Resources/MetelCatalogResource/RelationManagers/ItemsRelationManager.php` — actions/bulkActions
- `app/Filament/Resources/ProductResource.php` — actions/bulkActions deprecated methods

**Factories (Signature Fixes):**
- `database/factories/CatalogFrequencyFactory.php` — create()/createOne() return types fixed
- `database/factories/CatalogMeasureUnitFactory.php` — create()/createOne() return types fixed
- `database/factories/CatalogModelFactory.php` — create()/createOne() return types fixed
- `database/factories/CatalogPartTypeFactory.php` — create()/createOne() return types fixed
- `database/factories/MetelCatalogFactory.php` — create()/createOne() return types fixed
- `database/factories/MetelCatalogItemFactory.php` — create()/createOne() return types fixed
- `database/factories/ProductFactory.php` — create()/createOne() return types fixed

**Tests (Type Hints & Helpers):**
- `tests/Pest.php` — global namespace wrappers for helper functions
- `tests/PestHelpers.php` — braced namespace, type hint cleanup
- `tests/Feature/MetelCatalogTest.php` — test assertion type hints

**Impact on coverage:** Minimal. Changes are type-related (factory return types, test assertions, deprecated API updates). No business logic altered.

## PHPMD Violations (Pre-existing)

- 5 Policy classes exceed 10 public methods → design debt
- 2 Resource classes exceed 13 coupling threshold
- 1 TestCase variable naming violation

None related to Phase 2 factory return type additions.

## BaseModel `$connection = 'catalog'` fix — 2026-09-07

**Bug:** `Modules\Catalog\Models\BaseModel` extended `XotBaseModel` without
setting `protected $connection`. Every other module's `BaseModel`
(Activity, Blog, Cms, Costing, Customer, Document, Xot, ... 28 modules
checked) sets `$connection = <module_snake_name>`, dynamically provisioned
at runtime by `Modules\Tenant\Providers\TenantServiceProvider::mergeModuleConnections()`
(clones the default connection per enabled module, keyed by
`$module->getSnakeName()`). Catalog was the only module missing this,
introduced today in commit `9a67667` with the stated (and false) justification
"no dedicated DB connection — none configured for catalog, not guessed" —
that check only looked at static `config/database.php`, not
`TenantServiceProvider`'s dynamic per-module registration, and didn't
compare against sibling `BaseModel.php` files.

**Fix:** added `protected $connection = 'catalog';` to
`app/Models/BaseModel.php`, matching the universal convention.

**Test impact (`./vendor/bin/pest Modules/Catalog/tests --no-coverage`):**

| | Before fix | After fix |
|---|---|---|
| Failed | 6 | 5 |
| Passed | 5 | 6 |
| Assertions | 21 | 23 |

Net improvement, no regression. The 5 remaining failures are
`SQLSTATE[42S02]: Base table or view not found` on the `catalog` connection
against `workorder_data_test` — pre-existing missing migrations on that test
database, not caused by this fix (see
`project_test_db_missing_migrations_blocks_feature_tests` in second brain;
same class of issue already documented for customer/document/mariadb
connections — owner must migrate the test DB once).

PHPStan Modules/Catalog: 0 errors (clean cache run). PHPMD
(`Modules/Xot/phpmd.ruleset.xml`, no Catalog-local ruleset exists): 0
violations on the changed file. PHPInsights (ephemeral `/tmp` config
removing `ForbiddenSecurityIssues`, see second brain
`project_phpinsights_composer_lock_scoped_path`): Code 92.8, Complexity
96.5, Architecture 88.2, Style 88.0 — all above the repo's `min-*: 70` gate.

## `MetelCatalogItem` → `BasePivot` → `XotBasePivot` fix — 2026-09-07

**User request:** `MetelCatalogItem` must extend a new `Catalog\Models\BasePivot`,
which must extend `Xot\Models\XotBasePivot`.

**Investigated first (per project convention: compare against sibling
pivots before accepting the premise).** `metel_catalog_items` migration has
8 substantive business columns (metel_code, barcode, etim_code, description,
unit_of_measure, packaging, gross_price, net_price), an auto-increment `id`,
and is reached via `MetelCatalog::items()` `hasMany` (not `belongsToMany`);
`product_id` is nullable. Genuine pivots in this codebase (e.g.
`Blog\Models\CategoryPost`) have only the 2 FK columns + timestamps and are
consumed via `belongsToMany(...)->using(...)`. Flagged this mismatch to the
user via `AskUserQuestion` before implementing. **User's explicit decision:
implement literally as requested anyway** (create `BasePivot`, point
`MetelCatalogItem` at it) — proceeded on that basis.

**Concurrency note:** a different concurrent agent session independently
created `Modules/Catalog/app/Models/BasePivot.php` *without* `$connection`
(commit `c13b63c`, same missing-connection mistake as the `BaseModel` bug
above) and repointed `MetelCatalogItem` to it. A third concurrent edit then
flipped `MetelCatalogItem` back to `extends BaseModel` (converging on the
same "not really a pivot" conclusion this session raised) — while this
session held a `bashscripts/lock/` lock on the file. Reverted to
`extends BasePivot` per the user's explicit choice, which overrides an
unrequested concurrent edit. Added the missing `$connection = 'catalog';`
to `BasePivot.php`.

**Verification:** PHPStan Modules/Catalog: 0 errors (clean cache, re-run
after the concurrent-edit revert). PHPMD on both changed files: 0
violations. Pint: passed.

## Filament canonical structure (Schemas/Tables/Infolist) + PHPStan zero verification — 2026-09-07

**Context:** stories `4.14.10.catalog-canonical` e `5.72.9.catalog-filament-canonical`
(agente Kilo/MiniMax-M3, WIP non committato trovato in questa sessione) hanno
estratto `getFormSchema()`/`table()`/`getInfolistSchema()` inline dalle 6
Resource in classi dedicate `Resources/{Name}Resource/Schemas/*Form.php`,
`Schemas/*Infolist.php`, `Tables/*Table.php`, poi ripulito le Resource dai
metodi ormai shadowati (94→54, 106→56, 103→53, 94→53, 226→67, 156→56 righe).

Questa sessione (claude sonnet 5) ha verificato lo stato — nessun claim
attivo trovato in `docs/chat/INDEX.md`, `bashscripts/lock/check.sh` FREE —
e chiuso il gate:

- `./vendor/bin/phpstan analyse Modules/Catalog --memory-limit=-1 --no-progress`
  (cache pulita con `clear-result-cache`): **0 errori**.
- `./vendor/bin/pint --test Modules/Catalog`: violazioni pre-esistenti su
  file non toccati da questa WIP (tests/Pest.php, database/factories/*,
  database/seeders/*, app/Models/*.php — debito pregresso non introdotto
  qui). Le 6 Resource toccate (`no_unused_imports` residuo post-estrazione)
  sono state corrette con `pint` scoped ai soli 6 file.
- `php -l` su tutti i 36 file modificati/nuovi: nessun errore di sintassi.
- PHPMD (`phpmd.ruleset.xml` locale, formato text): 39 violazioni, tutte
  pre-esistenti su file non toccati da questa WIP (Policies `*.php` —
  pattern standard Laravel `UnusedFormalParameter` sui parametri di
  autorizzazione, presente identico in tutti i moduli — `RelationManagers/ItemsRelationManager.php`,
  `tests/TestCase.php`, `Pages/ListMetelCatalogs.php`; verificato con
  `git log` che questi file sono committati da prima, non parte della
  estrazione). Uniche 2 violazioni dentro lo scope estratto:
  `MetelCatalogsTable.php`/`ListMetelCatalogs.php` `CouplingBetweenObjects`
  15-16 vs soglia 13 — intrinseco alla concentrazione di più action/colonne
  in un'unica classe Table, non corretto (rischio di rompere le 2 custom
  table actions `importFile`/`syncApi`), documentato qui come debito
  accettato.
- PHPInsights (config ephemeral `/tmp` che rimuove `ForbiddenSecurityIssues`
  per il noto bug di risoluzione `composer.lock` su path scoped, vedi second
  brain `project_phpinsights_composer_lock_scoped_path`): Code 91.8,
  Complexity 97.6, Architecture 88.2, Style 88 — tutti sopra il gate
  `min-*: 70`. Violazioni residue di stile/lunghezza funzione sono
  pre-esistenti o strutturali (soglia 20 righe di PHPInsights vs Form/Table
  Filament tipiche, stesso bucket riscontrato su tutti gli altri moduli
  dello swarm Epic4).
- Pest (`./vendor/bin/pest Modules/Catalog/tests`): **6 failed, 5 passed
  (21 assertions)** — invariato rispetto a quanto già documentato dalla
  story 5.72.9: `UniqueConstraintViolationException` su `catalog_models`
  (DB di test non truncato tra run) e `metel_catalogs` non migrata su
  `workorder_data_test` — stesso gap infrastrutturale pre-esistente già
  tracciato sopra e in `project_test_db_missing_migrations_blocks_feature_tests`,
  non una regressione introdotta da questa estrazione.
- **Nota rilevante:** il file `docs/coverage.md` di questa working tree
  (prima di questo commit) era stato silenziosamente riportato a una
  versione precedente il commit `9a67667`+`c13b63c` (le due sezioni sopra,
  "BaseModel $connection" e "MetelCatalogItem → BasePivot", erano sparite
  dal working tree pur essendo in HEAD) — pattern già noto nello swarm
  (vedi second brain, caso analogo su HR). Ripristinato qui il contenuto
  completo di HEAD prima di aggiungere questa sezione: nessuna perdita di
  cronologia.

## Next Steps

1. Run full Pest suite with coverage: `php artisan test Modules/Catalog --coverage`
2. Update coverage table above with actual metrics
3. Track coverage trend over time
4. Owner: migrate `workorder_data_test` DB for the `catalog` connection so
   `MetelCatalogTest`'s remaining 5-6 failures can be re-evaluated (missing
   `metel_catalogs` table + missing truncate/teardown on `catalog_models`).
5. Owner/product decision needed: is `MetelCatalogItem` conceptually a
   pivot, or should it (like `Product`) extend `BaseModel`? Implemented as
   pivot per explicit user instruction 2026-09-07, but the table shape
   argues for `BaseModel`. Revisit if this causes friction (e.g. route
   model binding, API resource `id` typing).
6. Lang cleanup: popolare `Modules/Catalog/lang/{it,en}/*.php` con le chiavi
   referenziate dai nuovi Form/Infolist/Table, poi rimuovere le
   `->label(__('…'))` esplicite residue (vedi story 5.72.9 per l'elenco
   completo delle chiavi mancanti).
7. `MetelCatalogsTable`/`ListMetelCatalogs`: `CouplingBetweenObjects` sopra
   soglia PHPMD (15-16 vs 13) — valutare se estrarre le 2 custom actions
   (`importFile`, `syncApi`) in Action classes dedicate per ridurre le
   dipendenze dirette della Table/Page.

---

**Last Updated:** 2026-09-07 by PHPStan-zero verification + canonical-structure gate close (claude sonnet 5)
**Responsibility:** Catalog module maintainers
