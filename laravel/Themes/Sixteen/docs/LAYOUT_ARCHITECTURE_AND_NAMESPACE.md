# Layout Architecture & Component Namespace - Design Comuni

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** 🔴 **Critical Fix Required**
**Priority:** 🔴 **Blocker**

---

## 🎯 Scopo e Architettura

Questo documento spiega l'architettura dei layout e dei componenti nel tema Sixteen, gli errori da evitare, e le convenzioni da seguire.

---

## 📐 Layout Hierarchy

### Struttura Gerarchica

```
x-layouts.main (layouts/main.blade.php)
  │
  └─ HTML5 base structure
       ├─ <head> con @vite(['resources/css/app.css'], 'themes/Sixteen')
       └─ <body> con {{ $slot }}
  
x-layouts.app (layouts/app.blade.php)
  │
  ├─ Estende x-layouts.main
  ├─ Include elementi UNIVERSALI (NON page-specific):
  │    ├─ Skip Links (schiplink)
  │    ├─ Header (<x-section slug="header" />)
  │    └─ Footer (<x-section slug="footer" tpl="full" />)
  └─ <main id="main-container"> con {{ $slot }} per contenuto pagina

pages/tests/[slug].blade.php
  │
  ├─ Usa x-layouts.app (NON include header/footer/skiplink)
  └─ Include SOLO contenuto pagina-specifico:
       └─ <x-page side="content" :slug="$pageSlug" :data="$data" />
```

### Perché Questa Architettura?

#### 1. **DRY (Don't Repeat Yourself)**

❌ **SBAGLIATO:**
```blade
{{-- In OGNI pagina [slug].blade.php --}}
<div class="skiplink">
    <a href="#main-container">Vai ai contenuti</a>
    <a href="#footer">Vai al footer</a>
</div>
<x-section slug="header" />
<!-- contenuto pagina -->
<x-section slug="footer" tpl="full" />
```

✅ **CORRETTO:**
```blade
{{-- layouts/app.blade.php --}}
<x-layouts.main>
    <div class="skiplink">...</div>
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" tpl="full" />
</x-layouts.main>

{{-- pages/tests/[slug].blade.php --}}
<x-layouts.app>
    <x-page side="content" :slug="$pageSlug" :data="$data" />
</x-layouts.app>
```

**Motivo:** Skip links, header e footer sono **universali** - appaiono in TUTTE le pagine. Metterli in ogni pagina viola DRY.

#### 2. **Separation of Concerns**

| File | Responsabilità | Include |
|------|----------------|---------|
| `layouts/main.blade.php` | HTML5 structure, <head>, <body> | @vite, meta tags |
| `layouts/app.blade.php` | Frontend pubblico layout | Skip links, Header, Footer |
| `pages/tests/[slug].blade.php` | Contenuto pagina-specifico | SOLO <x-page> |
| `components/page.blade.php` | Renderizza JSON blocks | Block components |

#### 3. **KISS (Keep It Simple, Stupid)**

❌ **COMPLESSO:** Ogni pagina gestisce header/footer
✅ **SEMPLICE:** Layout centrale gestisce, pagina solo contenuto

---

## 🔧 Component Namespace

### Errore: `<x-sixteen::page>`

Ci sono **2 errori** nell'usare `<x-sixteen::page>`:

#### Errore 1: Namespace Hardcodato "sixteen"

❌ **SBAGLIATO:**
```blade
<x-sixteen::page />
```

**Problema:** "sixteen" è hardcodato. Se cambiamo tema (es. da "Sixteen" a "TwentyOne"), il componente si rompe.

**Perché è un problema:**
- Il progetto supporta **multi-tema**
- `pub_theme` è dinamico (configurato in `laravel/config/local/{tenant}/xra.php`)
- Hardcodare "sixteen" rende il codice **non portabile**

#### Errore 2: Componente Sbagliato

❌ **SBAGLIATO:**
```blade
<x-sixteen::page />
```

✅ **CORRETTO:**
```blade
<x-page side="content" :slug="$pageSlug" :data="$data" />
```

**Motivo:**
- `<x-page>` è un **componente anonimo Blade** (non Livewire)
- Si trova in `resources/views/components/page.blade.php`
- Non ha namespace perché è nel folder `components/` root

### Namespace System

#### Come Funziona il Namespace Dinamico

```php
// In Cms/View/Components/PubTheme.php (ipotetico)

class PubTheme extends Component
{
    public function render()
    {
        $theme = config('xra.pub_theme', 'Sixteen');
        $themeSnake = strtolower($theme); // es. 'sixteen'
        
        return view("pub_theme::components.{$this->componentName}");
    }
}
```

#### Componenti con Namespace

✅ **CORRETTO:**
```blade
{{-- Componenti theme-specific --}}
<x-pub_theme::components.blocks.hero.default :data="$data" />
<x-pub_theme::components.blocks.card.default :data="$data" />
<x-pub_theme::components.sections.header.default />
```

❌ **SBAGLIATO:**
```blade
<x-sixteen::blocks.hero />  <!-- Namespace hardcodato -->
<x-pub_theme::page />        <!-- Componente sbagliato -->
```

### Componenti Senza Namespace

Alcuni componenti sono **globali** (non theme-specific):

✅ **CORRETTO:**
```blade
<x-layouts.app />
<x-layouts.main />
<x-page side="content" :slug="$pageSlug" :data="$data" />
<x-section slug="header" />
```

---

## 🐛 Bug Attuale: ucfirst() Error

### Errore

```
TypeError: ucfirst(): Argument #1 ($string) must be of type string, array given
in Themes/Sixteen/resources/views/components/blocks/header/slim.blade.php:62
```

### Causa

A riga 62 di `slim.blade.php`:

