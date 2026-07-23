---
title: "Header Dynamic Navigation"
type: concept
sources: ["raw/headers/ui-app-header-blade.php", "raw/headers/layout-design-comuni-header-blade.php"]
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [header, navigation, json, dynamic, filament-builder]
related:
  - concepts/header-section-owner-rule
  - concepts/header-composition-rule
  - concepts/blade-component-extraction-governance
---

# Header Dynamic Navigation

## Overview
The header navigation links in the Fixcity application are dynamically loaded from a JSON configuration file instead of being hardcoded in Blade templates. This approach allows administrators to manage navigation items through the Filament Builder interface without requiring code changes.

## Implementation Details

### JSON Configuration
Navigation items are defined in `laravel/config/local/fixcity/database/content/sections/header.json` with the following structure:

```json
{
  "sections": {
    "primary_nav": {
      "items": [
        {
          "id": "amministrazione",
          "label": "Amministrazione",
          "url": "/it/amministrazione",
          "data_element": "management",
          "nav_group": "primary",
          "type": "link",
          "order": 10,
          "enabled": true,
          "visible": true,
          "active_patterns": ["it/amministrazione*", "it/tests/amministrazione*"]
        }
        // ... additional items
      ]
    },
    "secondary_nav": {
      // secondary navigation items
    }
  }
}
```

### Blade Template Implementation
In both `laravel/Themes/Sixteen/resources/views/components/ui/app/header.blade.php` and `laravel/Themes/Sixteen/resources/views/components/layout/design-comuni-header.blade.php`, the navigation is implemented as:

```blade
@php
    $headerNavConfig = [];
    $headerNavJsonPath = \Modules\Tenant\Services\TenantService::filePath('database/content/sections/header.json');
    if (is_string($headerNavJsonPath) && file_exists($headerNavJsonPath)) {
        $headerNavConfig = \Illuminate\Support\Facades\File::json($headerNavJsonPath);
    }
    $headerNavAllItems  = $headerNavConfig['sections']['primary_nav']['items'] ?? [];
    $headerNavTopicsUrl = $headerNavConfig['sections']['primary_nav']['topics_url'] ?? '/it/argomenti';
    $headerNavItems     = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'primary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    $headerNavSecondary = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'secondary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    usort($headerNavItems,     fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    usort($headerNavSecondary, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

    $headerNavItemIsActive = static function (array $item): bool {
        $patterns = $item['active_patterns'] ?? [];
        if (\is_array($patterns) && $patterns !== []) {
            foreach ($patterns as $p) {
                if (! \is_string($p) || $p === '') {
                    continue;
                }
                $normalized = ltrim($p, '/');
                if ($normalized !== '' && request()->is($normalized)) {
                    return true;
                }
            }
            return false;
        }
        $u = (string) ($item['url'] ?? '');
        $path = $u !== '' ? ltrim((string) parse_url($u, PHP_URL_PATH), '/') : '';

        return $path !== '' && (request()->is($path) || request()->is($path.'/*'));
    };
@endphp

<!-- Primary Navigation -->
<nav aria-label="Principale">
    <ul class="navbar-nav" data-element="main-navigation">
        @foreach($headerNavItems as $item)
            <li class="nav-item">
                <a class="nav-link{{ $headerNavItemIsActive($item) ? ' active' : '' }}"
                   href="{{ $item['url'] ?? '#' }}"
                   @if(! empty($item['data_element'])) data-element="{{ $item['data_element'] }}" @endif>
                    <span>{{ $item['label'] ?? '' }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>

<!-- Secondary Navigation -->
<nav aria-label="Secondaria">
    <ul class="navbar-nav navbar-secondary">
        @foreach($headerNavSecondary as $headerNavSecItem)
            <li class="nav-item">
                <a class="nav-link{{ $headerNavItemIsActive($headerNavSecItem) ? ' active' : '' }}"
                   href="{{ $headerNavSecItem['url'] ?? '#' }}"
                   @if(! empty($headerNavSecItem['data_element'])) data-element="{{ $headerNavSecItem['data_element'] }}" @endif>
                    <span>{{ $headerNavSecItem['label'] ?? '' }}</span>
                </a>
            </li>
        @endforeach
        <li class="nav-item">
            <a class="nav-link" href="{{ $headerNavTopicsUrl }}" data-element="all-topics">
                <span>Tutti gli argomenti
                    <x-filament::icon icon="heroicon-o-chevron-right" class="icon icon-sm" />
                </span>
            </a>
        </li>
    </ul>
</nav>
```

## Benefits
1. **Admin Management**: Navigation items can be managed through Filament Builder without code changes
2. **Consistency**: Ensures all headers across the application use the same navigation structure
3. **Flexibility**: Easy to add, remove, or reorder navigation items
4. **Active State**: Proper active state handling based on URL patterns
5. **Multi-group Support**: Separates primary and secondary navigation groups

## Related Concepts
- [[concepts/header-section-owner-rule]]: Defines the real runtime owner for header chrome
- [[concepts/header-composition-rule]]: Governance for header component composition
- [[concepts/blade-component-extraction-governance]]: Rules for extracting reusable Blade components

## Files Modified
- `laravel/Themes/Sixteen/resources/views/components/ui/app/header.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/layout/design-comuni-header.blade.php`

## Status
Implemented and verified. Both header templates now dynamically load navigation from JSON configuration instead of using hardcoded links.