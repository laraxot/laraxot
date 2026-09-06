# BMAD Story — MetelCatalog factory() PHPStan Fix

## Understand
- **Problem**: PHPStan reported `Call to an undefined static method MetelCatalog::factory()` in `MetelCatalogItemFactory.php:31`
- **Root Cause**: `MetelCatalog` extends `XotBaseModel` which uses `HasXotFactory` trait providing `newFactory()` but not `factory()` method
- **Rule**: Models that use `HasXotFactory` need explicit `factory()` method

## Plan
1. Add `factory()` method to `MetelCatalog` model
2. Verify PHPStan passes
3. Run quality checks (PHPMD, PHPInsights)
4. Git sync

## Implement
- **File**: `Modules/Catalog/app/Models/MetelCatalog.php`
- Added:
  - `use Illuminate\Database\Eloquent\Factories\Factory;`
  - `use Modules\Xot\Models\Traits\HasXotFactory;`
  - `use HasXotFactory;` (trait)
  - `factory()` static method returning `Factory<static>`

## Verify
- PHPStan: ✅ 0 errors
- PHPMD: ✅ (no output = no violations)
- PHPInsights: 97.9% code quality

## Document
- This story

## Status
- Branch: dev
- Module: Catalog
- Fixed: 1 PHPStan error
- Tests: 6 passed, 5 failed (pre-existing DB connection config issue)
