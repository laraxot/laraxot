# UI Module: Design System & Components

> **Reusable Form/Table Components** — Filament-integrated, consistent styling, multi-module usage.

---

## Zen

**"Component library is DRY. No copy-paste UI logic."**

UI is not a domain; it's a toolkit for all modules.

---

## Quick

### Models (4)
- **Category** — Component/asset taxonomy
- **Collection** — Grouped components
- **FieldOption** — Enum-like picker (for form/table dropdowns)

### Components (26 total)

**Forms (15)**
- AddressField, Children, EnumSelect, RangeInput, SectionCollapsible, TagsInput, etc.

**Tables (11)**
- AddressColumn, DummyActionsColumn, GroupColumn, KeyValueColumn, SlugColumn, etc.

### Pattern
```
Module needs form field for address
  ↓
Import UI.AddressField
  ↓
Use in Filament::form()
  ↓
Automatic i18n, validation, styling
```

### Traits (1)
- **TableLayoutTrait** — Grid/list layout switching

---

## Integration

- **Every module** uses UI components
- **Filament** abstraction layer
- **Styling** centralized (Tailwind, dark mode)

---

## Best/Bad

✓ Component reusability (no duplication)
✓ Consistent UX across modules
✓ Type-safe prop passing
❌ Custom form fields (use UI components)

---

## Parity: Forms ↔ Tables

**Rule**: For each form component, there exists a table column counterpart.
- `AddressField` ↔ `AddressColumn`
- `EnumSelect` ↔ `EnumColumn`

Tracked in: `docs/form-column-parity.md`

---

## Roadmap

- Component documentation (Storybook-like)
- Dark mode variants
- Accessibility audit (WCAG AA)
- Responsive breakpoint testing

---

```
┌──────────────────────────┐
│ UI (Design System)       │
├──────────────────────────┤
│ Purpose: Component lib   │
│ Models: 4                │
│ Forms: 15                │
│ Tables: 11               │
│ Status: Stable           │
│ Reverse Deps: All        │
└──────────────────────────┘
```

---

- **Generated**: 2026-09-06

