# Design Comuni Replication - Master Documentation

**Project**: FixCity - Sixteen Theme  
**Date**: 2026-04-01  
**Status**: ✅ Architecture Complete  
**Theme**: Sixteen (pub_theme)  
**Repository**: laraxot/base_fixcity_fila5

---

## 🎯 Project Overview

Replicate the Italian Design Comuni template using **Tailwind CSS + Alpine.js** (NOT Bootstrap Italia) following **DRY + KISS** principles.

### Target URLs
- **Template**: https://italia.github.io/design-comuni-pagine-statiche/sito/[page].html
- **Implementation**: http://fixcity.local/it/tests/[slug]

### Key Pages to Replicate
1. homepage → /it/tests/homepage
2. argomenti → /it/tests/argomenti
3. appuntamento-06-conferma → /it/tests/appuntamento-06-conferma
4. [All 38 Design Comuni pages]

---

## 🏗️ Architecture Principles

### 1. Dynamic Routing (DRY)
**❌ WRONG**: Multiple specific blade files
```
pages/tests/homepage.blade.php     ← DON'T
pages/tests/argomenti.blade.php    ← DON'T
pages/tests/amministrazione.blade.php ← DON'T
```

**✅ CORRECT**: Single dynamic file
```
pages/tests/[slug].blade.php       ← DO THIS (ONE file for ALL pages)
```

### 2. Component Naming
**❌ WRONG**: Page-specific blocks
```
pub_theme::components.blocks.tests.argomenti.topics-grid  ← WRONG
pub_theme::components.blocks.tests.homepage.hero          ← WRONG
```

**✅ CORRECT**: Universal block types
```
pub_theme::components.blocks.hero.homepage     ← CORRECT (type.variant)
pub_theme::components.blocks.topics.grid       ← CORRECT (type.variant)
pub_theme::components.blocks.events.calendar   ← CORRECT (type.variant)
```

### 3. Layout System
```
<x-layouts.app>              ← Use this for public pages
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" tpl="full" />
</x-layouts.app>
```

**NOT**:
- ❌ `<x-layouts.design-comuni>`
- ❌ `<x-pub_theme::layouts.*>`
- ❌ `<x-sixteen::*>`

### 4. Vite Configuration
```javascript
// vite.config.js
build: {
    outDir: './public',              // ← CORRECT
}

// package.json
"copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"
```

**WHY**: Clean separation, no cross-filesystem issues, explicit deployment

---

## 📐 Complete Architecture

### File Structure
```
laravel/Themes/Sixteen/
├── resources/views/
│   ├── components/
│   │   ├── layouts/
│   │   │   ├── main.blade.php      ← Base HTML (DOCTYPE, head, body)
│   │   │   └── app.blade.php       ← Extends main, adds semantics
│   │   ├── sections/
│   │   │   ├── header.blade.php    ← Called with <x-section slug="header" />
│   │   │   └── footer.blade.php    ← Called with <x-section slug="footer" />
│   │   └── blocks/
│   │       ├── hero/               ← Type: hero
│   │       ├── topics/             ← Type: topics
│   │       ├── events/             ← Type: events
│   │       └── ...
│   └── pages/
│       └── tests/
│           ├── [slug].blade.php    ← Dynamic route for ALL pages
│           └── index.blade.php     ← Index page
├── vite.config.js                   ← outDir: './public'
└── package.json                     ← "copy": "cp -rv ./public/* ..."
```

### Request Flow
```
1. User requests: /it/tests/homepage
   ↓
2. Folio Route: pages/tests/[slug].blade.php
   ↓
3. Mount: $slug = 'homepage', $pageSlug = 'tests.homepage'
   ↓
4. Load JSON: config/local/fixcity/database/content/pages/tests.homepage.json
   ↓
5. Render:
   - <x-layouts.app>
   - <x-section slug="header" />
   - <x-page side="content" :slug="$pageSlug" :data="$data" />
     → Loops through content_blocks from JSON
     → @includeIf($block['data']['view'])
   - <x-section slug="footer" tpl="full" />
```

### JSON Structure
```json
{
  "slug": "tests.homepage",
  "title": "Homepage - Nome del Comune",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage",
          "title": "Nome del Comune",
          "subtitle": "Contenuti in evidenza"
        }
      },
      {
        "type": "topics",
        "data": {
          "view": "pub_theme::components.blocks.topics.grid",
          "items": [...]
        }
      }
    ]
  }
}
```

---

## 🔧 Build Process

### Complete Command
```bash
cd laravel/Themes/Sixteen
composer update -W      # Update PHP dependencies
npm install             # Install JS dependencies
npm run build           # Build to ./public
npm run copy            # Copy to public_html/themes/Sixteen/
```

### One-Liner
```bash
cd laravel/Themes/Sixteen && composer update -W && npm install && npm run build && npm run copy
```

