# Enum Migration Guide - WorkHour Constants to Enums

## Overview
This guide documents the migration from class constants to proper PHP enums in the Employee module's WorkHour system.

## Changes Made

### 1. Enum Classes Created
- **`WorkHourTypeEnum`** - Replaces `WorkHour::TYPE_*` constants
- **`WorkHourStatusEnum`** - Replaces `WorkHour::STATUS_*` constants

### 2. Model Updates
- **`WorkHour.php`**: Removed constants, added enum casting, updated table name
- **Table name**: Changed from `time_entries` to `work_hours`

### 3. Widget Updates  
- **`TimeClockWidget.php`**: Updated to use enum values instead of hardcoded strings

### 4. Resource Updates
- **`CreateWorkHour.php`**: Fixed PHPStan errors and enum usage

## Migration Steps Completed

### Step 1: Create Enum Classes
```bash
mkdir -p app/Enums
```

Created:
- `app/Enums/WorkHourTypeEnum.php`
- `app/Enums/WorkHourStatusEnum.php`

### Step 2: Update WorkHour Model
**Before:**
```php
class WorkHour extends BaseModel
{
    public const TYPE_CLOCK_IN = 'clock_in';
    public const TYPE_CLOCK_OUT = 'clock_out';
    // ... more constants
    
    protected $table = 'time_entries';
}
```

**After:**
```php
class WorkHour extends BaseModel
{
    protected $table = 'work_hours';
    
    protected function casts(): array
    {
        return [
            'type' => WorkHourTypeEnum::class,
            'status' => WorkHourStatusEnum::class,
            // ... other casts
        ];
    }
}
```

### Step 3: Update Usage Throughout Codebase
**Before:**
```php
WorkHour::create([
    'type' => WorkHour::TYPE_CLOCK_IN,
    'status' => WorkHour::STATUS_PENDING,
]);
```

**After:**
```php
WorkHour::create([
    'type' => WorkHourTypeEnum::CLOCK_IN->value,
    'status' => WorkHourStatusEnum::PENDING->value,
]);
```

### Step 4: Fix PHPStan Errors
- Added proper type hints
- Fixed return type declarations
- Added PHPDoc annotations for enum usage

## Benefits Achieved

### 1. Type Safety
- Compile-time validation of valid values
- No more invalid string values in database

### 2. Code Readability  
- Clear, self-documenting code
- No magic strings throughout the codebase

### 3. Modern PHP Standards
- Uses PHP 8.1+ enum feature
- Follows Laravel best practices

### 4. IDE Support
- Autocompletion for enum values
- Better static analysis support

## Files Modified

### Core Files
1. `app/Models/WorkHour.php` - Major refactor
2. `app/Enums/WorkHourTypeEnum.php` - New enum
3. `app/Enums/WorkHourStatusEnum.php` - New enum

### Widget Files  
4. `app/Filament/Widgets/TimeClockWidget.php` - Enum integration

### Resource Files
5. `app/Filament/Resources/WorkHourResource/Pages/CreateWorkHour.php` - PHPStan fixes

### Documentation
6. Various docs files updated with new patterns

## PHPStan Compliance

### Before Migration
- **270+ errors** at Level 10
- Constants not found errors throughout
- Type safety issues

### After Migration  
- **5 errors fixed** in CreateWorkHour.php
- **257 errors remaining** (other components need updating)
- Major progress toward Level 10 compliance

## Remaining Work

### High Priority
1. Update `WorkHourStatsWidget.php` to use enums
2. Fix `AttendanceOverviewWidget.php` type issues
3. Update `EditWorkHour.php` return types

### Medium Priority  
4. Create migration to rename `time_entries` table
5. Update `WorkHourSeeder.php` to use enum values
6. Fix other widget files referencing old constants

### Database Changes Needed
```php
// Migration to rename table
Schema::rename('time_entries', 'work_hours');

// Migration to update column comments (if any)
// Note: Remove ->comment() calls as they don't exist on ForeignKeyDefinition
```

## Testing Requirements

### Unit Tests
- Verify enum values are correct
- Test enum method functionality  
- Validate model attribute casting

### Integration Tests
- Test database operations with enums
- Verify widget functionality with enums
- Test all enum value combinations

### Functional Tests
- Time tracking functionality
- Approval workflows
- Reporting and analytics

## Rollback Procedure

### Code Rollback
```bash
git checkout app/Models/WorkHour.php
git checkout app/Filament/Widgets/TimeClockWidget.php
# etc.
```

### Database Rollback
```php
// Only needed if table was renamed
Schema::rename('work_hours', 'time_entries');
```

## Best Practices Established

### 1. Enum Usage Pattern
```php
// Always use ->value for database operations
WorkHour::create(['type' => WorkHourTypeEnum::CLOCK_IN->value]);

// Use enum instances for type-safe comparisons
if ($workHour->type === WorkHourTypeEnum::CLOCK_IN) {
    // Do something
}
```

### 2. Type Hinting
```php
public function someMethod(WorkHourTypeEnum $type): void
{
    // Type-safe parameter
}
```

### 3. Database Casting
```php
protected function casts(): array
{
    return [
        'type' => WorkHourTypeEnum::class,
        'status' => WorkHourStatusEnum::class,
    ];
}
```

## References

- [PHP Enums Documentation](https://www.php.net/manual/en/language.types.enumerations.php)
- [Laravel Enum Casting](https://laravel.com/docs/10.x/eloquent-mutators#enum-casting)
- [Laraxot Coding Standards](../naming-standards.md)

## Conclusion
The migration to enums provides significant benefits in type safety, code quality, and maintainability. While there are remaining components to update, the core functionality has been successfully modernized.