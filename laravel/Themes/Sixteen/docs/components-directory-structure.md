# Components Directory Structure

**Type**: Architecture Documentation  
**Status**: ✅ Documented  
**Last Update**: 2026-04-01  
**Related**: [[00-index]], [[layout-hierarchy]], [[README]]

---

## 📁 Directory Overview

```
laravel/Themes/Sixteen/resources/views/components/
├── layouts/                        # 🏗️ Layout Components
│   ├── main.blade.php              # Base HTML structure
│   ├── app.blade.php               # Public frontend (extends main)
│   ├── auth.blade.php              # Protected pages
│   ├── guest.blade.php             # Authentication pages
│   └── navigation.blade.php        # Navigation layouts
├── sections/                       # 📐 Section Components
│   ├── header.blade.php            # Header section
│   ├── footer.blade.php            # Footer section
│   └── section-border.blade.php    # Section divider
├── blocks/                         # 🧱 Content Blocks
│   ├── hero/                       # Hero sections
│   ├── topics/                     # Topic grids/lists
│   ├── events/                     # Event calendars
│   ├── news/                       # News cards
│   ├── governance/                 # Governance cards
│   ├── feedback/                   # Rating forms
│   ├── contact/                    # Contact info
│   ├── links/                      # Link lists
│   ├── navigation/                 # Headers, footers, breadcrumbs
│   └── forms/                      # Form elements
├── bootstrap-italia/               # 🇮🇹 AGID Components
├── accessibility/                  # ♿ Accessibility
├── navigation/                     # 🧭 Navigation
├── forms/                          # 📝 Forms
├── ui/                             # 🎨 UI Elements
└── utilities/                      # 🔧 Utilities
```

---

## 🏗️ Layout Components

### Location
`components/layouts/`

### Components

| Component | File | Description | Extends |
|-----------|------|-------------|---------|
| `<x-layouts.main>` | `main.blade.php` | Base HTML structure | - |
| `<x-layouts.app>` | `app.blade.php` | Public frontend | `main` |
| `<x-layouts.auth>` | `auth.blade.php` | Protected pages | `main` |
| `<x-layouts.guest>` | `guest.blade.php` | Auth pages | `main` |
| `<x-layouts.navigation>` | `navigation.blade.php` | Navigation | `main` |

### Usage

```blade
{{-- Public page with header/footer --}}
<x-layouts.app>
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" />
</x-layouts.app>

{{-- Authentication page --}}
<x-layouts.guest>
    <livewire:auth.login />
</x-layouts.guest>

{{-- Custom structure --}}
<x-layouts.main>
    <!-- Your custom HTML here -->
</x-layouts.main>
```

### Documentation
- **[Layout Hierarchy](./layout-hierarchy.md)** - Complete architecture guide
- **[00-index](./00-index.md)** - Main documentation index

---

## 📐 Section Components

### Location
`components/sections/`

### Purpose
Sections are **semantic wrappers** for major page areas (header, footer, etc.)

### Components

| Component | File | Description |
|-----------|------|-------------|
| `<x-section slug="header">` | `header.blade.php` | Main header |
| `<x-section slug="footer">` | `footer.blade.php` | Main footer |
| `<x-section slug="footer" tpl="slim">` | `footer.blade.php` | Slim footer variant |

### Usage

```blade
{{-- Header section --}}
<x-section slug="header" />

{{-- Full footer --}}
<x-section slug="footer" tpl="full" />

{{-- Slim footer --}}
<x-section slug="footer" tpl="slim" />
```

### Related
- **[Blocks System](./blocks-system.md)** - Content blocks architecture
- **[Design Comuni Implementation](./design-comuni-implementation.md)** - Section usage

---

## 🧱 Content Blocks

### Location
`components/blocks/`

### Organization
Blocks are organized by **TYPE**, not by page:

```
blocks/
├── hero/           # Type: hero
│   ├── homepage.blade.php
│   ├── default.blade.php
│   └── argomenti.blade.php
├── topics/         # Type: topics
│   ├── grid.blade.php
│   ├── featured.blade.php
│   └── highlight.blade.php
├── events/         # Type: events
│   └── calendar.blade.php
└── ...
```

### Block Types

