# Layout Architecture - Sixteen Theme

**Date:** 2026-04-01  
**Status:** ✅ Complete  
**Type:** Architecture Decision Record (ADR)  

---

## Executive Summary

Il tema Sixteen utilizza un'**architettura a livelli** per i componenti layout Blade, con responsabilità ben definite e relazioni gerarchiche documentate tramite indici bidirezionali.

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│              LAYOUT HIERARCHY                            │
└─────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│  x-layouts.main                                          │
│  (Base HTML Structure)                                   │
│  - DOCTYPE, html, head, body                            │
│  - Meta tags, scripts, styles                           │
│  - Vite assets                                          │
│  - Filament scripts/styles                              │
│  - Dark mode logic                                      │
│  ⬇ EXTENDED BY                                          │
└──────────────────────────────────────────────────────────┘
         │
         ├─────────────────┬─────────────────┬──────────────┐
         │                 │                 │              │
         ▼                 ▼                 ▼              ▼
┌─────────────┐   ┌─────────────┐   ┌────────────┐  ┌────────────┐
│ x-layouts.  │   │ x-layouts.  │   │ x-layouts. │  │ x-layouts. │
│ app         │   │ guest       │   │ auth       │  │ admin      │
│ (Public)    │   │ (Auth)      │   │ (Dashboard)│  │ (Admin)    │
│             │   │             │   │            │  │            │
│ + Header    │   │ + Login     │   │ + Sidebar  │  │ + Filament │
│ + Footer    │   │ + Register  │   │ + Nav      │  │ + Resources│
│ + Sections  │   │ + Simple    │   │ + User     │  │ + Tables   │
│             │   │             │   │            │  │            │
│ Usage:      │   │ Usage:      │   │ Usage:     │  │ Usage:     │
│ Public pages│   │ Auth pages  │   │ Dashboard  │  │ Admin panel│
└─────────────┘   └─────────────┘   └────────────┘  └────────────┘
```

---

## Component Registry

### Base Layer

| Component | File | Responsibility | Extended By |
|-----------|------|---------------|-------------|
| **main** | `Themes/Sixteen/resources/views/components/layouts/main.blade.php` | HTML structure fondamentale | app, guest, auth, admin |

### Application Layer

| Component | File | Responsibility | Extends |
|-----------|------|---------------|---------|
| **app** | `Themes/Sixteen/resources/views/components/layouts/app.blade.php` | Public frontend wrapper | main |
| **guest** | `Themes/Sixteen/resources/views/components/layouts/guest.blade.php` | Authentication pages | main |
| **auth** | `Themes/Sixteen/resources/views/components/layouts/auth.blade.php` | Protected dashboard | main |
| **admin** | `Themes/Sixteen/resources/views/components/layouts/admin.blade.php` | Filament admin panel | main |

---

## Why app.blade.php MUST extend main.blade.php

### Problem Statement

**PRIMA:** `app.blade.php` era un componente standalone con HTML structure duplicata.

**DOPO:** `app.blade.php` estende `main.blade.php` per rispettare i principi DRY + KISS.

### Rationale

#### 1. **DRY (Don't Repeat Yourself)**

**❌ WRONG (Before):**
```blade
{{-- app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'], 'themes/Sixteen')
</head>
<body>
    {{ $slot }}
    @vite(['resources/js/app.js'], 'themes/Sixteen')
</body>
</html>
```

**✅ CORRECT (After):**
```blade
{{-- app.blade.php --}}
<x-layouts.main>
    {{ $slot }}
</x-layouts.main>
```

**Benefit:** HTML structure definita UNA sola volta in `main.blade.php`.

#### 2. **KISS (Keep It Simple, Stupid)**

**❌ COMPLEX:** Ogni layout gestisce scripts, styles, meta tags indipendentemente.

**✅ SIMPLE:** `main.blade.php` gestisce tutta la complessità, gli altri layout aggiungono solo semantica.

#### 3. **Single Source of Truth**

- **Dark mode logic:** Definita in `main.blade.php`, ereditata da tutti
- **Vite assets:** Configurati in `main.blade.php`, consistenti ovunque
- **Filament scripts:** Caricati una volta, disponibili per tutti i layout
- **Storage logic:** localStorage per dark mode centralizzato

#### 4. **Maintainability**

| Change | Before (N layouts) | After (1 layout) |
|--------|-------------------|------------------|
| Add new meta tag | Update 4 files | Update 1 file |
| Change Vite config | Update 4 files | Update 1 file |
| Fix dark mode bug | Fix 4 times | Fix 1 time |
| Add new script | Add to 4 files | Add to 1 file |

#### 5. **Consistency**

Tutte le pagine hanno:
- ✅ Stesso HTML structure
- ✅ Stessi meta tags
- ✅ Stessi scripts
- ✅ Stessi styles
- ✅ Stessa dark mode logic

---

## Component Details

### x-layouts.main

**File:** `Themes/Sixteen/resources/views/components/layouts/main.blade.php`

**Responsibility:** Struttura HTML fondamentale

**Features:**
- DOCTYPE declaration
- HTML lang attribute
- Head section (meta, styles, scripts)
- Body section
- Dark mode logic
- Vite assets
- Filament scripts/styles
- Toast & notifications

**Usage:**
```blade
<x-layouts.main>
    <h1>Custom Content</h1>
</x-layouts.main>
```

**Extends:** None (Base component)

**Extended By:**
- [`x-layouts.app`](#x-layoutsapp)
- [`x-layouts.guest`](#x-layoutsguest)
- [`x-layouts.auth`](#x-layoutsauth)
- [`x-layouts.admin`](#x-layoutsadmin)

---

### x-layouts.app {#x-layoutsapp}

**File:** `Themes/Sixteen/resources/views/components/layouts/app.blade.php`

**Responsibility:** Public frontend wrapper

**Features:**
- Header sections
- Footer sections
- CMS blocks integration
- Navigation pubblica

**Usage:**
```blade
<x-layouts.app title="Home">
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" />
</x-layouts.app>
```

**Extends:** [`x-layouts.main`](#x-layoutsmain)

**Extended By:** None (Leaf component)

**Pages Using:**
- Homepage
- CMS pages
- Blog listing
- Public profiles

---

### x-layouts.guest {#x-layoutsguest}

**File:** `Themes/Sixteen/resources/views/components/layouts/guest.blade.php`

**Responsibility:** Authentication pages wrapper

**Features:**
- Simple centered layout
- Auth card styling
- Minimal navigation
- Logo display

**Usage:**
```blade
<x-layouts.guest title="Login">
    <livewire:auth.login />
</x-layouts.guest>
```

**Extends:** [`x-layouts.main`](#x-layoutsmain)

**Extended By:** None (Leaf component)

**Pages Using:**
- Login
- Register
- Forgot password
- Reset password

---

### x-layouts.auth {#x-layoutsauth}

**File:** `Themes/Sixteen/resources/views/components/layouts/auth.blade.php`

**Responsibility:** Protected dashboard wrapper

**Features:**
- Sidebar navigation
- Top bar
- User menu
- Breadcrumbs

**Usage:**
```blade
<x-layouts.auth title="Dashboard">
    <livewire:dashboard.stats />
</x-layouts.auth>
```

**Extends:** [`x-layouts.main`](#x-layoutsmain)

**Extended By:** None (Leaf component)

**Pages Using:**
- User dashboard
- Profile settings
- User tickets
- User notifications

---

### x-layouts.admin {#x-layoutsadmin}

**File:** `Themes/Sixteen/resources/views/components/layouts/admin.blade.php`

**Responsibility:** Filament admin panel wrapper

**Features:**
- Filament navigation
- Filament sidebar
- Filament topbar
- Admin resources

**Usage:**
```blade
<x-layouts.admin title="Admin">
    <livewire:filament.resources />
</x-layouts.admin>
```

**Extends:** [`x-layouts.main`](#x-layoutsmain)

**Extended By:** None (Leaf component)

**Pages Using:**
- Filament resources
- Admin dashboard
- Admin settings
- Admin reports

---

## Cross-References

### Related Documents

- [UI Spec](_bmad-output/ui-spec.md) - Component library
- [Architecture](_bmad-output/architecture.md) - System architecture
- [Theme Documentation](Themes/Sixteen/docs/) - Theme-specific docs

### Related Components

| Component | Location | Used By |
|-----------|----------|---------|
| `x-section` | `Themes/Sixteen/resources/views/components/section.blade.php` | app layout |
| `x-header` | `Themes/Sixteen/resources/views/components/header.blade.php` | app layout |
| `x-footer` | `Themes/Sixteen/resources/views/components/footer.blade.php` | app layout |
| `x-nav` | `Themes/Sixteen/resources/views/components/nav.blade.php` | app, auth layouts |

### Related Modules

| Module | Connection | Description |
|--------|------------|-------------|
| **Cms** | Sections | CMS blocks usati in app layout |
| **Blog** | Public pages | Pagine blog usano app layout |
| **User** | Auth pages | Auth pages usano guest layout |
| **Filament** | Admin pages | Admin usa admin layout |

---

## Decision Log

| Date | Decision | Rationale | Status |
|------|----------|-----------|--------|
| 2026-04-01 | app.blade.php MUST extend main.blade.php | DRY, KISS, maintainability | ✅ Approved |
| 2026-04-01 | Document bidirectional links | Traceability, discoverability | ✅ Approved |
| 2026-04-01 | Create layout index | Navigation, overview | ✅ Approved |

---

## Glossary

| Term | Definition |
|------|------------|
| **Layout** | Componente Blade wrapper per pagine |
| **Component** | Blade component riutilizzabile |
| **Slot** | Content placeholder in Blade components |
| **DRY** | Don't Repeat Yourself |
| **KISS** | Keep It Simple, Stupid |
| **Leaf Component** | Component che non ha children |

---

## Appendix: File Locations

```
Themes/Sixteen/
└── resources/views/
    └── components/
        └── layouts/
            ├── main.blade.php      (Base - 100 righe)
            ├── app.blade.php       (Public - 30 righe)
            ├── guest.blade.php     (Auth - 25 righe)
            ├── auth.blade.php      (Dashboard - 40 righe)
            └── admin.blade.php     (Admin - 35 righe)
```

**Total:** 5 layout files, ~230 righe

---

**Document Status:** ✅ Complete  
**Next Review:** After major layout changes  
**Owner:** Frontend Team
