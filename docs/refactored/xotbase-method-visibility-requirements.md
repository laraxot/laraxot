# XotBase Method Visibility Requirements

## Overview
This document defines the required method visibility for abstract methods in XotBase classes to ensure proper inheritance and implementation.

## Critical Visibility Rules

### 1. Abstract Method Visibility Must Match Parent
When implementing abstract methods from XotBase classes, the visibility MUST match exactly:

- If parent declares `public abstract function`, implementation MUST be `public`
- If parent declares `protected abstract function`, implementation MUST be `protected`
- **NEVER** reduce visibility (public → protected → private)

### 2. Common XotBase Abstract Methods

#### XotBaseWidget
```php
// Parent declaration in XotBaseWidget
public abstract function getFormSchema(): array;

// Implementation MUST be public
public function getFormSchema(): array
{
    return [
        // Form schema implementation
    ];
}
```

#### XotBaseResource
```php
// Parent declaration in XotBaseResource
public static abstract function getFormSchema(): array;

// Implementation MUST be public static
public static function getFormSchema(): array
{
    return [
        // Form schema implementation
    ];
}
```

#### XotBaseViewRecord
```php
// Parent declaration in XotBaseViewRecord
public abstract function getInfolistSchema(): array;

// Implementation MUST be public
public function getInfolistSchema(): array
{
    return [
        // Infolist schema implementation
    ];
}
```

## Error Patterns and Solutions

### Error Pattern 1: Visibility Reduction
```php
// ❌ ERROR - Reducing visibility from public to protected
abstract class XotBaseWidget
{
    public abstract function getFormSchema(): array;
}

class MyWidget extends XotBaseWidget
{
    protected function getFormSchema(): array // ❌ FATAL ERROR
    {
        return [];
    }
}
```

**Error Message**: 
```
Access level to MyWidget::getFormSchema() must be public (as in class XotBaseWidget)
```

**Solution**:
```php
// ✅ CORRECT - Matching visibility
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array // ✅ CORRECT
    {
        return [];
    }
}
```

### Error Pattern 2: Missing Static Modifier
```php
// ❌ ERROR - Missing static modifier
abstract class XotBaseResource
{
    public static abstract function getFormSchema(): array;
}

class MyResource extends XotBaseResource
{
    public function getFormSchema(): array // ❌ MISSING STATIC
    {
        return [];
    }
}
```

**Solution**:
```php
// ✅ CORRECT - Including static modifier
class MyResource extends XotBaseResource
{
    public static function getFormSchema(): array // ✅ CORRECT
    {
        return [];
    }
}
```

## Detection Commands

### Find Visibility Violations
```bash
# Find protected implementations of getFormSchema in widgets
grep -r "protected function getFormSchema" laravel/Modules/*/app/Filament/Widgets/ --include="*.php"

# Find non-static implementations in resources
grep -r "public function getFormSchema" laravel/Modules/*/app/Filament/Resources/ --include="*.php" | grep -v "static"

# Find all abstract method implementations
grep -r "function getFormSchema\|function getInfolistSchema" laravel/Modules/ --include="*.php"
```

### Verify Parent Declarations
```bash
# Check XotBase abstract method declarations
grep -r "abstract.*function" laravel/Modules/Xot/app/Filament/ --include="*.php"
```

## Common XotBase Abstract Methods Reference

### Widgets
- `public abstract function getFormSchema(): array`
- `public abstract function getHeaderActions(): array` (if declared)

### Resources
- `public static abstract function getFormSchema(): array`
- `public static abstract function getTableColumns(): array` (if declared)

### Pages
- `public abstract function getInfolistSchema(): array` (ViewRecord)
- `public abstract function getFormSchema(): array` (if declared in page)

### Relation Managers
- `public abstract function getFormSchema(): array`
- `public abstract function getTableColumns(): array`

## Prevention Strategies

### 1. IDE Configuration
Configure IDE to show method signatures from parent classes when implementing abstract methods.

### 2. Code Templates
Create code templates that include correct visibility:

```php
// Widget template
public function getFormSchema(): array
{
    return [
        // TODO: Implement form schema
    ];
}

// Resource template
public static function getFormSchema(): array
{
    return [
        // TODO: Implement form schema
    ];
}
```

### 3. Static Analysis Rules
Add PHPStan rules to detect visibility violations:

```neon
# phpstan.neon
rules:
    - 'Method visibility must match parent abstract method'
```

### 4. Automated Testing
```php
// Test to verify method visibility
public function testAbstractMethodVisibility()
{
    $reflection = new ReflectionClass(MyWidget::class);
    $method = $reflection->getMethod('getFormSchema');
    
    $this->assertTrue($method->isPublic(), 
        'getFormSchema must be public to match parent abstract method');
}
```

## Resolution Checklist

When fixing visibility errors:

- [ ] Identify the parent abstract method declaration
- [ ] Check required visibility (public/protected)
- [ ] Check if static modifier is required
- [ ] Update implementation to match exactly
- [ ] Verify no other methods have same issue
- [ ] Test that error is resolved
- [ ] Update documentation if needed

## Common Mistakes to Avoid

### 1. Assuming Protected is Safer
```php
// ❌ WRONG ASSUMPTION
// "I'll make it protected for encapsulation"
protected function getFormSchema(): array // FATAL ERROR if parent is public
```

### 2. Forgetting Static Modifier
```php
// ❌ WRONG - Missing static for resource methods
public function getFormSchema(): array // Should be static for resources
```

### 3. Copy-Paste Without Checking Parent
```php
// ❌ WRONG - Copying from different base class
// Copied from EditRecord but extending ViewRecord
public function getFormSchema(): array // ViewRecord might not have this method
```

## Documentation Links

- [XotBase Extension Patterns](xotbase-extension-patterns.md)
- [Module-Specific Base Classes](module-specific-base-classes.md)
- [PHP Method Visibility Documentation](https://www.php.net/manual/en/language.oop5.visibility.php)

---

**Last Updated**: 2025-08-27
**Next Review**: 2025-09-27
