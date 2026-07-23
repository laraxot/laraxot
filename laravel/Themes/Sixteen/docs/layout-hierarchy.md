# Layout Hierarchy - Sixteen Theme

**Type**: Architecture Documentation  
**Status**: ✅ Implemented  
**Last Update**: 2026-04-01  
**Related**: [[00-index]], [[components]], [[vite-configuration-rules]]

---

## 🎯 Overview

The Sixteen theme uses a **layered layout architecture** following **DRY (Don't Repeat Yourself)** and **KISS (Keep It Simple, Stupid)** principles.

### Core Principle
> **`x-layouts.main`** = Base HTML structure (NO semantics)  
> **`x-layouts.app`** = Public frontend wrapper (adds header/footer)  
> **Specialized layouts** = Specific use cases (auth, guest, navigation)

---

## 📐 Architecture Diagram

```
┌─────────────────────────────────────────────────────┐
│                  Laravel Blade View                  │
│  (e.g., pages/tests/[slug].blade.php)               │
└─────────────────────────────────────────────────────┘
                        ↓ uses
┌─────────────────────────────────────────────────────┐
│            <x-layouts.app>                           │
│  (components/layouts/app.blade.php)                 │
│  → Public frontend wrapper                           │
│  → Adds semantic structure                           │
└─────────────────────────────────────────────────────┘
                        ↓ extends
┌─────────────────────────────────────────────────────┐
│            <x-layouts.main>                          │
│  (components/layouts/main.blade.php)                │
│  → Base HTML structure                               │
│  → DOCTYPE, <head>, <body>, scripts                 │
│  → Vite assets (@vite)                              │
│  → Filament styles/scripts                          │
└─────────────────────────────────────────────────────┘
```

---

## 🏗️ Layout Layers

### 1. x-layouts.main (Base Layer)

**File**: `components/layouts/main.blade.php`

**Purpose**: Pure HTML structure without semantics

**Contains**:
- `<!DOCTYPE html>` declaration
- `<html>`, `<head>`, `<body>` tags
- Meta tags (charset, viewport, CSRF)
- Dark mode script
- **Vite assets**: `@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')`
- **Filament styles/scripts**: `@filamentStyles`, `@filamentScripts`
- Livewire components: `<livewire:toast />`, `@livewire('notifications')`

**DOES NOT contain**:
- Header
- Footer
- Navigation
- Any semantic structure

**Usage**:
```blade
<x-layouts.main>
    <!-- Your custom structure here -->
</x-layouts.main>
```

### 2. x-layouts.app (Public Frontend Layer)

**File**: `components/layouts/app.blade.php`

**Purpose**: Public frontend wrapper

**Contains**:
- Extends `x-layouts.main`
- Adds slot for content
- Ready for `<x-section slug="header" />` and `<x-section slug="footer" />`

**Usage**:
```blade
<x-layouts.app>
    <x-section slug="header" />
    <main id="main-container">
        {{ $slot }}
    </main>
    <x-section slug="footer" tpl="full" />
</x-layouts.app>
```

### 3. Specialized Layouts

#### x-layouts.guest
**File**: `components/layouts/guest.blade.php`  
**Purpose**: Authentication pages (login, register, password reset)  
**Extends**: `x-layouts.main`

#### x-layouts.auth
**File**: `components/layouts/auth.blade.php`  
**Purpose**: Protected dashboard pages  
**Extends**: `x-layouts.main`

#### x-layouts.navigation
**File**: `components/layouts/navigation.blade.php`  
**Purpose**: Navigation-specific layouts  
**Extends**: `x-layouts.main`

#### x-layouts.design-comuni
**File**: `components/layouts/design-comuni.blade.php`  
**Purpose**: Design Comuni template (DEPRECATED - use app.blade.php)  
**Extends**: `x-layouts.main`  
**Note**: Should be replaced with `<x-layouts.app>` + sections

---

## 📁 File Locations

### Components Directory (Blade Components)
```
laravel/Themes/Sixteen/resources/views/components/layouts/
├── app.blade.php              → Public frontend
├── main.blade.php             → Base structure
├── auth.blade.php             → Protected pages
├── guest.blade.php            → Authentication pages
├── navigation.blade.php       → Navigation layouts
└── design-comuni.blade.php    → Design Comuni (legacy)
```

### Layouts Directory (Traditional Laravel)
```
laravel/Themes/Sixteen/resources/views/layouts/
├── app.blade.php              → Legacy Bootstrap Italia
├── base.blade.php             → Base layout
├── auth.blade.php             → Auth pages
├── guest.blade.php            → Guest pages
├── navigation.blade.php       → Navigation
├── design-comuni.blade.php    → Design Comuni
├── bootstrap-italia-1to1.blade.php → Exact Bootstrap replica
└── bootstrap-italia-exact.blade.php → Bootstrap exact
```

**⚠️ Important**: Use `components/layouts/` for modern Blade components, NOT `layouts/` directory.

---

## 🔧 Usage Examples

### Example 1: Public Page with Sections
```blade
{{-- pages/tests/homepage.blade.php --}}
<x-layouts.app>
    <x-section slug="header" />
    <main id="main-container">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </main>
    <x-section slug="footer" tpl="full" />
</x-layouts.app>
```

### Example 2: Custom Structure
```blade
{{-- Custom page without sections --}}
<x-layouts.main>
    <div class="min-h-screen">
        <header class="bg-primary text-white">
            Custom Header
        </header>
        <main class="container mx-auto py-8">
            {{ $slot }}
        </main>
        <footer class="bg-gray-800 text-white">
            Custom Footer
        </footer>
    </div>
</x-layouts.main>
```

### Example 3: Authentication Page
```blade
{{-- pages/auth/login.blade.php --}}
<x-layouts.guest>
    <div class="min-h-screen flex items-center justify-center">
        <livewire:auth.login />
    </div>
</x-layouts.guest>
```

---

## 🎯 Why This Architecture?

### Problem: Duplication
Before (❌ WRONG):
```blade
{{-- Multiple layouts duplicating HTML structure --}}
<!-- layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css'])
</head>
<body>
    <header>...</header>
    {{ $slot }}
    <footer>...</footer>
</body>
</html>

<!-- layouts/auth.blade.php -->
<!DOCTYPE html>  <!-- DUPLICATE! -->
<html>
<head>
    @vite(['resources/css/app.css'])  <!-- DUPLICATE! -->
</head>
<body>
    {{ $slot }}  <!-- No header/footer -->
</body>
</html>
```

### Solution: Layered Architecture
After (✅ CORRECT):
```blade
{{-- components/layouts/main.blade.php --}}
<!DOCTYPE html>  <!-- ONCE -->
<html>
<head>
    @vite(['resources/css/app.css'], 'themes/Sixteen')  <!-- ONCE -->
    @filamentStyles  <!-- ONCE -->
</head>
<body>
    {{ $slot }}
    @filamentScripts  <!-- ONCE -->
</body>
</html>

{{-- components/layouts/app.blade.php --}}
<x-layouts.main>  <!-- EXTENDS base -->
    {{ $slot }}
</x-layouts.main>

{{-- components/layouts/guest.blade.php --}}
<x-layouts.main>  <!-- EXTENDS base -->
    {{ $slot }}
</x-layouts.main>
```

---

## 📋 Best Practices

### ✅ DO
1. **Use `x-layouts.main`** for base HTML structure
2. **Use `x-layouts.app`** for public frontend pages
3. **Use specialized layouts** for specific use cases (auth, guest)
4. **Keep main.blade.php minimal** - only essential structure
5. **Add semantics in wrapper layouts** (app, auth, etc.)
6. **Reference this doc** from component usage guides

### ❌ DON'T
1. **Don't duplicate HTML structure** in multiple files
2. **Don't put Vite assets in multiple layouts** - only in main.blade.php
3. **Don't use `layouts/` directory** - use `components/layouts/`
4. **Don't create new layouts without checking existing ones**
5. **Don't add header/footer to main.blade.php** - it's base structure only

---

## 🔗 Related Documentation

### Internal Links
- [[00-index]] - Main documentation index
- [[components]] - Component directory structure
- [[vite-configuration-rules]] - Asset loading configuration
- [[svg-icon-convention]] - Icon usage in layouts
- [[agid-layout-usage-rules]] - AGID-compliant layouts

### Module Links
- [[../../Modules/Cms/docs/layout-system]] - CMS layout integration
- [[../../Modules/UI/docs/components/layout]] - UI layout components

### External Links
- [Laravel Blade Components](https://laravel.com/docs/blade#components)
- [Filament Documentation](https://filamentphp.com/docs)
- [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/)

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-01 | 1.0 | Initial documentation | AI Agent Team |
| 2026-04-01 | 1.1 | Fix x-layouts.app to extend x-layouts.main | AI Agent Team |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
