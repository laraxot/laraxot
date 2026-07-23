# Header Navigation Dynamic Architecture (Sixteen Theme)

## Overview

The header navigation in FixCity is **fully dynamic** — menu items are NOT hardcoded in Blade files. They are read from a centralized JSON configuration file, enabling non-technical administrators to manage navigation via Filament admin panel.

## Architecture Chain

```
<livewire:widget...>
  ↓
<x-section slug="header" />
  ↓
Modules/Cms/app/View/Components/Section.php
  ↓ (reads blocks via SectionModel::getBlocksBySlug('header'))
  ↓
Themes/Sixteen/resources/views/components/sections/header/v1.blade.php
  ↓ (reads header.json via TenantService::filePath())
  ↓
laravel/config/local/fixcity/database/content/sections/header.json
  ↓
nav-primary.blade.php + nav-secondary.blade.php (render $headerNavItems / $headerNavSecondary)
```

## Single Source of Truth (SSoT)

**File:** `laravel/config/local/fixcity/database/content/sections/header.json`

This JSON file contains ALL header navigation items with the following structure:

```json
{
  "version": "1.0.0",
  "sections": {
    "primary_nav": {
      "label": "Navigation principale",
      "topics_url": "/it/argomenti",
      "items": [
        {
          "id": "amministrazione",
          "label": "Amministrazione",
          "slug": "amministrazione",
          "url": "/it/amministrazione",
          "data_element": "management",
          "nav_group": "primary",
          "type": "link",
          "order": 10,
          "enabled": true,
          "visible": true,
          "active_patterns": ["it/amministrazione*"],
          "children": []
        }
      ]
    }
  }
}
```

## Key Design Decisions

### 1. Data-Driven Navigation
- **NO hardcoded links** in Blade files
- All menu items (Amministrazione, Novità, Servizi, Iscrizioni, etc.) come from JSON
- Filament Builder (`HeaderNavBlock`) will manage this JSON via admin panel

### 2. Dual Nav Groups
- `primary`: Main header links (Amministrazione, Novità, Servizi, Vivere il Comune)
- `secondary`: Secondary links (Iscrizioni, Estate in Città, Polizia Locale)

### 3. Active State Detection
The `$headerNavItemIsActive` closure in `v1.blade.php` checks:
- `active_patterns` array (regex patterns for route matching)
- Falls back to `url` field with `request()->is()`

### 4. Future Filament Integration
The `header.json` will be managed via:
- **Filament Builder**: `HeaderNavBlock` form with repeater fields
- **SushiToJsons trait**: SectionModel reads/writes JSON transparently
- **Admin UX**: Drag-and-drop reorder, toggle visibility, set active patterns

## Why This Architecture?

| Aspect | Philosophy |
|--------|-----------|
| **Zen** | One SSoT (header.json) → no duplication, no inconsistency |
| **Politics** | Non-technical admins can manage navigation without touching code |
| **Religion** | Filament + JSON = clean separation of content (JSON) and presentation (Blade) |
| **Vision** | Any navigation change (add/remove/reorder) happens in admin panel, never in code |
| **Purpose** | Scalability — adding "Estate in Città" link = JSON edit, not a developer task |

## Related Files

- **Config**: `laravel/config/local/fixcity/database/content/sections/header.json`
- **Component**: `laravel/Modules/Cms/app/View/Components/Section.php`
- **View**: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`
- **Partials**:
  - `laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-primary.blade.php`
  - `laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-secondary.blade.php`
- **Filament Block**: `laravel/Modules/Cms/app/Filament/Blocks/HeaderNavBlock.php`

## Filament Builder Integration (Future)

```php
// HeaderNavBlock.php - manages header.json via Filament Builder
class HeaderNavBlock extends Block
{
    public function getFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('url')->required(),
                    Select::make('nav_group')->options(['primary', 'secondary']),
                    Toggle::make('enabled'),
                    TextInput::make('order')->numeric(),
                ])
        ];
    }
}
```

## References

- Design Comuni: https://italia.github.io/design-comuni-pagine-statiche/
- Filament Builder: https://filamentphp.com/docs/5.x/forms/builder
- Current story: Story 8-107 (header-nav-items-from-json-filament-builder)
