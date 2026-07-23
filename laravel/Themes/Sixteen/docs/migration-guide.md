# Migration Guide - Sixteen Theme Component Structure

## Overview

The Sixteen theme has been reorganized to provide better component organization and maintainability. This guide helps developers migrate from the old `bootstrap-italia` structure to the new categorized structure.

## Migration Summary

### Before (Old Structure)
```
resources/views/components/bootstrap-italia/
├── button.blade.php
├── input.blade.php
├── form.blade.php
├── table.blade.php
├── modal.blade.php
└── ...
```

### After (New Structure)
```
resources/views/components/
├── layout/
│   ├── header-main.blade.php
│   ├── footer.blade.php
│   └── hero.blade.php
├── forms/
│   ├── input.blade.php
│   ├── form.blade.php
│   └── textarea.blade.php
├── utilities/
│   ├── button.blade.php
│   └── pagination.blade.php
├── data-display/
│   ├── table.blade.php
│   └── card.blade.php
└── overlays/
    └── modal.blade.php
```

## Component Namespace Changes

### Layout Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.header-main` | `x-sixteen::layout.header-main` |
| `x-sixteen::bootstrap-italia.header-slim` | `x-sixteen::layout.header-slim` |
| `x-sixteen::bootstrap-italia.footer` | `x-sixteen::layout.footer` |
| `x-sixteen::bootstrap-italia.hero` | `x-sixteen::layout.hero` |
| `x-sixteen::bootstrap-italia.sidebar` | `x-sixteen::layout.sidebar` |

### Navigation Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.navbar` | `x-sixteen::navigation.navbar` |
| `x-sixteen::bootstrap-italia.mega-nav` | `x-sixteen::navigation.mega-nav` |
| `x-sixteen::bootstrap-italia.megamenu` | `x-sixteen::navigation.megamenu` |
| `x-sixteen::bootstrap-italia.breadcrumb` | `x-sixteen::navigation.breadcrumb` |
| `x-sixteen::bootstrap-italia.bottom-nav` | `x-sixteen::navigation.bottom-nav` |
| `x-sixteen::bootstrap-italia.skiplinks` | `x-sixteen::navigation.skiplinks` |

### Form Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.form` | `x-sixteen::forms.form` |
| `x-sixteen::bootstrap-italia.input` | `x-sixteen::forms.input` |
| `x-sixteen::bootstrap-italia.input-number` | `x-sixteen::forms.input-number` |
| `x-sixteen::bootstrap-italia.textarea` | `x-sixteen::forms.textarea` |
| `x-sixteen::bootstrap-italia.checkbox` | `x-sixteen::forms.checkbox` |
| `x-sixteen::bootstrap-italia.radio` | `x-sixteen::forms.radio` |
| `x-sixteen::bootstrap-italia.select` | `x-sixteen::forms.select` |
| `x-sixteen::bootstrap-italia.upload` | `x-sixteen::forms.upload` |

### Feedback Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.alert` | `x-sixteen::feedback.alert` |
| `x-sixteen::bootstrap-italia.notification` | `x-sixteen::feedback.notification` |
| `x-sixteen::bootstrap-italia.notifiche` | `x-sixteen::feedback.notifiche` |
| `x-sixteen::bootstrap-italia.toast` | `x-sixteen::feedback.toast` |
| `x-sixteen::bootstrap-italia.spinner` | `x-sixteen::feedback.spinner` |
| `x-sixteen::bootstrap-italia.progress` | `x-sixteen::feedback.progress` |
| `x-sixteen::bootstrap-italia.progress-indicators` | `x-sixteen::feedback.progress-indicators` |

### Data Display Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.table` | `x-sixteen::data-display.table` |
| `x-sixteen::bootstrap-italia.card` | `x-sixteen::data-display.card` |
| `x-sixteen::bootstrap-italia.badge` | `x-sixteen::data-display.badge` |
| `x-sixteen::bootstrap-italia.list-group` | `x-sixteen::data-display.list-group` |
| `x-sixteen::bootstrap-italia.timeline` | `x-sixteen::data-display.timeline` |
| `x-sixteen::bootstrap-italia.avatar` | `x-sixteen::data-display.avatar` |
| `x-sixteen::bootstrap-italia.callout` | `x-sixteen::data-display.callout` |

### Overlay Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.modal` | `x-sixteen::overlays.modal` |
| `x-sixteen::bootstrap-italia.offcanvas` | `x-sixteen::overlays.offcanvas` |
| `x-sixteen::bootstrap-italia.dropdown` | `x-sixteen::overlays.dropdown` |
| `x-sixteen::bootstrap-italia.dropdown-item` | `x-sixteen::overlays.dropdown-item` |
| `x-sixteen::bootstrap-italia.dropdown-divider` | `x-sixteen::overlays.dropdown-divider` |
| `x-sixteen::bootstrap-italia.tooltip` | `x-sixteen::overlays.tooltip` |
| `x-sixteen::bootstrap-italia.popover` | `x-sixteen::overlays.popover` |

### Media Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.carousel` | `x-sixteen::media.carousel` |
| `x-sixteen::bootstrap-italia.rating` | `x-sixteen::media.rating` |

