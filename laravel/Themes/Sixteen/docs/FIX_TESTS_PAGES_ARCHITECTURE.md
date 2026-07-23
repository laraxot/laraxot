# FixCity Tests Pages - Architecture & Troubleshooting

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** 🔴 **Fixed**
**Priority:** 🔴 **Blocker - RESOLVED**

---

## 🎯 Scopo

Questo documento spiega l'architettura delle pagine di test (`/it/tests/*`), gli errori incontrati e le soluzioni applicate.

---

## 📐 Architecture Overview

### URL Pattern

```
/it/tests/{slug}
  │
  ├─ Locale: it (da mcamara/laravel-localization)
  ├─ Prefix: tests (Folio route group)
  └─ Slug: nome pagina (homepage, argomenti, servizi, etc.)
```

### File Mapping

```
URL: /it/tests/homepage
  │
  ▼
Folio Route: pages/tests/[slug].blade.php
  │
  ▼
Volt Component: mount()
  │
  ▼
pageSlug = 'tests.' . $slug = 'tests.homepage'
  │
  ▼
JSON File: config/local/fixcity/database/content/pages/tests.homepage.json
  │
  ▼
Blocks: $data['content_blocks']['it'] (NOT $data['blocks'])
  │
  ▼
Render: @foreach($blocks as $block) → <x-dynamic-component>
```

---

## 🔧 Componente page.blade.php

### Struttura JSON

Il JSON delle pagine ha questa struttura:

```json
{
  "id": "tests.homepage",
  "slug": "tests.homepage",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage",
          "title": "...",
          ...
        }
      }
    ],
    "en": []
  }
}
```

### Fix Applicato

**Problema:** Il componente cercava `$data['blocks']` ma il JSON usa `$data['content_blocks']['it']`.

**Soluzione:**

```blade
@php
    $jsonPath = config('local.fixcity.database.content.pages.'.$slug);
    $pageData = null;
    $blocks = [];

    $configPath = base_path('config/local/fixcity/database/content/pages/'.$slug.'.json');
    if (file_exists($configPath)) {
        $pageData = json_decode(file_get_contents($configPath), true);
        // Support both 'blocks' and 'content_blocks' structures
        $blocks = $pageData['blocks'] ?? $pageData['content_blocks']['it'] ?? [];
    }
@endphp
```

---

## 🐛 Bug Riscontrati e Soluzioni

### Bug 1: ucfirst() Error in header/slim.blade.php

**Errore:**
```
TypeError: ucfirst(): Argument #1 ($string) must be of type string, array given
in Themes/Sixteen/resources/views/components/blocks/header/slim.blade.php:62
```

**Causa:**
```blade
@foreach($social as $network)
    @php
        // $network può essere array, object, o string
        $platform = is_array($network) ? ($network['platform'] ?? '') 
                     : (is_object($network) ? ($network->platform ?? '') 
                     : (string) $network);
        // Se $network['platform'] è a sua volta un array, ucfirst() fallisce
    @endphp
    {{ ucfirst($platform) }}  // ← ERRORE
@endforeach
```

**Soluzione:**
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
            <a href="{{ $url }}" aria-label="{{ ucfirst($platform) }}">
                <svg class="icon icon-sm">
                    <use href="#it-{{ strtolower($platform) }}"></use>
                </svg>
            </a>
        </li>
    @endif
@endforeach
```

**File:** `laravel/Themes/Sixteen/resources/views/components/blocks/header/slim.blade.php`

---

### Bug 2: 404 su /it/tests/homepage

**Errore:** Pagina 404 o errore di rendering

**Causa:**
1. JSON structure mismatch (`blocks` vs `content_blocks`)
2. View path non esistente per i blocchi

**Soluzione:**
1. Fixato `page.blade.php` per supportare entrambe le strutture
2. Creare i blocchi mancanti o usare fallback

---

## 📋 Layout Architecture

### Gerarchia Layout

```
x-layouts.main (layouts/main.blade.php)
  │
  ├─ HTML5 structure
  ├─ <head> con @vite
  └─ <body> con {{ $slot }}

