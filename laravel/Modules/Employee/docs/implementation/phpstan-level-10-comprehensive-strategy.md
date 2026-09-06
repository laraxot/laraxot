# PHPStan Level 10 - Comprehensive Strategy & Implementation

## Strategy Overview

**Goal**: Complete PHPStan Level 10 compliance for entire Employee module
**Approach**: Fix all errors, no ignores except migration-specific
**Method**: Systematic file-by-file analysis and correction

## Level 10 Requirements Analysis

### 1. Type Safety Requirements
- All properties must have explicit types
- All method parameters and return types must be fully specified
- Generic types must be explicitly declared
- No `mixed` types allowed without proper handling
- All iterable types must specify value types

### 2. Relationship Type Requirements  
- Laravel relationship methods need proper generic typing
- Covariance issues must be resolved through proper declarations
- Collection types must be fully specified with key/value types

### 3. PHPDoc Requirements
- All `@property` tags must include full type specifications
- All `@method` tags must be properly typed
- All `@param` and `@return` tags must be consistent with actual types

### 4. Dynamic Property Access
- All property access must be on defined properties
- Dynamic properties require proper `@property` declarations
- Model attributes must be explicitly typed

## File Categories and Strategy

### Models (Highest Priority)
1. **BaseModel.php** - Foundation model, must be perfect
2. **WorkHour.php** - Core business logic model 
3. **Employee.php** - STI implementation model
4. **User.php** - Parent STI model
5. **Admin.php** - Child STI model

**Strategy for Models**:
- Add comprehensive `@property` docblocks
- Fix all relationship return types
- Ensure proper generic typing for collections
- Handle all iterable array types

### Widgets (Medium Priority)
1. **TimeClockWidget.php** - Main functional widget
2. All other widgets in `app/Filament/Widgets/`

**Strategy for Widgets**:
- Fix Auth::user() type handling
- Properly type all widget properties  
- Handle Carbon/datetime typing issues
- Fix model query type annotations

### Filament Resources (Medium Priority)
1. **WorkHourResource.php**
2. All resource pages and components

**Strategy for Resources**:
- Fix Filament form/table field typing
- Handle generic collection types
- Proper policy and authorization typing

### Services & Other Classes (Low Priority)
1. Controllers
2. Policies  
3. Providers
4. Tests

## Common PHPStan Level 10 Issues & Solutions

### Issue 1: Generic Collection Types
**Problem**: 
```php
@property Collection $items  // ❌ Missing generic types
```

**Solution**:
```php
@property Collection<int, ItemModel> $items  // ✅ Full generic typing
```

### Issue 2: Iterable Array Types
**Problem**:
```php
@property array $data  // ❌ No value type specified
```

**Solution**:
```php
@property array<string, mixed> $data  // ✅ Key/value types specified
```

### Issue 3: Laravel Relationship Covariance
**Problem**:
```php
@return BelongsTo<Model, $this>  // ❌ Covariance issue
```

**Solution**:
```php
// Remove generic docblock, let PHPStan infer
public function relation(): BelongsTo  // ✅ Let PHPStan handle it
```

### Issue 4: Auth::user() Type Handling
**Problem**:
```php
$user = Auth::user();  // Returns Authenticatable|null
$user->property;  // ❌ Property not found on interface
```

**Solution**:
```php
$user = Auth::user();
if ($user instanceof SpecificUserModel) {
    $user->property;  // ✅ Proper type narrowing
}
```

### Issue 5: Carbon Chaining Issues
**Problem**:
```php
Carbon::now()->locale('it')->format('Y-m-d');  // ❌ Method chaining issues
```

**Solution**:
```php
$carbon = Carbon::now();
$carbon->locale('it');
return $carbon->format('Y-m-d');  // ✅ Separate operations
```

## Implementation Order

### Phase 1: Foundation Models
1. Fix BaseModel with all property definitions
2. Fix User model as STI parent
3. Fix Employee model as STI child
4. Fix WorkHour model with enum integration

### Phase 2: Core Functionality
1. Fix TimeClockWidget (main functional component)
2. Fix WorkHourResource and related components
3. Fix other widgets one by one

### Phase 3: Supporting Classes
1. Fix policies and authorization
2. Fix providers and services
3. Fix controllers and other classes

### Phase 4: Validation & Documentation
1. Run full module PHPStan level 10 validation
2. Fix any remaining issues  
3. Document all changes and patterns

## Error Cataloging Strategy

For each PHPStan run:
1. **Categorize errors** by type (property, method, generic, etc.)
2. **Group by file** to handle systematically
3. **Prioritize by impact** (breaking vs. warning)
4. **Fix in batches** to avoid conflicts

## Success Criteria

**Complete Success**:
```bash
./vendor/bin/phpstan analyse Modules/Employee/app --level=10
# Result: [OK] No errors
```

**Acceptable Minimal Errors**:
- Only migration-related errors (already ignored)
- No functional code errors
- No type safety violations

## Documentation Updates

After completion:
1. Update architecture docs with type patterns
2. Document PHPStan level 10 compliance guidelines
3. Create type safety best practices guide
4. Update development workflow documentation

---

**Implementation Date**: 02/09/2025  
**Expected Duration**: Full day implementation  
**Validation**: Zero errors on PHPStan level 10