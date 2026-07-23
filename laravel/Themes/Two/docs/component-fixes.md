# Component Fixes - Two Theme
**Data**: 7 Febbraio 2026  
**Theme**: Two  
**Status**: ✅ Fixed

---

## 📋 Fix Summary

### Issue: Unable to locate a class or view for component [ui.service-card]

**Error Message**: `Unable to locate a class or view for component [ui.service-card]`

**Root Cause Analysis**:
1. The component `<x-ui.service-card>` was used in `blocks/services/enhanced-grid.blade.php`
2. The component existed in Theme Sixteen (`Themes/Sixteen/resources/views/components/ui/service-card.blade.php`)
3. The component did NOT exist in Theme Two
4. The component was NOT in Modules/UI (because it uses theme-specific CSS variables)

**Component Location Analysis**:
- ❌ **NOT** in `Modules/UI/resources/views/components/` (component uses theme-specific CSS)
- ✅ **YES** in `Themes/Sixteen/resources/views/components/ui/service-card.blade.php`
- ❌ **NO** in `Themes/Two/resources/views/components/ui/` (missing)

**Why NOT in Modules/UI?**
The component uses theme-specific CSS variables:
- `var(--italia-blue-50)`
- `var(--italia-blue-300)`
- `var(--italia-blue-500)`
- `var(--italia-green-50)`
- `var(--italia-yellow-50)`
- `var(--italia-red-50)`

These variables are specific to the Sixteen/Two theme design system, making the component **theme-specific** rather than **agnostic**.

---

## 🔧 Solution Implemented

### Action: Copy Component from Sixteen to Two

**Command Executed**:
```bash
mkdir -p /var/www/_bases/base_techplanner_fila5/laravel/Themes/Two/resources/views/components/ui
cp /var/www/_bases/base_techplanner_fila5/laravel/Themes/Sixteen/resources/views/components/ui/service-card.blade.php \
   /var/www/_bases/base_techplanner_fila5/laravel/Themes/Two/resources/views/components/ui/service-card.blade.php
```

**Result**: ✅ Component copied successfully

**Files Modified**:
- ✅ `laravel/Themes/Two/resources/views/components/ui/service-card.blade.php` - NEW (copied from Sixteen)

---

## 📚 Documentation Updated

### Files Updated:
1. ✅ `laravel/Themes/Two/docs/00-index.md` - Added analysis-complete-summary.md
2. ✅ `docs/modules_master_index.md` - Added Notify inbound marketing strategy
3. ✅ `docs/modules_master_index.md` - Added component-specific rules section

---

## 🎯 Component-Specific Rules

### Rule: Component Placement Decision Tree

```
Is the component agnostic (no theme-specific CSS)?
├─ YES → Place in Modules/UI/resources/views/components/
└─ NO  → Place in Themes/{ThemeName}/resources/views/components/ui/
```

### Examples:

**Component should be in Modules/UI** (Agnostic):
- Generic button component (standard colors)
- Form input component (no theme-specific styling)
- Modal component (generic)

**Component should be in Themes/** (Theme-Specific):
- ✅ service-card (uses `--italia-blue-*` variables)
- Any component using theme-specific CSS variables
- Components with theme-specific design system

---

## 📋 Component Props

The `service-card` component accepts the following props:

```php
@props([
    'title' => '',              // Title of the service
    'description' => '',        // Description of the service
    'icon' => 'it-settings',    // Icon name for the service
    'url' => '#',              // URL to the service page
    'category' => '',          // Category of the service
    'status' => 'active',      // 'active', 'inactive', 'maintenance'
    'id' => null,              // Custom ID for the service card
    'class' => '',             // Additional CSS classes
    'featured' => false,       // Whether the service is featured
    'image' => null,           // Background image URL
    'color' => 'primary'       // Accent color for the service
])
```

### Usage Example:

```blade
<x-ui.service-card
    title="Controllo Radioprotezione"
    description="Verifiche periodiche e straordinarie per apparecchiature radiologiche"
    icon="heroicon-o-shield-check"
    url="/it/servizi"
    category="Radioprotezione"
    status="active"
    featured="true"
/>
```

---

## 🚀 Next Steps

After copying the component:

1. ✅ Clear view cache: `php artisan view:clear`
2. ✅ Verify component renders correctly
3. ⚠️ **IMPORTANT**: Customize CSS variables for Theme Two design system
4. ⚠️ Update icon names to match Theme Two conventions
5. ⚠️ Test all service card instances in the theme

---

## 📊 Files Using This Component

The `service-card` component is used in:

1. `Themes/Two/resources/views/components/blocks/services/enhanced-grid.blade.php`
2. `Themes/Sixteen/resources/views/pages/services/index.blade.php`
3. `Themes/Sixteen/resources/views/components/ui/services-grid.blade.php`

---

**Fix Status**: ✅ RESOLVED  
**Date**: 7 Febbraio 2026  
**Agent**: iFlow CLI  
**Verified**: ✅ Component copied and view cache cleared
