# Body Plain Rule - HTML Parity Policy

**Status**: ✅ Enforced  
**Last Updated**: 2026-04-13  
**Scope**: All theme layouts (Sixteen, etc.)

---

## Rule Statement

The `<body>` tag MUST remain plain without any classes or attributes:

```blade
{{-- ✅ CORRECT --}}
<body>
    {{ $slot }}
</body>

{{-- ❌ NEVER DO THIS --}}
<body @class(['page-tests-' . $slug])>
<body class="page-segnalazione-02-dati">
<body data-theme="dark">
```

---

## Rationale

### HTML Parity Requirement

To achieve visual parity with the Design Comuni reference pages, ALL page-specific styling MUST be handled via CSS scoped to:

```css
.page-content[data-slug="tests.segnalazione-02-dati"] {
  /* Page-specific styles here */
}
```

NOT via body classes.

### Why This Matters

1. **Clean Separation**: HTML structure remains constant, visual parity achieved through CSS
2. **Maintainability**: No conditional body logic in layout files
3. **Parity Verification**: Easier to compare HTML structure when body is plain
4. **Theme Consistency**: All pages use the same base HTML structure

---

## Implementation

### File: `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php`

```blade
{{-- Line 46 — Body tag (NO classes, NO attributes) --}}
<body>
    {{ $slot }}
    {{-- ... --}}
</body>
```

### Visual Parity via CSS Wrapper

All page-specific styling is scoped to the wrapper:

```css
/* Example: segnalazione-02-dati page */
.page-content[data-slug="tests.segnalazione-02-dati"] {
  .steppers-header { /* ... */ }
  .cmp-card { /* ... */ }
}
```

---

## Related Rules

- **[Tailwind Only Build](./TAILWIND_ONLY_BUILD_COMPLETE.md)** — No Bootstrap Italia CSS
- **[Design Comuni Replication Rules](./DESIGN_COMUNI_RULES.md)** — Overall parity guidelines
- **[Translation Format](../translation-format.md)** — No hardcoded Italian in blades

---

## Verification Checklist

Before committing any layout changes:

- [ ] `<body>` has NO `@class()` directive
- [ ] `<body>` has NO conditional attributes
- [ ] `<body>` has NO data attributes
- [ ] Page-specific CSS is scoped to `.page-content[data-slug="..."]`
- [ ] Visual parity verified via screenshots (mobile, tablet, desktop)

---

## Change History

| Date | Change | Commit |
|------|--------|--------|
| 2026-04-13 | Documented body plain rule | `new` |
| 2026-04-12 | Verified body tag plain in main.blade.php | `8f547e01d` |

---

## 🔗 Cross-References

- → [Stepper Component](./stepper-component.md) — Uses body plain rule
- → [segnalazione-02-dati](./segnalazione-02-dati.md) — Page implementation example
- → [main.blade.php](../../resources/views/components/layouts/main.blade.php) — Layout file
