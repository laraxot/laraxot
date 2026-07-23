# PHPStan Level 10 - Complete Implementation Plan

## Obiettivo: Zero Errori PHPStan Level 10

**Task**: Correggere TUTTI gli errori PHPStan level 10 nell'intero modulo Employee senza modificare `.phpstan.neon` e senza ignorare errori.

## Strategia di Implementazione

### Phase 1: Error Analysis & Categorization
- Ottenere lista completa errori PHPStan level 10
- Categorizzare per tipo e file
- Creare piano di implementazione sistematico

### Phase 2: Systematic File Fixes
1. **Resources** - Filament resource classes
2. **Widgets** - Filament widget classes  
3. **Policies** - Authorization policies
4. **Other Files** - Controllers, providers, etc.

### Phase 3: PHPStan Level 10 Patterns

#### Pattern 1: Eloquent Static Methods
```php
// ❌ PHPStan Level 10 Error
$users = User::where('active', true)->get();

// ✅ PHPStan Level 10 Fix
$users = User::query()->where('active', true)->get();
```

#### Pattern 2: Auth::user() Proper Typing
```php
// ❌ PHPStan Level 10 Error
$user = Auth::user();
$user->name; // Property not found

// ✅ PHPStan Level 10 Fix
$user = Auth::user();
if ($user instanceof User) {
    $user->name; // OK
}
```

#### Pattern 3: Collection Generic Types
```php
// ❌ PHPStan Level 10 Error
$entries->map(function($entry) { ... });

// ✅ PHPStan Level 10 Fix
/** @var Collection<int, WorkHour> $entries */
$entries = WorkHour::query()->get();
$entries->map(function (WorkHour $entry): array { ... });
```

#### Pattern 4: Array Type Specifications
```php
// ❌ PHPStan Level 10 Error
public function getOptions(): array

// ✅ PHPStan Level 10 Fix
/** @return array<string, string> */
public function getOptions(): array
```

#### Pattern 5: Mixed Type Handling
```php
// ❌ PHPStan Level 10 Error
$value = config('key');
return $value . 'suffix'; // Mixed type error

// ✅ PHPStan Level 10 Fix
$value = config('key');
if (is_string($value)) {
    return $value . 'suffix';
}
return 'default';
```

## Current Status

### ✅ Models (Already Level 10 Compliant)
- WorkHour.php
- Employee.php  
- User.php
- Admin.php
- Attendance.php
- TimeRecord.php

### 🔧 Files Requiring Fixes
- Filament Resources (multiple errors)
- Filament Widgets (multiple errors)
- Policies (type comparison errors)
- Various other classes

## Implementation Rules

1. **No .phpstan.neon modifications** - Configuration stays as is
2. **No error ignoring** - All errors must be fixed
3. **Backward compatibility** - Existing functionality preserved
4. **Type safety** - Proper typing throughout
5. **Laravel patterns** - Use Laravel-compatible solutions

## Error Categories Expected

### Static Method Issues
- Model::where() not recognized
- Model::find() not recognized  
- Solution: Use Model::query()->method()

### Interface vs Concrete Class Issues
- Auth::user() returns Authenticatable
- Solution: instanceof checks

### Generic Type Issues
- Collection types not specified
- Array types not specified
- Solution: PHPDoc annotations

### Mixed Type Issues
- Config values, request parameters
- Solution: Type guards and assertions

## Success Criteria

```bash
./vendor/bin/phpstan analyse app --level=10
# Expected result: [OK] No errors
```

## Next Steps

1. Get complete error list
2. Fix Resources systematically
3. Fix Widgets systematically
4. Fix Policies
5. Fix remaining files
6. Final validation
7. Update documentation

---

**Date**: 02/09/2025  
**Target**: Complete PHPStan Level 10 compliance  
**Status**: Implementation in progress