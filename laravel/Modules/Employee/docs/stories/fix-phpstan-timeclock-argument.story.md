# Story 5.2: Fix PHPStan Errors — TimeClockPage + WorkHour

**Status**: ready-for-dev  
**Module**: Employee (independent .git)  
**PHPStan errors**: 6 total  
**Effort**: small  
**Owned by**: TBD (needs coordination)

## Errors

### Error 1: Deprecated method form() → schema()
- **File**: `app/Filament/Resources/WorkHourResource/Pages/TimeClockPage.php:79`
- **Type**: `method.deprecated`
- **Message**: Call to deprecated method form() of class Filament\Tables\Filters\BaseFilter: Use `schema()` instead.

### Error 2-3: Argument type `mixed`
- **File**: `app/Filament/Resources/WorkHourResource/Pages/TimeClockPage.php:88,89`
- **Type**: `argument.type`
- **Message**: Parameter #1 $value of function strval expects bool|float|GMP|int|resource|string|null, mixed given.

### Error 4-5: Type coverage WorkHour model
- **File**: `app/Models/WorkHour.php:75,83`
- **Type**: `typeCoverage.constantTypeCoverage`
- **Message**: Out of 148 possible constant types, only 145 - 97.9 % actually have it.

## Root Causes

1. Filament v5 API change: `form()` → `schema()` for filters
2. `strval()` receives mixed argument; needs explicit type inference from context
3. WorkHour constants missing explicit type declarations

## Solution

1. **TimeClockPage line 79**: Replace `->form(` with `->schema(`
2. **TimeClockPage lines 88-89**: Add explicit type to `$value` argument; infer from usage context (likely string)
3. **WorkHour lines 75,83**: Add explicit types to all const declarations (const string X = ..., const int Y = ...)
4. Verify: `phpstan analyse Modules/Employee --no-progress`
5. Run phpmd + pest (coverage UP)
6. Git sync

## Files Modified

- `app/Filament/Resources/WorkHourResource/Pages/TimeClockPage.php` (2 fixes)
- `app/Models/WorkHour.php` (const types)

## Success Criteria

- PHPStan: 0 errors on `Modules/Employee`
- PHPMD: no violations  
- Pest: all pass, coverage ≥ prior
- Git: commit + push to laraxot/dev

## Notes

- No User/Profile replacement needed (simple API migration + const types)
- Filament v5 schema refactoring ongoing in repo
- Coordinate with any concurrent TextEntry migration work