```blade
@foreach($social as $network)
    @php
        $platform = is_array($network) ? ($network['platform'] ?? '') 
                     : (is_object($network) ? ($network->platform ?? '') 
                     : (string) $network);  // ← QUI L'ERRORE
    @endphp
    
    <a href="{{ $url }}" aria-label="{{ ucfirst($platform) }}">
        <!-- ... -->
    </a>
@endforeach
```

**Problema:** Se `$network` è un array, il codice estrae correttamente `$network['platform']`. Ma se `$platform` è a sua volta un array (es. `['platform' => 'facebook', 'url' => '...']`), allora `ucfirst($platform)` fallisce perché `ucfirst()` richiede una stringa.

### Fix

```blade
@foreach($social as $network)
    @php
        // Handle array, object, or string
        if (is_array($network)) {
            $platform = $network['platform'] ?? '';
            $url = $network['url'] ?? '#';
        } elseif (is_object($network)) {
            $platform = $network->platform ?? '';
            $url = $network->url ?? '#';
        } else {
            // Fallback: assume string (platform name)
            $platform = (string) $network;
            $url = '#';
        }
        
        // Ensure platform is a string for ucfirst()
        $platform = is_string($platform) ? $platform : '';
    @endphp
    
    @if($platform !== '')
        <li class="list-inline-item">
            <a href="{{ $url }}" class="text-link" aria-label="{{ ucfirst($platform) }}">
                <svg class="icon icon-sm">
                    <use href="#it-{{ strtolower($platform) }}"></use>
                </svg>
            </a>
        </li>
    @endif
@endforeach
```

---

## 📋 Checklist: Cosa Deve Essere in Ogni File

### layouts/main.blade.php

- [ ] HTML5 doctype
- [ ] <head> con meta tags
- [ ] @vite(['resources/css/app.css'], 'themes/Sixteen')
- [ ] <body> con {{ $slot }}
- [ ] @livewire, @filamentScripts, @filamentStyles
- [ ] Dark mode toggle script

### layouts/app.blade.php

- [ ] `<x-layouts.main>` wrapper
- [ ] Skip links (`.skiplink`)
- [ ] `<x-section slug="header" />`
- [ ] `<main id="main-container">` con {{ $slot }}
- [ ] `<x-section slug="footer" tpl="full" />`

### pages/tests/[slug].blade.php

- [ ] `<?php declare(strict_types=1);` con Folio setup
- [ ] Volt component con mount()
- [ ] `<x-layouts.app>` wrapper
- [ ] `@volt('tests.view')` con `<x-page side="content" ... />`
- [ ] **NON** includere: skip links, header, footer

### components/page.blade.php

- [ ] Loop sui JSON blocks
- [ ] Render dinamico: `<x-dynamic-component>`
- [ ] Gestione errori (block mancante)

---

## 🔗 Cross-References

### Internal Documents

- → [Layout Architecture](Themes/Sixteen/docs/layout-architecture.md) - Layout system overview
- → [Component Namespace Guide](Themes/Sixteen/docs/component-namespace.md) - Namespace conventions
- → [Folio + Volt Guide](Themes/Sixteen/docs/folio-volt-guide.md) - Routing guide
- → [BMad Architecture](_bmad-output/design-comuni-architecture.md) - System architecture

### External Resources

- → [Laravel Folio Documentation](https://laravel.com/docs/folio)
- → [Livewire Volt Documentation](https://livewire.laravel.com/docs/volt)
- → [Blade Components Documentation](https://laravel.com/docs/blade#components)

---

## 📝 Decision Log

### Architectural Decisions

| ID | Decision | Rationale | Consequences |
|----|----------|-----------|--------------|
| AD-1 | x-layouts.app estende x-layouts.main | DRY, separation of concerns | Header/footer in un solo posto |
| AD-2 | Skip links in app.blade.php | Universali, NON page-specific | Tutte le pagine hanno skip links |
| AD-3 | Usare <x-page> non <x-sixteen::page> | Namespace dinamico, portability | Tema cambiabile senza rompere codice |
| AD-4 | Usare <x-pub_theme::...> per blocchi | Theme-specific components | Blocchi diversi per tema |

---

## 🚨 Errori da NON Fare

### 1. ❌ Mettere Header/Footer in [slug].blade.php

```blade
{{-- SBAGLIATO: pages/tests/homepage.blade.php --}}
<x-layouts.app>
    <div class="skiplink">...</div>  <!-- GIA' IN app.blade.php! -->
    <x-section slug="header" />      <!-- GIA' IN app.blade.php! -->
    
    <x-page side="content" ... />
    
    <x-section slug="footer" />      <!-- GIA' IN app.blade.php! -->
</x-layouts.app>
```

✅ **CORRETTO:**
```blade
<x-layouts.app>
    <x-page side="content" ... />
</x-layouts.app>
```

### 2. ❌ Usare Namespace Hardcodato

```blade
<x-sixteen::page />  <!-- SBAGLIATO: "sixteen" è hardcodato -->
```

✅ **CORRETTO:**
```blade
<x-page />  <!-- Componente anonimo, nessun namespace -->
```

### 3. ❌ Non Usare Tipizzazione nei Componenti

```blade
{{-- SBAGLIATO --}}
@foreach($social as $network)
    {{ ucfirst($network) }}  <!-- Fallisce se $network è array -->
@endforeach
```

✅ **CORRETTO:**
```blade
@foreach($social as $network)
    @php
        $platform = is_array($network) ? $network['platform'] ?? '' : (string) $network;
        $platform = is_string($platform) ? $platform : '';
    @endphp
    {{ ucfirst($platform) }}
@endforeach
```

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Status:** 🔴 Critical Fix Required

🐮 **Document Complete - Fix in Progress!**
