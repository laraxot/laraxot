# Filament v5 Hybrid Pattern - Theme Reference

**Status**: Reference  
**Theme**: Sixteen  
**Pattern**: Filament v5 + XotBase Hybrid  
**Related**: TicketInfolist, TicketForm  
**Last Updated**: 2026-05-05

## Overview

This document provides theme-level reference for the **Filament v5 Hybrid Pattern** implemented in the Fixcity module. While themes don't directly implement Filament Resources, understanding this pattern is essential for:

- Theme developers creating custom views that integrate with Filament components
- Debugging styling issues in admin infolists and forms
- Maintaining consistency between theme and admin UI patterns

## The Hybrid Pattern

### Core Concept

The hybrid pattern merges **Filament v5's `configure()` API** with **Laraxot's `XotBase*` extensions**:

```php
// Dual API support - both methods work
class TicketInfolist extends XotBaseResourceInfolist
{
    // NEW: Filament v5 fluent API
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([...]);
    }
    
    // LEGACY: Array API (backward compatible)
    public static function getInfolistSchema(): array
    {
        return [...];
    }
}
```

### Why This Matters for Themes

1. **Consistent Styling**: Theme CSS should align with Filament v5 component structure
2. **Infolist Display**: Frontoffice views may mirror admin infolist layouts
3. **Component Reuse**: Theme components can follow the same dual-API pattern

## Filament v5 vs Theme Components

| Layer | Filament v5 | Theme Sixteen |
|-------|-------------|---------------|
| **Admin** | `TicketInfolist::configure()` | N/A (admin uses Filament) |
| **Admin** | `TicketForm::getFormSchema()` | N/A (admin uses Filament) |
| **Frontoffice** | N/A | `TicketWizard` (Livewire) |
| **Display** | `TextEntry`, `ImageEntry` | Blade components with Alpine.js |

### Theme Equivalents

| Filament Component | Theme Equivalent |
|-------------------|------------------|
| `Tabs` | `<x-ui.tabs>` or Alpine.js tabs |
| `Section` | `<x-ui.card>` or `<section>` blocks |
| `TextEntry` | Blade `{{ $ticket->name }}` |
| `ImageEntry` | `<img>` or `<x-media.image>` |
| Badge | Tailwind badge classes |

## Key Files Reference

### Module (Source of Truth) - COMPLETE

```
Modules/Fixcity/app/Filament/Resources/TicketResource/
├── Schemas/
│   ├── TicketForm.php          # ✅ Form schema (wizard-based)
│   ├── TicketInfolist.php      # ✅ Infolist (Tabs: Overview + Location)
│   └── TicketWizard.php         # (optional) Wizard configuration
├── Tables/
│   └── TicketsTable.php         # ✅ Table columns/filters (NEW)
├── Pages/
│   ├── ListTickets.php          # ✅ Uses TicketsTable
│   ├── ViewTicket.php           # ✅ Uses TicketInfolist
│   ├── EditTicket.php           # ✅ Uses TicketForm
│   └── CreateTicket.php         # ✅ Uses TicketForm
└── TicketResource.php           # ✅ Resource configuration
```

### Schema Stack Completeness

| Component | Status | Pattern | Location |
|-----------|--------|---------|----------|
| **TicketForm** | ✅ | Wizard + Steps | `Schemas/TicketForm.php` |
| **TicketInfolist** | ✅ | Tabs (Overview + Location) | `Schemas/TicketInfolist.php` |
| **TicketsTable** | ✅ | Columns + Filters | `Tables/TicketsTable.php` |

### Theme (Presentation Layer)

```
Themes/Sixteen/resources/views/
├── components/
│   └── wizard/                  # Wizard theme components
├── pages/
│   └── segnalazione/
│       ├── create.blade.php     # Frontoffice ticket creation
│       └── show.blade.php       # Ticket detail display (mirror infolist)
└── layouts/
    └── app.blade.php            # Layout with header/nav
```

## TicketInfolist Structure (for Theme Reference)

### Tab: Overview

Displays:
- Basic info (ID, slug, name)
- Status badges
- Priority badges
- Owner/Assignee names
- Timestamps (created/updated)
- Content (prose formatted)

### Tab: Location

Displays:
- Full address
- Coordinates (lat/lng)
- Map image/entry
- Associated media images

### Theme Parity Checklist

When creating frontoffice ticket detail views:

- [ ] Match infolist tab structure (Overview + Location)
- [ ] Use same field order as admin infolist
- [ ] Display badges for status/priority with same colors
- [ ] Show placeholder '-' for null values
- [ ] Format dates consistently with admin
- [ ] Use prose styling for content field

## Translation Keys (Theme-Module Alignment)

Admin infolist uses these keys (from `Modules/Fixcity/lang/it/ticket.php`):

```php
'ticket' => [
    'infolist' => [
        'tabs' => [
            'overview' => ['label' => 'Panoramica'],
            'location' => ['label' => 'Posizione'],
        ],
        'fields' => [
            'id' => ['label' => 'ID'],
            'name' => ['label' => 'Titolo'],
            'status' => ['label' => 'Stato'],
            // ...
        ],
    ],
]
```

Theme should reuse these keys or provide theme-specific overrides in:
```
Themes/Sixteen/lang/it/ticket.php
```

## References

### Internal
- **Module Implementation**: `Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketInfolist.php`
- **Pattern Guide**: `Modules/Xot/docs/wiki/concepts/filament-v5-hybrid-pattern.md`
- **Story 8-91**: `.planning/stories/8-91-filament-v5-schemas-structure-refactor.story.md`

### External
- **Filament v5 Demo**: https://github.com/filamentphp/demo/tree/5.x/app/Filament/Resources
- **Filament Docs**: https://filamentphp.com/docs/5.x/schemas/infolists

## Theme Development Guidelines

### DO
- Study TicketInfolist structure before building frontoffice views
- Use same translation keys as admin (DRY)
- Mirror tab structure (Overview + Location)
- Apply consistent badge colors (status/priority)

### DON'T
- Hardcode labels that differ from admin
- Skip fields present in admin infolist
- Use different date formats than admin
- Break tab organization (keep Overview + Location)

---

*Theme reference for Filament v5 Hybrid Pattern. Implementation lives in Fixcity module.*
