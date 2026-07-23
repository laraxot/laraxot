# Module-Specific Base Classes vs Generic XotBase Classes

## CRITICAL LARAXOT HIERARCHY RULE

### The Inheritance Hierarchy
```
Filament Classes (❌ NEVER EXTEND DIRECTLY)
    ↓
XotBase Classes (✅ Generic base functionality)
    ↓
Module-Specific Base Classes (✅ PREFERRED when available)
    ↓
Your Component Classes
```

## Rule Priority

### 1. ALWAYS Use Most Specific Base Class Available
When a module provides specialized base classes, use them instead of generic XotBase classes.

### 2. Module-Specific Base Classes Take Precedence
```php
// ❌ WRONG - Too generic
class EditPageContent extends XotBaseEditRecord

// ✅ CORRECT - Module-specific
class EditPageContent extends LangBaseEditRecord
```

## Known Module-Specific Base Classes

### Lang Module
- `LangBaseCreateRecord` - For multilingual create pages
- `LangBaseEditRecord` - For multilingual edit pages
- `LangBaseListRecords` - For multilingual list pages
- `LangBaseViewRecord` - For multilingual view pages

**Usage Pattern:**
```php
use Modules\Lang\Filament\Resources\Pages\LangBaseEditRecord;

class EditPageContent extends LangBaseEditRecord
{
    use EditRecord\Concerns\Translatable;
    
    protected static string $resource = PageContentResource::class;
}
```

### Other Modules
Additional modules may have their own specialized base classes. Always check for:
- `{Module}BaseCreateRecord`
- `{Module}BaseEditRecord`
- `{Module}BaseListRecords`
- `{Module}BaseViewRecord`

## Detection Strategy

### 1. Check Module Structure
```bash
# Look for module-specific base classes
find Modules/{ModuleName}/app/Filament -name "*Base*.php"
```

### 2. Study Existing Patterns
Look at similar files in the same module to understand patterns:
```bash
# Check existing pages in the module
ls Modules/{ModuleName}/app/Filament/Resources/*/Pages/
```

### 3. Understand Domain Requirements
- **Lang Module**: Multilingual content management
- **Cms Module**: Content management with translations
- **Other Modules**: Check documentation for specific functionality

## Error Patterns and Solutions

### Error: Using Generic Instead of Specific
```php
// ❌ PROBLEM
class EditPageContent extends XotBaseEditRecord
{
    use EditRecord\Concerns\Translatable; // Hint: needs Lang functionality
}

// ✅ SOLUTION
class EditPageContent extends LangBaseEditRecord
{
    use EditRecord\Concerns\Translatable; // Now properly supported
}
```

### Error: Missing Domain Functionality
Using generic XotBase classes when module-specific functionality is needed results in:
- Missing translation support
- Incomplete feature set
- Runtime errors
- Inconsistent behavior

## Validation Checklist

Before creating any Filament page class:

- [ ] Check if the module has specialized base classes
- [ ] Look at existing pages in the same module for patterns
- [ ] Understand the domain-specific requirements
- [ ] Use the most specific base class available
- [ ] Verify all required traits and interfaces are compatible

## Common Modules and Their Patterns

### Cms Module (Content Management)
- Uses Lang module base classes for multilingual content
- Pattern: `LangBaseCreateRecord`, `LangBaseEditRecord`

### User Module
- May have authentication-specific base classes
- Check for `UserBaseCreateRecord`, etc.

### Other Modules
- Each module may define its own specialized bases
- Always check module documentation and existing patterns

## Prevention Rules

### 1. Module Pattern Analysis
Always analyze the module's existing patterns before creating new components.

### 2. Base Class Discovery
Use this command to discover available base classes:
```bash
grep -r "class.*Base.*Record" Modules/{ModuleName}/
```

### 3. Functionality Requirements
Understand what functionality your component needs:
- Multilingual? → Use Lang base classes
- Authentication? → Check User module bases
- Domain-specific? → Look for module-specific bases

### 4. Consistency Verification
Ensure all pages in the same resource use consistent base classes.

## Documentation Updates

When discovering new module-specific base class patterns:
1. Document them in this file
2. Update the module's specific documentation
3. Add to memory system for future reference
4. Create examples and templates

---

**Critical Rule**: Always use the most specific base class available. Generic XotBase classes are fallbacks, not first choices when specialized alternatives exist.
