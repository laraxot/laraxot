# Design Comuni Replication - Master Plan

**Project:** FixCity Fila5  
**Date:** 2026-04-01  
**Status:** 🔄 **In Progress**  
**Priority:** 🔴 **Critical**  

---

## 🎯 Executive Summary

Replicare **tutte le pagine statiche** di [Design Comuni Pagine Statiche](https://italia.github.io/design-comuni-pagine-statiche/) utilizzando:

- ✅ **Tailwind CSS @apply** (NOT Bootstrap Italia imports)
- ✅ **Folio + Volt** con singolo `[slug].blade.php`
- ✅ **JSON Content Blocks** (NOT hardcoded HTML)
- ✅ **Blocchi universali riutilizzabili** (NOT page-specific)
- ✅ **`<x-layouts.app>`** (NOT custom layouts)
- ✅ **`<x-section slug="header" />`** (NOT inline HTML)
- ✅ **`<x-pub_theme::` namespace** (NOT `<x-sixteen::`)

---

## 🏗️ Architecture Overview

### Theme Detection Flow

```
.env → APP_URL=http://fixcity.local
  ↓
Extract Domain → fixcity.local
  ↓
Remove protocol → fixcity.local
  ↓
Remove www → fixcity.local
  ↓
Explode by "." → ['fixcity', 'local']
  ↓
Reverse array → ['local', 'fixcity']
  ↓
Join with "/" → "local/fixcity"
  ↓
Config path → laravel/config/local/fixcity/xra.php
  ↓
Read pub_theme → "Sixteen"
  ↓
Theme folder → laravel/Themes/Sixteen/
```

### Page Routing Flow

```
URL: /it/tests/argomenti
  ↓
Folio Route → pages/tests/[slug].blade.php
  ↓
Volt Component → mount(string $slug)
  ↓
pageSlug = 'tests.'.$slug
  ↓
JSON Content → laravel/config/local/fixcity/database/content/pages/tests.argomenti.json
  ↓
Blocks → "blocks": [{ "type": "topics-grid", "data": {...} }]
  ↓
Render → <x-pub_theme::components.blocks.topics-grid :data="$block['data']" />
```

### Layout Architecture

```
<x-layouts.app>
  ↓
<x-layouts.main> (extends)
  ↓
  <x-section slug="header" />
  ↓
  @volt('tests.view')
    <x-page side="content" :slug="$pageSlug" :data="$data" />
  @endvolt
  ↓
  <x-section slug="footer" />
</x-layouts.app>
```

---

## 📁 File Structure

### Correct Structure

```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   └── app.css (with @apply for Bootstrap Italia)
│   ├── js/
│   │   └── app.js (Alpine.js, NOT Bootstrap Italia JS)
│   └── views/
│       ├── components/
│       │   ├── layouts/
│       │   │   ├── main.blade.php (base HTML structure)
│       │   │   └── app.blade.php (extends main, adds semantics)
│       │   ├── blocks/
│       │   │   ├── hero/
│       │   │   │   └── default.blade.php
│       │   │   ├── topics-grid/
│       │   │   │   └── default.blade.php
│       │   │   ├── card/
│       │   │   │   └── default.blade.php
│       │   │   └── ...
│       │   └── sections/
│       │       ├── header/
│       │       │   └── default.blade.php
│       │       └── footer/
│       │           └── default.blade.php
│       └── pages/
│           └── tests/
│               ├── [slug].blade.php (ALL pages use this)
│               └── index.blade.php (listing page)
├── Main_files/
│   └── five/
│       └── src/
│           ├── style-apply.css (Tailwind @apply rules)
│           └── app1.js (Alpine.js components)
└── docs/
    ├── design-comuni/
    │   ├── replication-plan.md (THIS FILE)
    │   ├── screenshots/
    │   │   ├── homepage/
    │   │   ├── argomenti/
    │   │   └── appuntamento-06-conferma/
    │   └── analysis/
    │       ├── header-analysis.md
    │       ├── footer-analysis.md
    │       └── block-analysis.md
    └── ...
```

### JSON Content Structure

**File:** `laravel/config/local/fixcity/database/content/pages/tests.argomenti.json`

```json
{
  "slug": "tests.argomenti",
  "title": "Argomenti",
  "blocks": [
    {
      "type": "hero",
      "weight": 10,
      "data": {
        "view": "pub_theme::components.blocks.hero.default",
        "title": "Argomenti del Comune",
        "subtitle": "Naviga per argomenti"
      }
    },
    {
      "type": "topics-grid",
      "weight": 20,
      "data": {
        "view": "pub_theme::components.blocks.topics-grid.default",
        "topics": [
          {"title": "Cultura", "icon": "it-culture", "url": "/it/cultura"},
          {"title": "Sport", "icon": "it-sport", "url": "/it/sport"}
        ]
      }
    }
  ]
}
```

---

## ❌ Common Mistakes (DO NOT DO)

### 1. ❌ WRONG: Page-Specific Blade Files

```blade
<!-- DON'T CREATE: pages/tests/argomenti.blade.php -->
<!-- DON'T CREATE: pages/tests/homepage.blade.php -->
<!-- DON'T CREATE: pages/tests/appuntamento-06-conferma.blade.php -->
```

**✅ CORRECT:** Single `pages/tests/[slug].blade.php` for ALL pages

---

### 2. ❌ WRONG: Custom Layouts

```blade
<!-- DON'T USE: <x-layouts.design-comuni> -->
<!-- DON'T USE: <x-pub_theme::layouts.design-comuni> -->
```

**✅ CORRECT:** `<x-layouts.app>`

---

### 3. ❌ WRONG: Bootstrap Italia Imports

```css
/* DON'T DO: @import url('https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css'); */
```

**✅ CORRECT:** Tailwind @apply in `style-apply.css`

---

### 4. ❌ WRONG: Page-Specific Blocks

```blade
<!-- DON'T USE: <x-pub_theme::components.blocks.tests.argomenti.topics-grid> -->
<!-- "tests.argomenti" is NOT a valid block type -->
```

**✅ CORRECT:** `<x-pub_theme::components.blocks.topics-grid.default>`

---

### 5. ❌ WRONG: Inline Header/Footer HTML

```blade
<!-- DON'T DO in [slug].blade.php: -->
<header class="it-header-wrapper">...</header>
<footer>...</footer>
```

**✅ CORRECT:** `<x-section slug="header" />` and `<x-section slug="footer" />`

---

### 6. ❌ WRONG: Namespace per Theme

```blade
<!-- DON'T USE: <x-sixteen::blocks.navigation.header-main> -->
```

**✅ CORRECT:** `<x-pub_theme::blocks.navigation.header-main>`

---

### 7. ❌ WRONG: Vite Without Theme Parameter

```blade
<!-- DON'T USE: @vite(['resources/css/app.css']) -->
```

**✅ CORRECT:** `@vite(['resources/css/app.css'], 'themes/Sixteen')`

---

### 8. ❌ WRONG: Bootstrap Italia JS Import

```js
// DON'T DO: import "bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js";
```

**✅ CORRECT:** Alpine.js components in `app.js`

---

## ✅ Correct Implementation

### [slug].blade.php

```blade
<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('tests.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = [
            'slug' => $slug
        ];
    }
};
?>

<x-layouts.app>
    @volt('tests.view')
        <div>
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        </div>
    @endvolt
</x-layouts.app>
```

---

## 🎨 Block Types (Universal, Reusable)

Inspired by:
- https://flowbite.com/blocks/
- https://tailwindcss.com/plus/ui-blocks
- https://daisyui.com/components/
- https://italia.github.io/bootstrap-italia/docs/componenti/introduzione/

### Block Categories

| Category | Block Types |
|----------|-------------|
| **Hero** | hero/default, hero-with-image, hero-with-video |
| **Navigation** | topics-grid, category-list, mega-menu |
| **Cards** | card/default, card-with-image, card-with-icon |
| **Content** | text-block, image-gallery, accordion |
| **Forms** | contact-form, search-form, newsletter-signup |
| **Media** | video-embed, image-slider, gallery-grid |
| **Testimonials** | testimonial-single, testimonial-carousel |
| **Stats** | stats-grid, counter, progress-bar |
| **CTA** | cta-banner, cta-button-group |
| **Footer** | footer-main, footer-slim, footer-links |

---

## 📋 Page Replication Checklist

### Pages to Replicate

| Page | URL | Status | JSON | Blocks | Screenshot |
|------|-----|--------|------|--------|------------|
| Homepage | /sito/homepage.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |
| Argomenti | /sito/argomenti.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |
| Appuntamento 06 | /sito/appuntamento-06-conferma.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |
| Novità | /sito/novita.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |
| Documenti e Dati | /sito/documenti-dati.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |
| Amministrazione | /sito/amministrazione.html | ⏳ Pending | ⏳ | ⏳ | ⏳ |

---

## 🔧 Build Commands

### Theme Build Process

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen

# 1. Update dependencies
composer update -W

# 2. Install NPM packages
npm install

# 3. Build Vite assets (outDir: './public')
npm run build

# 4. Copy to public_html
npm run copy
```

### Vite Configuration

**File:** `laravel/Themes/Sixteen/vite.config.js`

```javascript
export default defineConfig({
    build: {
        outDir: './public',  // ✅ CORRECT
        emptyOutDir: true,
        manifest: 'manifest.json',
        // ... other options
    },
});
```

**File:** `laravel/Themes/Sixteen/package.json`

```json
{
  "scripts": {
    "build": "vite build",
    "copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"
  }
}
```

---

## 📊 Progress Tracking

### Phase 1: Foundation (Week 1)

- [x] Theme detection documented
- [x] Vite build system fixed
- [ ] Single [slug].blade.php created
- [ ] JSON content structure defined
- [ ] Block component architecture defined

### Phase 2: Header/Footer (Week 2)

- [ ] Header screenshot analysis
- [ ] Header Tailwind @apply implementation
- [ ] Footer screenshot analysis
- [ ] Footer Tailwind @apply implementation
- [ ] Visual parity achieved

### Phase 3: Block Components (Week 3-4)

- [ ] Hero blocks (5 variants)
- [ ] Navigation blocks (3 variants)
- [ ] Card blocks (5 variants)
- [ ] Content blocks (5 variants)
- [ ] Form blocks (3 variants)

### Phase 4: Page Replication (Week 5-6)

- [ ] Homepage JSON + blocks
- [ ] Argomenti JSON + blocks
- [ ] Appuntamento JSON + blocks
- [ ] All other pages

### Phase 5: Documentation (Ongoing)

- [ ] Screenshot analysis for each page
- [ ] Block documentation
- [ ] Usage guides
- [ ] Bidirectional links

---

## 🎯 Success Criteria

### Visual Parity

- [ ] Header identical to Design Comuni
- [ ] Footer identical to Design Comuni
- [ ] Colors match exactly
- [ ] Spacing matches exactly
- [ ] Typography matches exactly
- [ ] Icons match exactly

### Technical Excellence

- [ ] Single [slug].blade.php for ALL pages
- [ ] JSON content blocks (NOT hardcoded HTML)
- [ ] Universal reusable blocks (NOT page-specific)
- [ ] Tailwind @apply (NOT Bootstrap imports)
- [ ] Alpine.js (NOT Bootstrap Italia JS)
- [ ] <x-layouts.app> (NOT custom layouts)
- [ ] <x-section slug="header" /> (NOT inline HTML)

### Documentation

- [ ] Screenshot analysis for each page
- [ ] Block documentation with examples
- [ ] Bidirectional links between all docs
- [ ] DRY + KISS compliance verified

---

## 📞 Support

### Key Documents

- [Layout Architecture](Themes/Sixteen/docs/layout-architecture.md)
- [Vite Manifest Fix](Themes/Sixteen/docs/VITE_MANIFEST_FIX_COMPLETE.md)
- [Master Documentation Index](docs/MODULE_DOCS_INDEX.md)

### External Resources

- [Design Comuni Pagine Statiche](https://italia.github.io/design-comuni-pagine-statiche/)
- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind UI Blocks](https://tailwindcss.com/plus/ui-blocks)
- [DaisyUI Components](https://daisyui.com/components/)

---

**Status:** 🔄 **In Progress**  
**Next Review:** Daily  
**Owner:** Multi-Agent Team  
**Method:** BMad + GSD + Ralph Loop  

🐮 **Design Comuni Replication Plan Complete!**