| Type | Directory | Description | Example View Path |
|------|-----------|-------------|-------------------|
| `hero` | `blocks/hero/` | Hero sections | `pub_theme::components.blocks.hero.homepage` |
| `topics` | `blocks/topics/` | Topic grids/lists | `pub_theme::components.blocks.topics.grid` |
| `events` | `blocks/events/` | Event calendars | `pub_theme::components.blocks.events.calendar` |
| `news` | `blocks/news/` | News cards | `pub_theme::components.blocks.news-card.standard` |
| `governance` | `blocks/governance/` | Governance cards | `pub_theme::components.blocks.governance.cards` |
| `feedback` | `blocks/feedback/` | Rating forms | `pub_theme::components.blocks.feedback.rating` |
| `contact` | `blocks/contact/` | Contact info | `pub_theme::components.blocks.contact.info` |
| `links` | `blocks/links/` | Link lists | `pub_theme::components.blocks.links.useful` |
| `navigation` | `blocks/navigation/` | Headers, footers | `pub_theme::components.blocks.navigation.header-main` |
| `forms` | `blocks/forms/` | Form elements | `pub_theme::components.blocks.forms.select` |

### Naming Convention

**Format**: `pub_theme::components.blocks.<type>.<variant>`

**Examples**:
- ✅ `pub_theme::components.blocks.hero.homepage`
- ✅ `pub_theme::components.blocks.topics.grid`
- ❌ `pub_theme::components.blocks.tests.argomenti.topics-grid` (tests.argomenti is NOT a type)

### JSON Structure

```json
{
  "slug": "tests.homepage",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage",
          "title": "Nome del Comune"
        }
      },
      {
        "type": "topics",
        "data": {
          "view": "pub_theme::components.blocks.topics.grid",
          "items": [...]
        }
      }
    ]
  }
}
```

### Documentation
- **[Blocks System](./blocks-system.md)** - Complete blocks architecture
- **[Design Comuni Plan](.planning/DESIGN_COMUNI_PLAN.md)** - Block types reference

---

## 🇮🇹 AGID Components (Bootstrap Italia)

### Location
`components/bootstrap-italia/`

### Purpose
Replicate Bootstrap Italia components using Tailwind CSS

### Components
- Alerts, Badges, Breadcrumbs
- Buttons, Cards, Carousels
- Footer, Header (slim, main)
- Forms (select, radio, upload)
- Navigation (megamenu, sidebar)
- Skiplinks, Tabs, Toggles

### Documentation
- **[AGID Checklist](./agid-checklist.md)** - Compliance verification
- **[Components Status](./components-status.md)** - Implementation status

---

## ♿ Accessibility Components

### Location
`components/accessibility/`

### Features
- Skip links
- Focus management
- ARIA attributes
- Keyboard navigation

### Documentation
- **[Accessibility Guide](./accessibility.md)** - WCAG 2.1 AA compliance

---

## 🧭 Navigation Components

### Location
`components/navigation/`

### Components
- Menus (primary, secondary)
- Breadcrumbs
- Pagination
- Tabs

---

## 📝 Form Components

### Location
`components/forms/`

### Components
- Input fields
- Select dropdowns
- Checkboxes, Radios
- File uploads
- Validation errors

---

## 🎨 UI Elements

### Location
`components/ui/`

### Components
- Buttons
- Cards
- Badges
- Modals
- Tooltips

---

## 🔧 Utilities

### Location
`components/utilities/`

### Components
- Icons
- Spacing helpers
- Visibility controls
- Responsive utilities

---

## 📋 Best Practices

### ✅ DO
1. **Use `components/layouts/`** for layout components
2. **Organize blocks by TYPE** (hero, topics, events), not by page
3. **Use `<x-section>`** for header/footer
4. **Reference this index** from usage guides
5. **Create bidirectional links** between related docs

### ❌ DON'T
1. **Don't create page-specific blocks** (e.g., `blocks/tests/homepage/...`)
2. **Don't duplicate HTML structure** in layouts
3. **Don't use `layouts/` directory** - use `components/layouts/`
4. **Don't forget to update this index** when adding new components

---

## 🔗 Related Documentation

### Internal
- [[00-index]] - Main documentation index
- [[layout-hierarchy]] - Layout architecture
- [[blocks-system]] - Blocks architecture
- [[README]] - Theme overview

### Module
- [[../../Modules/Cms/docs/components]] - CMS components
- [[../../Modules/UI/docs/components]] - UI components

### External
- [Laravel Blade Components](https://laravel.com/docs/blade#components)
- [Filament Components](https://filamentphp.com/docs/components)

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
