# Subagent-A Task: Fix Xot Module (9 PHPStan Errors)

## Critical Issues Found
1. **CollectionExportLabelledFieldsTest.php** (63):
   - Parameter type mismatch in `map()` method
   - Expected `Illuminate\Database\Eloquent\Model` or `array<string, mixed>` but got `TModel`
   - Fix: Ensure proper type hints in CollectionExport class

## Action Plan
1. **Fix CollectionExport class** (app/Exports/CollectionExport.php)
2. **Update test** (tests/Unit/Exports/CollectionExportLabelledFieldsTest.php)
3. **Run PHPStan again** to verify fix
4. **Run Pint** on Xot module
5. **Document** all fixes in story

## Implementation Steps
1. Open `app/Exports/CollectionExport.php` and fix the `map()` method signature
2. Update the test file to match the corrected method signature
3. Re-run PHPStan to verify 0 errors
6. Run Pint on Xot module
7. Commit changes with appropriate message