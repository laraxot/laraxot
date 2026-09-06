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

## Phase 2 Changes

**commit:** `fix: add return type declarations to factory create/createOne methods for PHPStan L10`

**Files modified:**
- `database/factories/CatalogFrequencyFactory.php` (+12 lines — create/createOne methods)
- `database/factories/CatalogMeasureUnitFactory.php` (+12 lines)
- `database/factories/CatalogModelFactory.php` (+12 lines)
- `database/factories/CatalogPartTypeFactory.php` (+12 lines)
- `database/factories/MetelCatalogFactory.php` (+12 lines)
- `database/factories/MetelCatalogItemFactory.php` (+12 lines)
- `database/factories/ProductFactory.php` (+12 lines)

**Impact on coverage:** None expected. Changes are pure type hints (return types) on factory methods; no business logic altered.

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