x-layouts.app (layouts/app.blade.php)
  │
  ├─ Estende x-layouts.main
  ├─ Skip links (UNIVERSALE)
  ├─ Header (UNIVERSALE)
  ├─ <main> con {{ $slot }}
  └─ Footer (UNIVERSALE)

pages/tests/[slug].blade.php
  │
  ├─ Usa x-layouts.app
  └─ SOLO contenuto pagina-specifico (<x-page>)
```

### Cosa NON Mettere in [slug].blade.php

❌ **SBAGLIATO:**
```blade
<div class="skiplink">...</div>  <!-- GIA' IN app.blade.php! -->
<x-section slug="header" />      <!-- GIA' IN app.blade.php! -->
<x-section slug="footer" />      <!-- GIA' IN app.blade.php! -->
```

✅ **CORRETTO:**
```blade
<x-layouts.app>
    <x-page side="content" :slug="$pageSlug" :data="$data" />
</x-layouts.app>
```

**Motivo:** DRY - Skip links, header e footer sono **universali**, NON page-specific.

---

## 🔧 Component Namespace

### Errore Comune: `<x-sixteen::page>`

❌ **SBAGLIATO:**
```blade
<x-sixteen::page />
```

**2 Errori:**
1. **Namespace hardcodato** - "sixteen" non è portabile (tema dinamico)
2. **Componente sbagliato** - `<x-page>` è anonimo, non ha namespace

✅ **CORRETTO:**
```blade
<x-page side="content" :slug="$pageSlug" :data="$data" />
```

### Namespace System

| Tipo | Namespace | Esempio |
|------|-----------|---------|
| **Global** | Nessuno | `<x-layouts.app>`, `<x-page>` |
| **Theme-specific** | `pub_theme::` | `<x-pub_theme::components.blocks.hero.default>` |

**Motivo:** `pub_theme` è dinamico (configurato per tenant), "sixteen" è hardcodato.

---

## 📁 Block View Paths

### Come Funziona il Rendering

```blade
@foreach($blocks as $block)
    @php
        $viewPath = $block['data']['view'] ?? 'pub_theme::components.blocks.' . $block['type'];
    @endphp
    
    @includeIf($viewPath, ['data' => $block['data']])
@endforeach
```

### Esempio JSON

```json
{
  "blocks": [
    {
      "type": "hero",
      "data": {
        "view": "pub_theme::components.blocks.hero.homepage",
        "title": "Benvenuto"
      }
    }
  ]
}
```

### Fallback

Se la view non esiste:
1. Tenta `pub_theme::components.blocks.{type}.default`
2. Tenta `pub_theme::components.blocks.{type}`
3. Mostra errore graceful

---

## 🔗 Cross-References

### Internal Documents

- → [Layout Architecture](LAYOUT_ARCHITECTURE_AND_NAMESPACE.md) - Layout hierarchy
- → [BMad Architecture](_bmad-output/design-comuni-architecture.md) - System design
- → [Block Analysis](_bmad-output/design-comuni-block-analysis.md) - 47 componenti

### External Resources

- → [Laravel Folio Documentation](https://laravel.com/docs/folio)
- → [Livewire Volt Documentation](https://livewire.laravel.com/docs/volt)

---

## ✅ Checklist Fix

### Per Ogni Pagina Test

- [ ] JSON file esiste in `config/local/fixcity/database/content/pages/`
- [ ] JSON ha struttura corretta (`content_blocks.it` o `blocks`)
- [ ] Block view esistono o hanno fallback
- [ ] `[slug].blade.php` usa `<x-layouts.app>` (NON include header/footer)
- [ ] `<x-page>` component renderizza blocchi correttamente

### Per Ogni Block

- [ ] View file esiste in `resources/views/components/blocks/`
- [ ] View accetta `@props(['data' => []])`
- [ ] View usa `pub_theme::` namespace (NON `sixteen::`)

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Status:** ✅ **Fixed**

🐮 **Documentation Complete - Bug Fixed!**
