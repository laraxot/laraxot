# <body> NO CLASSES — Design Comuni Fidelity Rule

**Severity**: 🟡 HIGH  
**Scope**: All layout Blade templates (`resources/views/layouts/*.blade.php`, `resources/views/components/layouts/*.blade.php`)  
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/

---

## ❌ FORBIDDEN

```blade
{{-- NEVER add classes to <body> --}}
<body class="min-h-screen antialiased bg-white dark:bg-gradient-to-b dark:from-gray-950 dark:to-gray-900">
<body class="font-sans text-gray-900 antialiased">
<body class="h-full bg-gray-50">
<body class="offline-page">
```

---

## ✅ CORRECT

```blade
{{-- Design Comuni reference: simple <body> tag --}}
<body>
```

Reference from `view-source:https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html`:
```html
<!DOCTYPE html>
<html lang="it">
<head>...</head>
<body>
    <!-- content -->
</body>
</html>
```

---

## Why This Matters

### 1. Design Comuni Fidelity
The Italian Municipalities Design System uses semantic, minimal HTML. The `<body>` tag is a clean container — styling belongs to inner wrappers.

### 2. CSS Cascade Control
When you add classes to `<body>`, you create:
- **Specificity wars**: `body.dark .component` vs `.component`
- **Unexpected inheritance**: font-family, line-height cascade everywhere
- **Debug complexity**: Hard to trace where styles originate

### 3. Framework Agnostic
Design Comuni CSS works with the DOM structure, not framework-specific classes:
```css
/* Design Comuni native */
.it-header-wrapper { ... }
.it-header-slim-wrapper { ... }

/* NOT framework overrides */
.min-h-screen { ... } /* Tailwind specific */
.bg-gray-50 { ... }    /* Tailwind specific */
```

---

## Pattern: Styling Without Body Classes

### Pattern 1: ID on First Wrapper
```blade
<body>
    {{-- Page-specific styling via ID --}}
    <div id="offline-wrapper">
        <!-- content -->
    </div>
</body>

<style>
    #offline-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
```

### Pattern 2: Component-Scoped Classes
```blade
<body>
    <div class="page-container min-h-screen bg-white">
        <!-- content -->
    </div>
</body>
```

### Pattern 3: CSS Custom Properties (Design Comuni way)
```css
:root {
    --body-bg: #ffffff;
    --body-text: #333333;
}

body {
    background-color: var(--body-bg);
    color: var(--body-text);
}

/* Dark mode via data attribute, not class */
[data-theme="dark"] {
    --body-bg: #1a1a1a;
    --body-text: #ffffff;
}
```

---

## Detection

```bash
# Find all body tags with classes
grep -r "<body class=" laravel/Themes/Sixteen/resources/views/ --include="*.blade.php"

# Should return empty after fix
```

---

## Files Fixed

| File | Before | After |
|------|--------|-------|
| `layouts/main.blade.php` | `<body class="min-h-screen antialiased bg-white...">` | `<body>` |
| `components/layouts/guest.blade.php` | `<body class="font-sans text-gray-900 antialiased">` | `<body>` |
| `components/layouts/auth.blade.php` | `<body class="h-full bg-gray-50">` | `<body>` |
| `offline.blade.php` | `<body class="offline-page">` | `<body>` + `#offline-wrapper` |

---

## Related Rules

- **NO-INLINE-JS.md** — Inline styles also forbidden
- **ALPINE-JS-COMPONENTS.md** — Framework-agnostic JS patterns
- **DESIGN-COMUNI-COMPLIANCE.md** — Full compliance checklist

---

**Created**: 2026-05-26  
**Author**: Claude (Cascade)  
**Status**: ✅ Active Rule
