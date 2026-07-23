# Design System Conversion: Bootstrap Italia → Tailwind + Alpine + Flowbite

**Type**: Architecture Documentation  
**Status**: ✅ In Progress  
**Last Update**: 2026-04-01  
**Related**: [[00-index]], [[layout-hierarchy]], [[vite-configuration-guide]]

---

## 🎯 Overview

Questo documento spiega la strategia di conversione da **Bootstrap Italia** a **Tailwind CSS + Alpine.js + Flowbite**, mantenendo l'HTML identico ma sostituendo i CSS e JS.

### Principio Fondamentale

> **HTML UGUALE, CSS DIVERSO**

L'obiettivo è che l'HTML generato sia **IDENTICO** a quello di Design Comuni, ma le classi CSS sono convertite da Bootstrap Italia a Tailwind.

---

## 🔄 Strategia di Conversione

### Pattern 1: Tailwind @apply

```css
/* ❌ PRIMA: Bootstrap Italia CDN */
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css" />

/* ✅ DOPO: Tailwind CSS con @apply in style-apply.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

@import './style-apply.css';
```

### Pattern 2: Conversione Classi

```html
<!-- HTML IDENTICO - solo i CSS cambiano -->

<!-- Bootstrap Italia -->
<div class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
        ...
    </div>
</div>

<!-- Tailwind (style-apply.css) -->
.it-header-wrapper {
    @apply relative z-50 w-full;
}

.it-header-slim-wrapper {
    @apply bg-primary-700 text-white py-2;
}
```

### Pattern 3: JavaScript

```javascript
// ❌ PRIMA: Bootstrap Italia JS
<script src="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/js/bootstrap-italia.bundle.min.js"></script>

// ✅ DOPO: Alpine.js + Flowbite
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.x.x/dist/flowbite.min.js"></script>

// In app.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

---

## 📋 Stack Tecnologico

| Layer | Bootstrap Italia (Rimosso) | Tailwind + Alpine + Flowbite (Nuovo) |
|-------|---------------------------|--------------------------------------|
| CSS | `bootstrap-italia.min.css` | Tailwind v4 + `style-apply.css` |
| JS | `bootstrap-italia.bundle.min.js` | Alpine.js + Flowbite |
| Icons | Bootstrap Italia Icons | Heroicons / Lucide via Filament |
| Fonts | Bootstrap Italia fonts | System fonts / Google Fonts |

---

## 🎨 File CSS Structure

### style-apply.css

Questo file contiene la conversione delle classi Bootstrap Italia a Tailwind:

```css
/* laravel/Themes/Sixteen/resources/css/style-apply.css */

@layer components {
    /* Header */
    .it-header-wrapper {
        @apply relative z-50 w-full;
    }
    
    .it-header-slim-wrapper {
        @apply bg-primary-700 text-white;
    }
    
    /* Navigation */
    .it-nav-list li a {
        @apply block px-4 py-2 hover:bg-primary-700 transition-colors;
    }
    
    /* Cards */
    .card {
        @apply bg-white rounded-lg shadow-md p-4;
    }
    
    /* Skip Links */
    .skiplink {
        @apply absolute -top-full left-0 z-[100] bg-primary-700 p-2;
    }
    
    .visually-hidden-focusable {
        @apply sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0;
    }
    
    /* Footer */
    .it-footer {
        @apply bg-primary-800 text-white py-8;
    }
    
    /* Form Components */
    .it-input {
        @apply w-full px-4 py-2 border border-neutral-300 rounded focus:ring-2 focus:ring-primary-500;
    }
    
    /* Buttons */
    .it-btn {
        @apply px-6 py-2 rounded font-medium transition-colors;
    }
    
    .it-btn-primary {
        @apply bg-primary-600 text-white hover:bg-primary-700;
    }
}
```

---

## 🔧 Configurazione Vite

### vite.config.js

```javascript
// laravel/Themes/Sixteen/vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        outDir: './public',
        manifest: true,
    },
});
```

### resources/css/app.css

```css
/* laravel/Themes/Sixteen/resources/css/app.css */
@import "tailwindcss";

/* Custom Bootstrap Italia → Tailwind conversions */
@layer components {
    @import "./style-apply.css";
}
```

---

## 🚫 File da Eliminare

Questi file contengono riferimenti a Bootstrap Italia e vanno eliminati:

| File | Motivo |
|------|--------|
| `layouts/base.blade.php` | ❌ Eliminato - conteneva CDN Bootstrap |
| `layouts/bootstrap-italia-exact.blade.php` | Legacy - non usato |
| `layouts/bootstrap-italia-1to1.blade.php` | Legacy - non usato |

---

## ✅ Checklist Conversione

- [x] Rimosso CDN Bootstrap Italia da layouts
- [x] Configurato Tailwind CSS in vite.config.js
- [x] Creato style-apply.css per conversioni
- [x] Configurato Alpine.js in app.js
- [x] Eliminato layouts/base.blade.php
- [ ] Test HTML parità con Design Comuni
- [ ] Verificato che nessun CDN Bootstrap sia residuo

---

## 🔗 Related Documentation

### Internal Links
- [[00-index]] - Main documentation index
- [[layout-hierarchy]] - Layout architecture
- [[vite-configuration-guide]] - Vite config
- [[components]] - Component directory

### External Resources
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [Flowbite](https://flowbite.com/)
- [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/)

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-01 | 1.0 | Initial documentation | Xot |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*