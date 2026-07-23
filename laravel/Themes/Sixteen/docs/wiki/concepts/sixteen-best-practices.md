---
title: "Sixteen Theme Best Practices"
type: concept
sources: ["../../Themes/Sixteen/resources/"]
confidence: high
created: 2026-04-28
updated: 2026-04-28
tags: [sixteen, best-practices, design-comuni, blade-components, filament-5]
related:
  - concepts/header-section-owner-rule.md
  - concepts/theme-owned-wizard-css-parity-rule.md
  - concepts/design-comuni-header-auth-state.md
---

# Sixteen Theme Best Practices

## Overview

Best practices per lo sviluppo del tema Sixteen (Design Comuni Italia).

## ✅ Best Practices

### 1. Header SSoT in `v1.blade.php`
```blade
{{-- ✅ SI --}}
{{-- File: Themes/Sixteen/resources/views/components/sections/header/v1.blade.php --}}
<x-section slug="header" />

{{-- ❌ NO --}}
{{-- Modificare bootstrap-italia/header.blade.php per default --}}
```

### 2. Partiali header in `sections/header/partials/`
```blade
{{-- ✅ SI --}}
{{-- Themes/Sixteen/resources/views/components/sections/header/partials/ --}}
<x-dynamic-component :component="'sections.header.partials.' . $partialName" />

{{-- ❌ NO --}}
{{-- File allo stesso livello di v1.blade.php --}}
```

### 3. CSS Design Comuni solo in `app.css`
```css
/* ✅ SI - in Themes/Sixteen/resources/css/app.css */
[data-slug="segnalazione-crea"] .header { background: var(--dc-green); }

/* ❌ NO - inline style o <style> nel Blade */
```

### 4. Build + copy dopo modifiche CSS
```bash
# ✅ SI - workflow obbligatorio
cd laravel/Themes/Sixteen
npm run build
cp -r dist/ ../../../public/themes/sixteen/
```

### 5. Importare componenti Lit Geo in `app.js`
```javascript
// ✅ SI - in Themes/Sixteen/resources/js/app.js
import '@geo/map-picker-lit.js';

// ❌ NO - dimenticare import, componente non riconosciuto
```

## ❌ Bad Practices

### 1. `<style>` inline nei Blade del tema
```blade
{{-- ❌ NO --}}
<style>.header { color: blue; }</style>

{{-- ✅ SI --}}
{{-- CSS in resources/css/app.css --}}
```

### 2. Scrivere CSS per singola pagina
```css
/* ❌ NO */
.ticket-wizard-root { ... }
[data-slug="segnalazione-crea"] { ... }

/* ✅ SI - componenti riusabili */
.dc-btn-primary { ... }
.dc-navbar-green { ... }
```

### 3. Dimenticare `build/copy` dopo modifiche
Vedi Best Practice #4.

### 4. Override diretti Bootstrap Italia
```blade
{{-- ❌ NO --}}
<x-bootstrao-italia-header class="text-blue" />

{{-- ✅ SI --}}
{{-- Usare token CSS o partiali locali --}}
```

## 🔗 False Friends

### `v1.blade.php` vs `header.blade.php`
- **False Friend**: Pensare che `header.blade.php` sia il file principale
- **Realtà**: Con `<x-section slug="header" />`, il file SSoT è `sections/header/v1.blade.php`
- **Soluzione**: Modificare sempre `v1.blade.php` per l'header

### `app.css` vs `app.js`
- **False Friend**: Pensare che CSS e JS siano nello stesso file
- **Realtà**: `app.css` per stili, `app.js` per componenti JS/Lit
- **Soluzione**: Modificare entrambi se servono stili + componenti

### `build` vs `copy`
- **False Friend**: Pensare che `npm run build` sia sufficiente
- **Realtà**: Il build crea `dist/`, il copy (o symlink) porta in `public/themes/`
- **Soluzione**: Eseguire sempre entrambi

## Related

- [[header-section-owner-rule]]
- [[theme-owned-wizard-css-parity-rule]]
- [[design-comuni-header-auth-state]]
- [[geo-lit-components-must-be-imported-rule]]
- [[theme-bundle-integration-false-friends]]
