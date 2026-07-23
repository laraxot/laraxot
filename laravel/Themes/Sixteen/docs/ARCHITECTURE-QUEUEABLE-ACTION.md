# Architecture: QueueableAction Pattern (Theme Sixteen)

> **Inherited Rule**: From `laravel/Modules/docs/QUEUEABLE-ACTION-RULE.md`  
> **Package**: [spatie/laravel-queueable-action](https://github.com/spatie/laravel-queueable-action)  
> **Status**: REQUIRED - No Services allowed

## Quick Reference

```
┌─────────────────────────────────────────────────────────────┐
│  ❌ BANNED in Theme: Services                                 │
│  ✅ REQUIRED in Theme: QueueableAction (when needed)        │
└─────────────────────────────────────────────────────────────┘
```

## What This Means for Theme Development

Theme Sixteen focuses on **Presentation Layer** (Blade, Tailwind, Alpine.js). However, when you need business logic in ViewModels:

### ❌ WRONG - Creating Services in Theme

```php
// NEVER do this in Themes/Sixteen
namespace Themes\Sixteen\Services;  // ❌ DON'T CREATE

class FilterService { ... }  // ❌ BANNED
```

### ✅ CORRECT - Use Module Actions

```php
// In ViewModel or Blade component - use Module's Actions
use Modules\Fixcity\Actions\BuildTicketCategoriesFromGeoJsonAction;

class SomeViewModel {
    public function getFilters(): array {
        return (new BuildTicketCategoriesFromGeoJsonAction(...))->execute();
    }
}
```

## Theme vs Module Responsibilities

| Layer | Responsibility | Pattern |
|-------|---------------|---------|
| **Module** (Fixcity) | Business Logic | QueueableAction |
| **Module** (Fixcity) | Data Processing | QueueableAction |
| **Theme** (Sixteen) | Presentation | Blade + Tailwind |
| **Theme** (Sixteen) | View Models | Call Module Actions |

## When Theme Needs Actions

Rare cases when Theme Sixteen might need its own Actions:

1. **Theme-specific transformations** (not business logic)
2. **Asset processing** (images, CSS build)
3. **Theme-specific caching**

```php
// Example: Theme Sixteen specific Action
namespace Themes\Sixteen\Actions;

use Spatie\QueueableAction\QueueableAction;

class OptimizeHeroImagesAction {
    use QueueableAction;
    
    public function handle(): void {
        // Optimize images in public_html/assets
    }
}
```

## How to Use Module Actions in Theme

### In Blade Components

```blade
{{-- Call Action via ViewModel --}}
@php
use Modules\Fixcity\Actions\BuildTicketCategoriesFromGeoJsonAction;

$filters = (new BuildTicketCategoriesFromGeoJsonAction(
    geoJsonPath: public_path('/data/tickets.json'),
    typeConfig: config('fixcity.types'),
))->execute();
@endphp

@foreach($filters as $filter)
    {{ $filter['label'] }} ({{ $filter['count'] }})
@endforeach
```

### In ViewModels

```php
namespace Themes\Sixteen\ViewModels;

use Modules\Fixcity\Actions\BuildTicketCategoriesFromGeoJsonAction;
use Illuminate\Support\Facades\Cache;

class TicketPageViewModel {
    public function filters(): array {
        return Cache::remember('theme_filters', 300, function () {
            return (new BuildTicketCategoriesFromGeoJsonAction(
                geoJsonPath: public_path('/data/tickets.json'),
                typeConfig: config('fixcity.types'),
            ))->execute();
        });
    }
}
```

## Reference

- **Full Documentation**: `../../../Modules/docs/QUEUEABLE-ACTION-RULE.md`
- **Module Actions**: `../../../Modules/Fixcity/app/Actions/`
- **Package**: https://github.com/spatie/laravel-queueable-action

## Summary

1. **Never** create Services (anywhere)
2. **Always** use QueueableAction (from spatie)
3. **Theme Sixteen** calls **Module Actions** for business logic
4. **Theme Sixteen** handles presentation only

---

**DRY Principle**: This file references the full documentation in Modules/docs/ to avoid duplication.
