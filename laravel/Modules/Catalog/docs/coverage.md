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

## Next Steps

1. Run full Pest suite with coverage: `php artisan test Modules/Catalog --coverage`
2. Update coverage table above with actual metrics
3. Track coverage trend over time

---

**Last Updated:** 2026-09-06 by PHPStan Phase 2 workflow  
**Responsibility:** Catalog module maintainers
