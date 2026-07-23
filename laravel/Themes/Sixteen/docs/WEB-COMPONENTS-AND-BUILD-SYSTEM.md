# Sixteen Theme — Web Components & Build System Architecture

**Date**: 2026-04-15  
**Status**: Active  
**Related**: Story 8-10 (segnalazione-crea map bidirectional sync)

---

## Overview

The Sixteen theme is a **presentation layer** that compiles CSS and JavaScript assets for the entire application. It imports components from other modules but **must maintain strict boundaries** to avoid cross-cutting concerns and build failures.

This document clarifies:
- ✅ What can be imported in theme `app.js`
- ❌ What should NOT be imported
- 🔧 How to properly integrate module-specific web components
- 🏗️ Build system architecture and asset compilation

---

## Architecture: Theme vs Module Assets

### Theme Layer (`laravel/Themes/Sixteen/`)

**Responsibility**: 
- Compile shared CSS/JS for all pages
- Provide design system tokens and utilities
- Alpine.js components for UI interactions

**Build Output**:
- `public/assets/app-*.css` — Main stylesheet
- `public/assets/app-*.js` — Main JavaScript bundle
- Deployed to `public_html/themes/Sixteen/`

**Build Command**:
```bash
npm run build          # Vite build in theme directory
npm run copy           # Copy compiled assets to public_html
```

### Module Layer (`laravel/Modules/{ModuleName}/`)

**Responsibility**:
- Provide domain-specific components (forms, widgets, maps)
- Self-contained assets and dependencies
- Module-level CSS/JS bundling (if needed)

**Examples**:
- `Modules/Geo/resources/js/components/my-map-lit.js` — Web component
- `Modules/Fixcity/Filament/Widgets/CreateTicketWizardWidget.php` — Filament widget

**Key Rule**: Module assets are **self-scoped** — they include their own dependencies and don't rely on theme imports.

---

## The Build Failure (2026-04-15)

### Symptom

```
[vite]: Rollup failed to resolve import 'leaflet/dist/leaflet.css' from my-map-lit.js
```

### Root Cause

The web component `my-map-lit.js` was being **globally imported** in theme `app.js`:

```javascript
// ❌ WRONG (was in Themes/Sixteen/resources/js/app.js, line 17)
import '../../../../Modules/Geo/resources/js/components/my-map-lit.js';
```

**Why this fails**:
1. `my-map-lit.js` imports `leaflet/dist/leaflet.css`
2. CSS imports from `node_modules` must resolve relative to the importing file
3. The theme's `node_modules` is at `Themes/Sixteen/node_modules`
4. The import path `leaflet/dist/leaflet.css` resolves to **theme's** Leaflet, which may not be installed
5. Vite/Rollup can't resolve it → build fails

### The Fix

**Removed the problematic import line** from `Themes/Sixteen/resources/js/app.js`:

```diff
  import '@splidejs/splide/dist/css/splide.min.css';
  import { dropdownToggle } from './components/dropdown';
  import { modal } from './components/modal';
  import { mobileMenu } from './components/mobile-menu';
  import { governanceCarousel } from './components/carousel';
  import './components/bootstrap-italia.js';
- import '../../../../Modules/Geo/resources/js/components/my-map-lit.js';
```

**Result**: ✅ Build succeeds

---

## Design Pattern: Module-Scoped Web Components

### ✅ Correct Approach

**Use case**: You need `<my-map>` web component in a specific page/feature

**Step 1: Import in Page/Component Blade**

Instead of importing globally in `app.js`, import **only where needed**:

```blade
{{-- resources/views/pages/report-issue.blade.php --}}

<script type="module">
    import '{{ asset("Modules/Geo/resources/js/components/my-map-lit.js") }}';
</script>

<form>
    <my-map lat="45.6669" lng="12.2423" zoom="10"></my-map>
</form>
```

Or use a **Livewire component** that handles the import:

```php
// app/Livewire/MapWidget.php
#[On('alpine:init')]
public function render()
{
    return view('livewire.map-widget');
}
```

```blade
{{-- resources/views/livewire/map-widget.blade.php --}}
<script type="module">
    import '{{ asset("Modules/Geo/resources/js/components/my-map-lit.js") }}';
</script>

<my-map lat="{{ $this->lat }}" lng="{{ $this->lng }}"></my-map>
```

**Step 2: Wire up Communication (if needed)**

Use Alpine.js or custom events to communicate between web component and Livewire:

```blade
<div x-data="{ lat: 45.6669, lng: 12.2423 }" @map-changed="lat = $event.detail.lat; lng = $event.detail.lng">
    <my-map :lat="lat" :lng="lng" @change="dispatch('map-changed', { lat: $el.lat, lng: $el.lng })"></my-map>
</div>
```

### ❌ What NOT to Do

**Never** import module-specific web components globally in `app.js`:

```javascript
// ❌ DON'T DO THIS
import '../../../../Modules/Geo/resources/js/components/my-map-lit.js';
import '../../../../Modules/SomeOther/resources/js/widgets/something.js';
```

