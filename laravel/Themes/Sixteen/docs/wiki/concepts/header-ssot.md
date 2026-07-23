# Header SSoT (Single Source of Truth) - Sixteen Theme

## Critical Rule

**L'header del tema Sixteen è implementato in UN SOLO FILE:**

```
resources/views/components/sections/header/v1.blade.php
```

Questo file è il **Single Source of Truth (SSoT)** per l'header.

---

## Architecture

### File Structure

```
resources/views/components/sections/header/
├── v1.blade.php              ✅ SSoT - Solo questo file
└── language-switcher.blade.php ✅ Include permesso (incluso da v1)
```

### What Lives in v1.blade.php

- **Slim Header**: Language dropdown + User area
- **Center Header**: Logo, search, main branding
- **Navbar**: Navigation menu
- **Guest State**: Login button
- **Authenticated State**: User dropdown with name + avatar

### What Does NOT Live Elsewhere

❌ Vietato:
- `components/header/guest.blade.php`
- `components/header/authenticated.blade.php`
- `components/header/base.blade.php` (dispatcher)
- `components/header/slim.blade.php`

---

## Implementation Pattern

### PHP Logic

```blade
@php
    // Resolve user display name
    $headerUserDisplayName = auth()->check() 
        ? (auth()->user()->full_name ?? auth()->user()->name ?? auth()->user()->email)
        : null;
    
    // Resolve avatar
    $headerAvatarUrl = auth()->check() && auth()->user()->getFirstMediaUrl('avatars')
        ? auth()->user()->getFirstMediaUrl('avatars')
        : null;
    
    $headerUserInitial = strtoupper(substr($headerUserDisplayName ?? '', 0, 1));
@endphp
```

### Blade Structure

```blade
<header class="it-header-wrapper">
    {{-- Slim Wrapper --}}
    <div class="it-header-slim-wrapper" style="background-color: #0066CC;">
        <div class="container">
            <div class="it-header-slim-wrapper-content">
                {{-- Region name --}}
                <a class="navbar-brand text-white">Nome Regione</a>
                
                <div class="it-header-slim-right-zone">
                    {{-- Language dropdown --}}
                    @include('pub_theme::components.sections.header.partials.language-switcher')
                    
                    {{-- User area --}}
                    @guest
                        {{-- Login button --}}
                    @else
                        {{-- User dropdown --}}
                    @endguest
                </div>
            </div>
        </div>
    </div>
    
    {{-- Center Wrapper --}}
    <div class="it-header-center-wrapper">
        {{-- Logo, search --}}
    </div>
    
    {{-- Navbar Wrapper --}}
    <div class="it-header-navbar-wrapper">
        {{-- Navigation --}}
    </div>
</header>
```

---

## Design System Compliance

### Colors

| Element | Color | Token/Value |
|---------|-------|-------------|
| Slim header background | Primary blue | `#0066CC` |
| Slim header text | White | `#FFFFFF` |
| User dropdown background | White | `#FFFFFF` |
| User dropdown text | Dark | `#212529` |

### Typography

- **User name**: Visibile come "Mario Rossi" (design reference)
- **Font**: Bootstrap Italia defaults

### Icons

- Expand: `it-expand`
- Collapse: `it-collapse`
- User: `it-user`

---

## Runtime Compatibility

### Livewire + Filament Pages

**Problema**: `data-bs-toggle="dropdown"` non funziona senza inizializzazione Bootstrap JS.

**Soluzione**: Custom runtime in `resources/js/app.js`:

```javascript
document.addEventListener('DOMContentLoaded', () => {
    initBootstrapDropdowns();
});

function initBootstrapDropdowns() {
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        if (!el._initialized) {
            new bootstrap.Dropdown(el);
            el._initialized = true;
        }
    });
}
```

---

## Testing

### Checklist

- [ ] `v1.blade.php` esiste in `components/sections/header/`
- [ ] Nessun altro file header esiste
- [ ] Language dropdown funziona
- [ ] User dropdown funziona (authenticated)
- [ ] Login button visibile (guest)
- [ ] Slim header sfondo blu #0066CC
- [ ] Nome utente completo visibile
- [ ] Test su `tests/segnalazione-crea`

---

## Documentation

### Links

- **Story**: `.planning/stories/8-35-header-sixteen-ssot.story.md`
- **Rule**: `.windsurf/rules/header-sixteen-ssot.mdc`
- **Wiki Root**: `docs/wiki/index.md`
- **Design**: https://italia.github.io/design-comuni-pagine-statiche/

---

## Summary

**ONE FILE, ONE TRUTH.**

```
v1.blade.php = Single Source of Truth for Sixteen Header
```

Non duplicare. Non creare alternative. Mantieni tutto in v1.