### Output
```
public_html/themes/Sixteen/
├── manifest.json             ← Vite manifest for @vite()
└── assets/
    ├── app-*.css            ← Compiled CSS (145.43 kB)
    └── app-*.js             ← Bundled JS (46.65 kB)
```

---

## 🎨 Styling Approach

### Tailwind @apply (NOT Bootstrap Italia CDN)

**❌ WRONG**:
```css
@import url('https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css');
```

**✅ CORRECT**:
```css
/* style-apply.css */
@import 'tailwindcss';
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap');

/* Replicate Bootstrap Italia classes with Tailwind @apply */
.it-header-wrapper {
  background-color: #007a52;
  @apply text-white relative;
}

.it-header-slim-wrapper {
  background-color: #00614a;
  @apply py-2 text-sm;
}
```

### HTML Classes
Keep Bootstrap Italia class names in HTML for compatibility:
```html
<body>
  <div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
  </div>
  <header class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
      ...
    </div>
  </header>
</body>
```

**Styling comes from**: `style-apply.css` with Tailwind @apply

---

## 📚 Documentation Index

### Architecture Documentation
1. **[layout-hierarchy.md](./layout-hierarchy.md)** - `x-layouts.app` extends `x-layouts.main`
2. **[components-directory-structure.md](./components-directory-structure.md)** - Complete component organization
3. **[vite-configuration-guide.md](./vite-configuration-guide.md)** - Build configuration (`outDir: './public'`)
4. **[build-commands-guide.md](./build-commands-guide.md)** - Complete build process
5. **[vite-config-fix-summary.md](./vite-config-fix-summary.md)** - Why `outDir: './public'`
6. **[DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)** - Master documentation index

### Design Comuni Documentation
1. **[design-comuni-implementation.md](./design-comuni-implementation.md)** - Primary technical reference
2. **[agid-checklist.md](./agid-checklist.md)** - Compliance verification
3. **[components-status.md](./components-status.md)** - Current state of all components

### Related Modules
- **Cms Module**: `../../../Modules/Cms/docs/`
  - Content blocks system
  - Page rendering
  - Section management
- **UI Module**: `../../../Modules/UI/docs/`
  - SVG icons
  - UI components

---

## ✅ Verification Checklist

### Architecture
- [x] Single `[slug].blade.php` for all test pages
- [x] `x-layouts.app` extends `x-layouts.main`
- [x] Header/Footer called with `<x-section slug="header" />`
- [x] Block types organized by TYPE, not page
- [x] JSON files in `config/local/fixcity/database/content/pages/`

### Build Configuration
- [x] `outDir: './public'` in vite.config.js
- [x] `"copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"` in package.json
- [x] `@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')` in layout
- [x] manifest.json generated and copied

### Styling
- [x] No Bootstrap Italia CDN
- [x] Tailwind @apply in style-apply.css
- [x] Bootstrap Italia class names in HTML (for compatibility)
- [x] Alpine.js for interactivity

### Documentation
- [x] Bidirectional links between all docs
- [x] DRY + KISS compliant
- [x] No duplicate information
- [x] Clear navigation with indices

---

## 🚀 Next Steps

### Immediate
1. ✅ Architecture documented
2. ✅ Build process documented
3. ⚠️ Header/Footer HTML matching (needs screenshots)
4. ⚠️ Block types implementation (ongoing)

### Short Term
1. Create screenshot comparisons for all pages
2. Implement missing block types
3. Complete Tailwind @apply styles
4. Test all pages end-to-end

### Long Term
1. Install UI/UX Pro Max skill
2. Configure NotebookLM MCP
3. Create comprehensive block library
4. Document all components with examples

---

## 🔗 Multi-Agent Coordination

### Agent Roles
1. **Documentation Agent**: Update all docs folders
2. **Frontend Agent**: Implement Tailwind components
3. **Backend Agent**: Fix JSON structures and routes
4. **QA Agent**: Screenshot comparisons and validation
5. **Integration Agent**: Ensure all pieces work together

### Communication Protocol
- Check GitHub Issues before starting work
- Update coordination log after each task
- Small commits with frequent pushes
- Document all decisions in docs/

---

## 📊 Key Metrics

| Metric | Target | Status |
|--------|--------|--------|
| **Pages to Replicate** | 38 | ⚠️ In Progress |
| **Block Types** | 20+ | ✅ Architecture Ready |
| **Documentation Files** | 10+ | ✅ Complete |
| **Build Process** | Automated | ✅ Working |
| **DRY Compliance** | 100% | ✅ Verified |
| **KISS Compliance** | 100% | ✅ Verified |

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-01 | 1.0 | Initial architecture | AI Agent Team |
| 2026-04-01 | 1.1 | Complete documentation | AI Agent Team |
| 2026-04-01 | 1.2 | Bidirectional links | AI Agent Team |
| 2026-04-01 | 1.3 | Build process verified | AI Agent Team |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