### Utility Components
| Old Namespace | New Namespace |
|---------------|---------------|
| `x-sixteen::bootstrap-italia.button` | `x-sixteen::utilities.button` |
| `x-sixteen::bootstrap-italia.pagopa-button` | `x-sixteen::utilities.pagopa-button` |
| `x-sixteen::bootstrap-italia.spid-button` | `x-sixteen::utilities.spid-button` |
| `x-sixteen::bootstrap-italia.accordion` | `x-sixteen::utilities.accordion` |
| `x-sixteen::bootstrap-italia.collapse` | `x-sixteen::utilities.collapse` |
| `x-sixteen::bootstrap-italia.tabs` | `x-sixteen::utilities.tabs` |
| `x-sixteen::bootstrap-italia.tab` | `x-sixteen::utilities.tab` |
| `x-sixteen::bootstrap-italia.tab-item` | `x-sixteen::utilities.tab-item` |
| `x-sixteen::bootstrap-italia.stepper` | `x-sixteen::utilities.stepper` |
| `x-sixteen::bootstrap-italia.pagination` | `x-sixteen::utilities.pagination` |
| `x-sixteen::bootstrap-italia.toggle` | `x-sixteen::utilities.toggle` |

## Migration Steps

### 1. Update Blade Templates
Search and replace all component references in your Blade templates:

```bash
# Find all files using old namespaces
grep -r "x-sixteen::bootstrap-italia" resources/views/
```

### 2. Update Component References
Use your IDE's find and replace functionality to update all references:

**Find**: `x-sixteen::bootstrap-italia.`
**Replace**: `x-sixteen::[category].`

Where `[category]` is the appropriate category for each component.

### 3. Update Livewire Components
If you have Livewire components using the old namespaces:

```php
// Before
<x-sixteen::bootstrap-italia.input wire:model="name" />

// After
<x-sixteen::forms.input wire:model="name" />
```

### 4. Update Tests
Update your test files to use the new namespaces:

```php
// Before
$this->assertSee('<x-sixteen::bootstrap-italia.button>');

// After
$this->assertSee('<x-sixteen::utilities.button>');
```

### 5. Update Documentation
Update any internal documentation that references the old component structure.

## Benefits of New Structure

1. **Better Organization**: Components are logically grouped by function
2. **Improved Discoverability**: Easier to find components by category
3. **Enhanced Maintainability**: Related components are grouped together
4. **Future-Proof**: Structure supports easy addition of new components
5. **Reduced Redundancy**: Eliminates unnecessary `bootstrap-italia` folder

## Common Migration Patterns

### Form Components
```blade
{{-- Before --}}
<x-sixteen::bootstrap-italia.form method="POST" action="/contact">
    <x-sixteen::bootstrap-italia.input name="name" label="Nome" />
    <x-sixteen::bootstrap-italia.textarea name="message" label="Messaggio" />
    <x-sixteen::bootstrap-italia.button type="submit">Invia</x-sixteen::bootstrap-italia.button>
</x-sixteen::bootstrap-italia.form>

{{-- After --}}
<x-sixteen::forms.form method="POST" action="/contact">
    <x-sixteen::forms.input name="name" label="Nome" />
    <x-sixteen::forms.textarea name="message" label="Messaggio" />
    <x-sixteen::utilities.button type="submit">Invia</x-sixteen::utilities.button>
</x-sixteen::forms.form>
```

### Data Display Components
```blade
{{-- Before --}}
<x-sixteen::bootstrap-italia.card>
    <x-sixteen::bootstrap-italia.table :data="$users" />
</x-sixteen::bootstrap-italia.card>

{{-- After --}}
<x-sixteen::data-display.card>
    <x-sixteen::data-display.table :data="$users" />
</x-sixteen::data-display.card>
```

### Navigation Components
```blade
{{-- Before --}}
<x-sixteen::bootstrap-italia.navbar>
    <x-sixteen::bootstrap-italia.breadcrumb :items="$breadcrumbs" />
</x-sixteen::bootstrap-italia.navbar>

{{-- After --}}
<x-sixteen::navigation.navbar>
    <x-sixteen::navigation.breadcrumb :items="$breadcrumbs" />
</x-sixteen::navigation.navbar>
```

## Troubleshooting

### Common Issues

1. **Component Not Found**: Ensure you're using the correct category namespace
2. **Missing Components**: Check if the component exists in the new structure
3. **Namespace Errors**: Verify the component path matches the new structure

### Verification

After migration, verify that all components are working correctly:

```bash
# Check for any remaining old namespace references
grep -r "bootstrap-italia" resources/views/

# Test component rendering
php artisan view:clear
php artisan cache:clear
```

## Support

If you encounter issues during migration:

1. Check the [Component Structure Reorganization](component-structure-reorganization.md) document
2. Review the [Complete Theme Analysis](complete-theme-analysis.md) for component details
3. Verify component usage in the [Implemented Components Update](implemented-components-update.md)

---

*Last updated: December 2024*
*Theme: Sixteen v2.2.0*
*Status: Production Ready*
