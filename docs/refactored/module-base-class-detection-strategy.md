# Module-Specific Base Class Detection Strategy

## Overview
This document provides systematic detection strategies to identify when module-specific base classes should be used instead of generic XotBase classes.

## Detection Rules

### 1. Translatable Trait Detection
**Rule**: Any class using `Translatable` trait MUST extend LangBase classes

**Detection Command**:
```bash
# Find files using Translatable trait
grep -r "use.*Translatable" laravel/Modules/ --include="*.php"

# Find classes extending XotBase but using Translatable (violations)
grep -l "extends.*XotBase" laravel/Modules/ --include="*.php" | xargs grep -l "use.*Translatable"
```

**Expected Results**:
- Resources with Translatable → should extend `LangBaseResource`
- Edit pages with Translatable → should extend `LangBaseEditRecord`
- Create pages with Translatable → should extend `LangBaseCreateRecord`
- List pages with Translatable → should extend `LangBaseListRecords`
- View pages with Translatable → should extend `LangBaseViewRecord`

### 2. User Module Hierarchy Detection
**Rule**: User module pages should use module-specific base classes when available

**Detection Command**:
```bash
# Find User module pages extending XotBase directly
find laravel/Modules/User -name "*.php" -exec grep -l "extends.*XotBase" {} \;

# Check for module-specific base classes in User module
find laravel/Modules/User -name "*Base*.php" | grep -v XotBase
```

**Expected Patterns**:
- `EditUser` should extend `BaseEditUser` (not `XotBaseEditRecord`)
- Other User pages should check if module-specific bases exist

### 3. Module-Specific Base Class Discovery
**Rule**: Identify modules that provide their own base classes

**Detection Command**:
```bash
# Find all module-specific base classes
find laravel/Modules -name "*Base*.php" | grep -v XotBase | sort

# Find abstract base classes in modules
grep -r "abstract class.*Base" laravel/Modules/ --include="*.php"
```

### 4. Inheritance Hierarchy Validation
**Rule**: Ensure proper inheritance chain is followed

**Detection Command**:
```bash
# Find classes that might be violating hierarchy
grep -r "extends.*Record" laravel/Modules/ --include="*.php" | grep -v XotBase | grep -v LangBase
```

## Automated Detection Script

```bash
#!/bin/bash
# module-base-class-detector.sh

echo "=== Module-Specific Base Class Violations Detection ==="

echo "1. Checking Translatable trait violations..."
echo "Files using Translatable but extending XotBase (should use LangBase):"
grep -l "extends.*XotBase" laravel/Modules/**/*.php | xargs grep -l "use.*Translatable" 2>/dev/null

echo -e "\n2. Checking User module hierarchy violations..."
echo "User module files extending XotBase directly:"
find laravel/Modules/User -name "*.php" -exec grep -l "extends.*XotBase" {} \; 2>/dev/null

echo -e "\n3. Available module-specific base classes:"
find laravel/Modules -name "*Base*.php" | grep -v XotBase | sort

echo -e "\n4. Abstract base classes in modules:"
grep -r "abstract class.*Base" laravel/Modules/ --include="*.php" 2>/dev/null

echo -e "\n5. Potential hierarchy violations:"
grep -r "extends.*Record" laravel/Modules/ --include="*.php" | grep -v XotBase | grep -v LangBase 2>/dev/null | head -10

echo -e "\n=== Detection Complete ==="
```

## Manual Verification Checklist

### For Each Module:
- [ ] Check if module has its own base classes (`*Base*.php`)
- [ ] Verify pages extend appropriate base classes
- [ ] Confirm resources with Translatable use LangBase
- [ ] Validate inheritance hierarchy is correct

### For Translatable Content:
- [ ] All resources with `use Translatable` extend `LangBaseResource`
- [ ] All pages with `use.*Translatable` extend appropriate `LangBase*` class
- [ ] No XotBase classes are used with Translatable trait

### For User Module:
- [ ] `EditUser` extends `BaseEditUser` (not `XotBaseEditRecord`)
- [ ] Other User pages check for module-specific bases first
- [ ] No duplicate functionality between base and derived classes

## Common Violation Patterns

### Pattern 1: Translatable with XotBase
```php
// ❌ VIOLATION
class MyResource extends XotBaseResource
{
    use Translatable;
}

// ✅ CORRECT
class MyResource extends LangBaseResource
{
    use Translatable;
}
```

### Pattern 2: Ignoring Module Hierarchy
```php
// ❌ VIOLATION - User module ignoring BaseEditUser
class EditUser extends XotBaseEditRecord
{
    // Duplicate password logic
}

// ✅ CORRECT - Using module-specific base
class EditUser extends BaseEditUser
{
    // Inherits password logic
}
```

### Pattern 3: Missing Module-Specific Features
```php
// ❌ VIOLATION - Missing multilingual support
class EditPageContent extends XotBaseEditRecord
{
    use EditRecord\Concerns\Translatable; // Won't work properly
}

// ✅ CORRECT - Proper multilingual support
class EditPageContent extends LangBaseEditRecord
{
    use EditRecord\Concerns\Translatable; // Works correctly
}
```

## Prevention Strategies

### 1. Code Review Checklist
- Check for Translatable trait usage
- Verify module-specific base class availability
- Confirm proper inheritance hierarchy
- Validate no duplicate functionality

### 2. Automated Testing
```php
// Example PHPUnit test
public function testTranslatableClassesUseLangBase()
{
    $files = glob('Modules/*/app/Filament/**/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        if (strpos($content, 'use Translatable') !== false) {
            $this->assertStringContains('LangBase', $content, 
                "File {$file} uses Translatable but doesn't extend LangBase class");
        }
    }
}
```

### 3. Static Analysis Rules
Add to PHPStan configuration:
```neon
rules:
    - 'Classes using Translatable trait must extend LangBase classes'
    - 'User module pages should extend module-specific base classes'
```

## Resolution Priority

### High Priority (Critical Violations)
1. Translatable trait with XotBase classes
2. User module hierarchy violations
3. Missing required abstract method implementations

### Medium Priority (Optimization)
1. Duplicate functionality in derived classes
2. Unused imports after base class changes
3. Documentation updates

### Low Priority (Maintenance)
1. Code style and formatting
2. Comment updates
3. Test coverage improvements

## Documentation Updates Required

After fixing violations:
1. Update module-specific documentation
2. Add examples to XotBase extension patterns
3. Create prevention guidelines
4. Update developer onboarding materials

## Testing Strategy

### Unit Tests
- Test each base class functionality
- Verify inheritance chain works correctly
- Confirm abstract methods are implemented

### Integration Tests
- Test Translatable functionality with LangBase
- Verify User module specific features work
- Confirm no regressions in existing functionality

### Manual Testing
- Load each fixed page in browser
- Test form submissions and validations
- Verify translations work correctly
- Check user-specific functionality

## Maintenance

### Regular Audits
- Run detection script monthly
- Review new modules for compliance
- Update detection rules as needed
- Monitor for new violation patterns

### Developer Education
- Include in onboarding documentation
- Create examples and tutorials
- Regular team training sessions
- Code review guidelines

---

**Last Updated**: 2025-08-27
**Next Review**: 2025-09-27