**Why**:
- Breaks separation of concerns
- Creates undeclared dependencies at theme build time
- Module dependencies may not resolve in theme context
- Module updates require theme rebuild
- Couples presentation layer to domain layer

---

## Lit.dev: Web Components Framework

### What is Lit?

[Lit](https://lit.dev/) is a lightweight TypeScript library for building Web Components with:
- Reactive property bindings
- Efficient DOM updates
- Shadow DOM style encapsulation
- Lifecycle hooks (`render()`, `firstUpdated()`, `updated()`, `disconnectedCallback()`)

### Example: my-map-lit.js

```javascript
import { LitElement, html, css } from 'lit';
import L from 'leaflet';

export class MyMap extends LitElement {
    static properties = {
        lat: { type: Number },
        lng: { type: Number },
        zoom: { type: Number },
        markerTitle: { type: String, attribute: 'marker-title' },
    };

    static styles = css`
        :host { display: block; }
        .map { width: 100%; height: 400px; }
    `;

    constructor() {
        super();
        this.lat = 45.6669;
        this.lng = 12.2423;
        this.zoom = 10;
        this.markerTitle = 'My Map';
        this._map = null;
    }

    render() {
        return html`<div id="map" class="map"></div>`;
    }

    firstUpdated() {
        const mapEl = this.renderRoot.querySelector('#map');
        this._map = L.map(mapEl).setView([this.lat, this.lng], this.zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(this._map);
        L.marker([this.lat, this.lng])
            .addTo(this._map)
            .bindPopup(this.markerTitle);
    }

    disconnectedCallback() {
        if (this._map) {
            this._map.remove();
            this._map = null;
        }
        super.disconnectedCallback();
    }
}

customElements.define('my-map', MyMap);
```

### Key Concepts

**Properties**: Reactive attributes
```javascript
static properties = {
    lat: { type: Number },        // lat attribute → lat property
    markerTitle: { type: String } // markerTitle → marker-title attribute
};
```

**Shadow DOM**: Style encapsulation
```javascript
static styles = css`...`; // CSS scoped to component
```

**Lifecycle**:
- `constructor()` — Initialize state
- `render()` — Return template
- `firstUpdated()` — Component in DOM, safe to access child elements
- `updated()` — Property changed, re-render needed
- `disconnectedCallback()` — Component removed, clean up resources

### Resources

- 📚 [Lit Official Docs](https://lit.dev/)
- 📚 [Web Components MDN](https://developer.mozilla.org/en-US/docs/web/web_components)
- 📚 [Custom Elements v1 Spec](https://html.spec.whatwg.org/multipage/custom-elements.html)

---

## Build Workflow: Sixteen Theme

### 1. Development

```bash
cd laravel/Themes/Sixteen

# Watch for changes and rebuild
npm run dev

# Or build once
npm run build
```

### 2. Production Build

```bash
npm run build:production
npm run copy
```

### 3. Filament-Specific Build

For admin panel assets:
```bash
npm run build:filament
npm run copy:filament
```

### 4. What Gets Built?

- ✅ **CSS**: All `resources/css/*.css` + imported modules
- ✅ **JS**: All `resources/js/*.js` + imported modules
- ✅ **Assets**: Images, fonts from `resources/` folder
- ✅ **Dependencies**: Anything in `node_modules` that's imported
- ❌ **Module-scoped code**: Only if explicitly imported in theme
- ❌ **Module-scoped CSS**: Doesn't resolve unless module is bundled

---

## Anti-Patterns & Guardrails

| Anti-Pattern | ❌ Result | ✅ Solution |
|---|---|---|
| Import module web components in theme `app.js` | Build fails (can't resolve dependencies) | Import only in specific pages/features |
| Use `wire:ignore` without proper isolation | Livewire might still re-render component | Wrap component in container with `wire:ignore` |
| Import CSS from `node_modules` in theme | May not resolve if module isn't installed | Import in component JavaScript, not theme CSS |
| Global web component registration | Conflicts if multiple versions exist | Register in module, import only where needed |
| No `disconnectedCallback()` cleanup | Memory leaks on navigation | Always clean up external resources |

---

## Checklist: Adding a New Web Component

- [ ] Create component in module: `Modules/{Name}/resources/js/components/`
- [ ] Use Lit.dev pattern with proper lifecycle
- [ ] Include `disconnectedCallback()` for cleanup
- [ ] **DO NOT** import globally in theme `app.js`
- [ ] Document in module's `docs/` folder
- [ ] Import only in specific page/feature where used
- [ ] Test in Firefox, Chrome, Safari (Web Components support)
- [ ] Check that CSS dependencies resolve correctly
- [ ] Update theme docs if affecting build system

---

## References

- [Sixteen Theme Documentation](./00-INDEX.md)
- [Geo Module: Filament Forms Components](../../Modules/Geo/docs/filament-forms-components.md)
- [Story 8-10: Map Bidirectional Sync](../../../_bmad-output/implementation-artifacts/8-10-segnalazione-crea-map-bidirectional-sync-and-no-refresh-on-marker-drag.md)
- [Lit.dev Official](https://lit.dev/)
- [Web Components on MDN](https://developer.mozilla.org/en-US/docs/web/web_components)
