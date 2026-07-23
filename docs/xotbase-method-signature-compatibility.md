# XotBase Method Signature Compatibility Guide

## Critical Rules for Method Signature Compatibility

When implementing traits or extending XotBase classes, **EXACT** method signature compatibility is mandatory to prevent PHP fatal errors.

### Method Signature Components

All components must match between trait and base class:

1. **Static vs Non-Static** - Must match exactly
2. **Visibility** - Must match or be more permissive (public ≥ protected ≥ private)
3. **Return Types** - Must be compatible
4. **Parameter Types** - Must be compatible
5. **Parameter Names** - Should match for clarity

### Common XotBase Method Signatures

#### XotBasePage Methods
```php
// NON-STATIC instance methods
public function getTitle(): string
public function getHeading(): string
public function getSubHeading(): string
public function getModelLabel(): string
public function getPluralModelLabel(): string
```

#### XotBaseResource Methods
```php
// NON-STATIC instance methods
public function getFormSchema(): array
```

#### XotBaseListRecords Methods
```php
// Auto-managed - DO NOT implement getTableColumns()
// XotBase handles table columns automatically
```

#### XotBaseViewRecord Methods
```php
// ABSTRACT method - MUST be implemented
public function getInfolistSchema(): array
```

### Fatal Error Patterns

#### Static/Non-Static Mismatch
```php
// ❌ FATAL ERROR
// Base class has: public function getModelLabel(): string
// Trait implements: public static function getModelLabel(): string
// Error: "Cannot make static method non static"

// ✅ CORRECT
// Both must be non-static
public function getModelLabel(): string
{
    return static::trans('navigation.name');
}
```

#### Visibility Restriction
```php
// ❌ FATAL ERROR
// Base class has: public function getFormActions(): array
// Implementation: protected function getFormActions(): array
// Error: Visibility cannot be more restrictive

// ✅ CORRECT
public function getFormActions(): array
{
    return [];
}
```

### Prevention Checklist

Before implementing any trait or method override:

- [ ] **Analyze base class** - Read the actual source code
- [ ] **Check method signatures** - Verify static/non-static, visibility, return types
- [ ] **Review existing implementations** - Look at similar files in the same module
- [ ] **Test immediately** - Run the application after implementation
- [ ] **Document requirements** - Update this guide with new patterns discovered

### Investigation Process

1. **Read Base Class Source Code**
   ```bash
   # Always check the actual implementation
   cat laravel/Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php
   ```

2. **Search for Existing Patterns**
   ```bash
   # Find similar implementations
   grep -r "getModelLabel" laravel/Modules/*/app/Filament/
   ```

3. **Verify Method Signatures**
   ```bash
   # Check if method is static or instance
   grep -A 5 "function getModelLabel" laravel/Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php
   ```

### Common Mistakes

1. **Assuming Static Methods** - Don't assume navigation/label methods are static
2. **Copying Wrong Patterns** - Different XotBase classes have different requirements
3. **Incomplete Analysis** - Always check the actual base class, not documentation
4. **Mixed Patterns** - Don't mix patterns from different XotBase classes

### Error Resolution Steps

When encountering method signature errors:

1. **Identify the base class** method signature
2. **Update trait/implementation** to match exactly
3. **Test the fix** immediately
4. **Document the pattern** for future reference
5. **Update related implementations** if needed

### XotBase Class Reference

| XotBase Class | Required Abstract Methods | Common Instance Methods |
|---------------|--------------------------|------------------------|
| XotBasePage | None | getTitle(), getHeading(), getModelLabel() |
| XotBaseResource | getFormSchema() | form(), table() |
| XotBaseListRecords | None | Auto-managed columns |
| XotBaseViewRecord | getInfolistSchema() | infolist() |
| XotBaseCreateRecord | None | form() |
| XotBaseEditRecord | None | form() |

### Related Documentation

- [XotBase Extension Patterns](xotbase-extension-patterns.md)
- [Laraxot Philosophy](laraxot-philosophy.md)
- [PHP Method Signatures](https://www.php.net/manual/en/language.oop5.inheritance.php)

---

**Last Updated**: Based on NavigationPageLabelTrait fix - all methods are NON-STATIC instance methods
**Critical**: This guide prevents fatal PHP errors in XotBase implementations
