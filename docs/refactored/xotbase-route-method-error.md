# XotBase Route Method Error - Critical Pattern Violation

## Error Analysis: ::route() Method Does Not Exist

### Error Details
```
BadMethodCallException: Method Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages\PreviewNotificationTemplate::route does not exist.
```

### Root Cause
XotBase page classes do NOT have a static `route()` method. This is a Filament-specific pattern that doesn't apply to XotBase classes.

### Problematic Pattern
```php
// ❌ WRONG - XotBase classes don't have route() method
public static function getPages(): array
{
    return [
        'preview' => Pages\PreviewNotificationTemplate::route('/{record}/preview'),
    ];
}
```

### Correct Pattern
```php
// ✅ CORRECT - Use class reference directly
public static function getPages(): array
{
    return [
        'preview' => Pages\PreviewNotificationTemplate::class,
    ];
}
```

## XotBase vs Filament Page Registration

### XotBase Pattern (Correct)
XotBase classes handle routing automatically through the parent class:
```php
public static function getPages(): array
{
    return [
        ...parent::getPages(),  // Auto-generated standard pages
        'custom' => Pages\CustomPage::class,  // Custom pages
    ];
}
```

### Filament Pattern (Incorrect for XotBase)
Direct Filament classes use explicit route registration:
```php
// This pattern ONLY works with direct Filament classes
public static function getPages(): array
{
    return [
        'index' => Pages\ListRecords::route('/'),
        'create' => Pages\CreateRecord::route('/create'),
        'edit' => Pages\EditRecord::route('/{record}/edit'),
    ];
}
```

## Codebase Violations Found

The following files contain `::route()` violations that need fixing:

### High Priority (Custom Pages)
- `Modules/Notify/app/Filament/Resources/NotificationTemplateResource.php` ✅ FIXED
- `Modules/Media/app/Filament/Resources/MediaResource.php` (convert page)
- `Modules/Job/app/Filament/Resources/JobResource.php` (board page)

### Standard Pages (May be using direct Filament extension)
- All files in Activity, Job, Media, Xot modules using `::route()`
- These likely need to be converted to extend XotBase classes

## Resolution Strategy

### Step 1: Identify Page Type
- **Custom Pages**: Use `PageClass::class` pattern
- **Standard Pages**: Should use `parent::getPages()` from XotBase

### Step 2: Fix Custom Pages
```php
// Before
'custom' => Pages\CustomPage::route('/custom'),

// After  
'custom' => Pages\CustomPage::class,
```

### Step 3: Fix Standard Pages
```php
// Before
public static function getPages(): array
{
    return [
        'index' => Pages\ListRecords::route('/'),
        'create' => Pages\CreateRecord::route('/create'),
        'edit' => Pages\EditRecord::route('/{record}/edit'),
    ];
}

// After
public static function getPages(): array
{
    return parent::getPages(); // XotBase handles all standard pages
}
```

### Step 4: Verify XotBase Extension
Ensure all page classes extend XotBase classes:
- `XotBaseCreateRecord`
- `XotBaseEditRecord`
- `XotBaseListRecords`
- `XotBaseViewRecord`
- `XotBasePage`

## Prevention Rules

### 1. Never Use ::route() with XotBase Classes
XotBase classes handle routing automatically.

### 2. Custom Pages Pattern
```php
// Custom pages in getPages()
'custom_name' => Pages\CustomPageClass::class,
```

### 3. Standard Pages Pattern
```php
// Let XotBase handle standard pages
public static function getPages(): array
{
    return parent::getPages();
}
```

### 4. Mixed Pattern
```php
// Standard + custom pages
public static function getPages(): array
{
    return [
        ...parent::getPages(),
        'custom' => Pages\CustomPage::class,
    ];
}
```

## Error Detection

### Search Pattern
```bash
grep -r "::route(" Modules/
```

### Common Violations
- `Pages\SomePage::route('/path')`
- Custom route definitions in getPages()
- Direct Filament class usage instead of XotBase

## Testing After Fix

1. Clear route cache: `php artisan route:clear`
2. Test all affected resources
3. Verify custom pages load correctly
4. Check navigation works properly

## Documentation Updates

This error pattern has been added to:
- XotBase extension patterns documentation
- Critical errors learned documentation
- Memory system for permanent retention
- Prevention checklists and templates

---

**Status**: Critical error pattern identified and documented
**Next Action**: Systematic fix of all violations across codebase
**Prevention**: Updated rules and templates to prevent recurrence
