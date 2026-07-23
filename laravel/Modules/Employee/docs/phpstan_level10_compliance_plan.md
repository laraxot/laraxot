# PHPStan Level 10 Compliance Plan - Employee Module

## Current Status
**257 errors remaining** - Full analysis completed, categorized, and prioritized

## Error Categorization

### Category 1: Widget Errors (85 errors)
**High Priority** - Affects user interface functionality
- `WorkHourStatsWidget.php`: 22 errors (constant references, type issues)
- `AttendanceOverviewWidget.php`: 35 errors (mixed types, method calls)
- Other widgets: 28 errors total

### Category 2: Resource Errors (45 errors)  
**High Priority** - Affects CRUD operations
- `EditWorkHour.php`: 15 errors (return types, mixed values)
- `CreateWorkHour.php`: 5 errors (already partially fixed)
- Other resource pages: 25 errors

### Category 3: Model Errors (62 errors)
**Medium Priority** - Data integrity issues
- Constant reference errors throughout models
- Relationship type covariance issues
- Property access on mixed types

### Category 4: Database Errors (35 errors)
**Medium Priority** - Schema and data issues
- Migration method issues (`comment()` not exists)
- Seeder type problems
- Foreign key definition problems

### Category 5: Other Components (30 errors)
**Low Priority** - Secondary functionality
- Controller return type issues
- Policy method problems
- Factory references

## Implementation Strategy

### Phase 1: Immediate Widget Fixes (2-4 hours)
1. **Fix WorkHourStatsWidget** - Replace constants with enums, add proper type hints
2. **Fix AttendanceOverviewWidget** - Resolve mixed type issues, add query() calls
3. **Update all widgets** to use enum values instead of constants

### Phase 2: Resource Page Fixes (1-2 hours)
1. **Fix EditWorkHour.php** - Return type declarations, mixed value handling
2. **Verify CreateWorkHour.php** - Ensure all enum usage is correct
3. **Update all resource pages** with proper type hints

### Phase 3: Model Consistency (2-3 hours)
1. **Add backward-compatible constants** to WorkHour model
2. **Fix relationship type annotations** - Remove problematic PHPDoc
3. **Add proper type hints** to all model methods

### Phase 4: Database & Seeder Fixes (1-2 hours)
1. **Create migration** to rename `time_entries` → `work_hours`
2. **Fix seeder enum usage** - Replace constants with enum values
3. **Remove invalid method calls** from migrations

### Phase 5: Comprehensive Validation (1-2 hours)
1. **Run PHPStan incrementally** after each category fix
2. **Test functionality** after each major change
3. **Document all fixes** in appropriate docs

## Technical Approach

### For Constant References:
```php
// BEFORE (causing errors)
$query->where('type', WorkHour::TYPE_CLOCK_IN);

// AFTER (PHPStan compliant)  
$query->where('type', WorkHourTypeEnum::CLOCK_IN->value);

// WITH BACKWARD COMPATIBILITY
class WorkHour extends BaseModel
{
    // Deprecated constants for backward compatibility
    public const TYPE_CLOCK_IN = 'clock_in';
    public const TYPE_CLOCK_OUT = 'clock_out';
    // ... etc
    
    // Primary usage should be enums
    protected function casts(): array
    {
        return [
            'type' => WorkHourTypeEnum::class,
            'status' => WorkHourStatusEnum::class,
        ];
    }
}
```

### For Mixed Type Issues:
```php
// BEFORE (PHPStan error)
$value = someFunction(); // returns mixed
$object->property = $value; // error: mixed not accepted

// AFTER (PHPStan compliant)
$value = someFunction();
if (is_string($value)) {
    $object->property = $value;
} else {
    $object->property = ''; // default value
}

// OR with type assertion
/** @var string $value */
$value = someFunction();
$object->property = $value;
```

### For Method Call Issues:
```php
// BEFORE (PHPStan error - method doesn't exist)
$foreignKey->comment('Some comment');

// AFTER (PHPStan compliant)
// Remove comment() calls or find alternative
$table->string('column')->comment('Valid comment'); // Only on columns
```

## Files Requiring Immediate Attention

### Critical Files (Fix First):
1. `app/Filament/Resources/WorkHourResource/Widgets/WorkHourStatsWidget.php`
2. `app/Filament/Widgets/AttendanceOverviewWidget.php`
3. `app/Filament/Resources/WorkHourResource/Pages/EditWorkHour.php`

### High Priority Files:
4. `app/Models/WorkHour.php` (add backward compatibility constants)
5. `database/seeders/WorkHourSeeder.php` (update to use enums)
6. All other widget files with constant references

### Database Files:
7. Migration files with `comment()` method calls
8. All files referencing `time_entries` table

## Testing Strategy

### After Each Phase:
1. Run PHPStan on modified files
2. Test basic functionality
3. Verify no regression

### Final Validation:
1. Full PHPStan level 10 run on entire module
2. Functional testing of all features
3. Database migration testing
4. Rollback procedure verification

## Risk Management

### High Risk Areas:
- Database table rename migration
- Backward compatibility breaking changes
- Complex widget functionality

### Mitigation Strategies:
- Test migrations on copy of database first
- Maintain backward compatibility constants
- Comprehensive testing after each change
- Document all changes thoroughly

## Estimated Timeline

### Phase 1: 2-4 hours
### Phase 2: 1-2 hours  
### Phase 3: 2-3 hours
### Phase 4: 1-2 hours
### Phase 5: 1-2 hours

**Total: 7-13 hours** to reach PHPStan Level 10 compliance

## Success Criteria

- ✅ Zero PHPStan errors at level 10
- ✅ All functionality working correctly
- ✅ Backward compatibility maintained
- ✅ Comprehensive documentation updated
- ✅ Database schema consistent with model

## Documentation Updates Required

1. Update all docs to reflect enum usage patterns
2. Document backward compatibility approach
3. Create migration guide for database changes
4. Update PHPStan compliance documentation

This plan provides a systematic approach to achieving full PHPStan Level 10 compliance without modifying phpstan.neon and while maintaining all functionality.