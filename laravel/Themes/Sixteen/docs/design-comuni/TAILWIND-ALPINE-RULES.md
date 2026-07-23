# TailwindCSS + Alpine.js Rules - Design Comuni Replication

**Created:** 2026-04-08
**Status:** Active
**Type:** Architectural Rule

---

## Core Principle

This project replicates Design Comuni pages using **TailwindCSS + Alpine.js ONLY**.
Bootstrap Italia is the visual reference, NOT a dependency.

---

## Rules

### Rule 1: NO Bootstrap CSS/JS

**FORBIDDEN:**
```blade
<script src="...bootstrap-italia.bundle.min.js"></script>
<link rel="stylesheet" href="...bootstrap-italia.min.css" />
@extends('pub_theme::layouts.bootstrap-italia')
```

**ALLOWED:**
```blade
@vite(['resources/css/app.css'], 'themes/Sixteen')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

**Note:** SVG sprite paths like `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` are acceptable - these are static icon files, not Bootstrap framework code.

### Rule 2: NO Bootstrap JS Attributes

Replace Bootstrap JS attributes with Alpine.js equivalents:

| Bootstrap JS | Alpine.js |
|-------------|-----------|
| `data-bs-toggle="dropdown"` | `x-data="{ open: false }" @click="open = !open"` |
| `data-bs-toggle="collapse"` | `x-data="{ open: false }" x-show="open"` |
| `data-bs-toggle="modal"` | `x-data="{ show: false }" @click="show = true"` |
| `data-bs-dismiss="modal"` | `@click="show = false"` |

### Rule 3: Dropdown Pattern

```blade
<div class="dropdown" x-data="{ open: false }">
    <button @click="open = !open" :aria-expanded="open.toString()">Toggle</button>
    <div class="dropdown-menu" x-show="open" @click.away="open = false" x-cloak>
        <!-- items -->
    </div>
</div>
```

### Rule 4: Test Pages Must Use `<x-layouts.app>`

**FORBIDDEN:**
```blade
<x-layouts.design-comuni>
```

**CORRECT:**
```blade
<x-layouts.app>
```

### Rule 5: All Text Must Be Translatable

**FORBIDDEN:**
```blade
<span>Condividi</span>
<small>Vedi azioni</small>
```

**CORRECT:**
```blade
<span>{{ __($ns.'.detail.share.label') }}</span>
<small>{{ __($ns.'.detail.actions.label') }}</small>
```

### Rule 6: Translation Key Pattern

Pattern: `<namespace>::<context>.<collection>.<key>.<type>`

| Bad | Good |
|-----|------|
| `SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE` | `fixcity::segnalazione.fields.title.label` |
| `fixcity::segnalazione.heading.title_label` | `fixcity::segnalazione.heading.title.label` |

---

## Why `design-comuni.blade.php` Layout Should Not Exist

1. Creates a parallel layout to `app.blade.php`
2. Breaks the single-layout architecture
3. Test pages should use `<x-layouts.app>` which includes header/footer via `<x-section>`
4. Maintaining two layouts is against DRY principle

---

## Cross-References

- → [HTML Structure Comparison](../../../docs/html-structure-comparison.md)
- → [Design Comuni Index](./00-index.md)
- → [Master Docs Index](../../../docs/MODULE_DOCS_INDEX.md)

---

**Last Updated:** 2026-04-08
