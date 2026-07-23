# PHPStan Level 10 Implementation - Employee Module

## Current State Analysis

**PHPStan Configuration**: Updated to level 10 with minimal ignores
**Target**: Level 10 with all errors fixed (not ignored)
**Location**: `.phpstan.neon`
**Status**: COMPREHENSIVE FIX REQUIRED - ALL ERRORS MUST BE RESOLVED

## Issues to Resolve for Level 10

### 1. Relationship Method Return Types

**Problem**: Overly specific `@return` docblocks cause covariance issues at higher levels.

**Files Affected**:
- `WorkHour.php:109-120` (employee relationship)
- `WorkHour.php:119-124` (approvedBy relationship)  
- `Employee.php:82-87` (workHours relationship)

**Solution**: Remove detailed `@return` docblocks, keep only type hints.

**Before**:
```php
/**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\Employee\Models\Employee, \Modules\Employee\Models\WorkHour>
 */
public function employee(): BelongsTo
```

**After**:
```php
/**
 * Get the employee that owns the work hour record.
 */
public function employee(): BelongsTo
```

### 2. Enum Implementation Issues

**Problem**: Constants instead of proper PHP enums cause type safety issues.

**Current Issues**:
- `WorkHour.php:34-59` - Constants for types and statuses
- No enum casting in model

**Solution**: Create proper enums and implement casting.

### 3. Mixed Type Issues

**Problem**: Config values and other mixed types need proper handling.

**Locations**:
- User model configuration values
- Service class initializations

### 4. Property Type Declarations

**Problem**: All model properties need explicit typing for level 10.

**Required Changes**:
- Add `@property` docblocks for all model attributes
- Ensure consistent typing across relationships
- Fix any `mixed` type declarations

## Implementation Plan

### Step 1: Prepare PHPStan Configuration

Update `.phpstan.neon` to level 10:
```neon
parameters:
    level: 10
    paths:
        - app
        - tests
    excludePaths:
        - database/migrations
    checkMissingIterableValueType: false
    
    ignoreErrors:
        # Only migration-related errors
        - '#Call to an undefined method.*renameColumn#'
        - '#Call to an undefined method.*dropIndex#'
```

### Step 2: Fix Models in Order

1. **WorkHour Model**
   - Fix table name
   - Remove specific relationship docblocks
   - Implement enum casting
   - Add proper property docblocks

2. **Employee Model** 
   - Add HasParent trait
   - Fix relationship docblocks
   - Remove table override

3. **User Model**
   - Fix childTypes mapping
   - Ensure proper STI setup

### Step 3: Create Enums

Create enum classes:
- `WorkHourTypeEnum`
- `WorkHourStatusEnum`

### Step 4: Update Widget

Fix TimeClockWidget queries to work with STI.

### Step 5: Validate

Run PHPStan level 10 after each change to ensure compliance.

## Validation Commands

```bash
# Test specific model
./vendor/bin/phpstan analyse Modules/Employee/app/Models/WorkHour.php --level=10

# Test full module
./vendor/bin/phpstan analyse Modules/Employee/app --level=10

# Test with memory limit
./vendor/bin/phpstan analyse Modules/Employee/app --level=10 --memory-limit=2G
```

## Expected PHPStan Level 10 Results

After implementation:
- ✅ No type covariance errors
- ✅ All properties properly typed
- ✅ Enum types correctly implemented
- ✅ Relationship methods clean
- ✅ Mixed types properly handled

## Breaking Changes

**None Expected**: All changes maintain backward compatibility while improving type safety.

## Documentation Updates Required

1. Update architecture documentation
2. Document enum usage patterns
3. Update development best practices
4. Create PHPStan level 10 compliance guide

---

**Implementation Date**: 02/09/2025  
**Target Completion**: Same day (critical fixes)  
**Validation**: PHPStan level 10 passing