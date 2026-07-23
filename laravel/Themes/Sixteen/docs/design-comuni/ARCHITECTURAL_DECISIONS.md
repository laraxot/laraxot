# Design Comuni Italia - Architectural Decisions & Zen Philosophy

**Data**: 2026-04-01  
**Status**: ✅ DEFINITIVE  
**Methodology**: DRY + KISS + Forward-Only

---

## 🧘 La Filosofia, Lo Zen, La Religione

### 1. UNO [slug].blade.php per TUTTE le pagine

**PERCHÉ**:
- ✅ DRY: Una sola blade gestisce 38 pagine
- ✅ KISS: Logica semplice, slug dinamico
- ✅ Maintainability: Modifichi 1 file, non 38
- ✅ Scalability: Aggiungi pagine senza nuove blade

**COME**:
```php
// laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
<x-layouts.app>
 @volt('tests.view')
    <x-page side="content" :slug="$pageSlug" :data="$data" />
 @endvolt
</x-layouts.app>
```

**MAI**:
```blade
❌ homepage.blade.php
❌ argomenti.blade.php
❌ amministrazione.blade.php
```

---

### 2. JSON per Contenuti, Blade per Struttura

**PERCHÉ**:
- ✅ Separation of Concerns: Struttura (blade) ≠ Contenuti (JSON)
- ✅ CMS-driven: Content editors modificano JSON
- ✅ Riutilizzabilità: Stessa blade, contenuti diversi
- ✅ Versioning: JSON sotto Git, facile diff

**COME**:
```json
{
  "slug": "tests.homepage",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "view": "pub_theme::components.blocks.hero.homepage",
        "data": {...}
      }
    ]
  }
}
```

**MAI**:
```blade
❌ HTML hardcoded nella blade
❌ Contenuti pagina-specifici nella blade
```

---

### 3. Blocchi Universali, NON Specifici per Pagina

**PERCHÉ**:
- ✅ DRY: 47 blocchi per 38 pagine (NOT 38 blocchi specifici)
- ✅ Maintainability: Modifichi 1 blocco, tutte le pagine aggiornate
- ✅ Consistency: Stesso blocco, stesso look
- ✅ Scalability: Nuove pagine usano blocchi esistenti

**COME**:
```blade
✅ pub_theme::components.blocks.hero.homepage
✅ pub_theme::components.blocks.card.simple
✅ pub_theme::components.blocks.navigation.main
```

**MAI**:
```blade
❌ pub_theme::components.blocks.tests.argomenti
❌ pub_theme::components.blocks.homepage.specific
```

**TIPI BLOCCO** (da ispirazione):
- https://flowbite.com/blocks/ → `hero`, `card`, `navigation`
- https://tailwindcss.com/plus/ui-blocks → `grid`, `list`, `modal`
- https://daisyui.com/components/ → `accordion`, `carousel`, `tabs`
- https://italia.github.io/bootstrap-italia/docs/componenti/ → `breadcrumbs`, `footer`, `header`

---

### 4. Tailwind @apply, NOT Bootstrap Imports

**PERCHÉ**:
- ✅ Performance: CSS compilato, no CDN
- ✅ Customization: Tailwind utilities
- ✅ Consistency: Unico design system
- ✅ Build: Vite gestisce assets

**COME**:
```css
/* style-apply.css */
.it-header-wrapper {
  @apply text-white relative;
  background-color: var(--bs-primary);
}

.it-header-slim-wrapper {
  @apply py-2 text-sm;
  background-color: var(--bs-primary-dark);
}
```

**MAI**:
```css
❌ @import url('https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/...');
❌ <link rel="stylesheet" href="/themes/sixteen/bootstrap-italia/...">
```

**HTML**: Mantiene classi Bootstrap Italia (per parity)  
**CSS**: Tailwind @apply replica stili

---

### 5. <x-layouts.app>, NOT Custom Layouts

**PERCHÉ**:
- ✅ DRY: Unico layout per tutto il frontend pubblico
- ✅ KISS: Gerarchia chiara
- ✅ Consistency: Tutte le pagine stesse structure

**GERARCHIA**:
```
x-layouts.main (base HTML: DOCTYPE, head, body, scripts)
└── x-layouts.app (frontend wrapper)
    ├── <x-section slug="header" />
    ├── <x-page />
    └── <x-section slug="footer" />
```

**MAI**:
```blade
❌ <x-layouts.design-comuni>
❌ <x-pub_theme::layouts.design-comuni>
❌ Layouts specifici per pagina
```

---

### 6. <x-section slug="header" />, NOT Inline HTML

**PERCHÉ**:
- ✅ DRY: Header definito una volta, riusato ovunque
- ✅ Maintainability: Modifichi section, aggiorni tutte le pagine
- ✅ Flexibility: Passi parametri (`tpl="slim"`)

**COME**:
```blade
<x-layouts.app>
    <x-section slug="header" />
    <x-section slug="footer" tpl="slim" />
</x-layouts.app>
```

**MAI**:
```blade
❌ <header class="it-header-wrapper">...</header> inline
❌ HTML header duplicato in ogni pagina
```

---

### 7. Namespace `pub_theme`, NOT `sixteen::`

**PERCHÉ**:
- ✅ Theme-aware: Funziona con qualsiasi tema
- ✅ Dynamic: `pub_theme` resolve al tema corrente
- ✅ Portability: Cambi tema, blocchi funzionano

**COME**:
```blade
<x-pub_theme::components.blocks.header.slim />
<x-pub_theme::components.blocks.hero.homepage />
```

**MAI**:
```blade
❌ <x-sixteen::blocks.navigation.header-main>
❌ <x-themes::...>
```

---

### 8. Forward-Only Git, NO Reset/Revert

**PERCHÉ**:
- ✅ Storia: Ogni commit è un passo avanti
- ✅ Learning: Errori insegnano, non si cancellano
- ✅ Traceability: Sempre capire "perché" una decisione

**COME**:
```bash
✅ git commit -m "Fix header colors"
✅ git commit -m "Improve footer layout"
```

**MAI**:
```bash
❌ git reset --hard HEAD~5
❌ git revert <commit>
❌ git checkout <old-commit>
```

**FILOSOFIA**:
> "Studiare le versioni vecchie per capire il 'perché', migliorare andando avanti"

---

### 9. Vite Build: outDir: './public' + npm run copy

**PERCHÉ**:
- ✅ Theme isolation: Build indipendente
- ✅ Deployment: Copy a public_html/themes/[theme]/
- ✅ Manifest: Laravel trova assets con `@vite([...], 'themes/Sixteen')`

**COME**:
```js
// vite.config.js
build: {
  outDir: './public',
  manifest: 'manifest.json',
}
```

```json
// package.json
"copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"
```

```bash
cd laravel/Themes/Sixteen
composer update -W
npm install
npm run build
npm run copy
```

**MAI**:
```blade
❌ @vite(['resources/css/app.css']) // NO theme parameter
❌ outDir: '../../public_html/build' // Wrong path
```

---

### 10. HTML Parity: Body Tag (Esclusi Scripts)

**PERCHÉ**:
- ✅ SEO: Stesso HTML structure
- ✅ Accessibility: Stessi ARIA labels
- ✅ Consistency: Match esatto originale

**COME**:
```html
<!-- ORIGINALE -->
<body>
  <div class="skiplink">...</div>
  <header class="it-header-wrapper">...</header>
  <main id="main-container">...</main>
  <footer class="it-footer">...</footer>
</body>

<!-- FIXCITY: DEVE ESSERE IDENTICO -->
<body>
  <div class="skiplink">...</div>
  <header class="it-header-wrapper">...</header>
  <main id="main-container">...</main>
  <footer class="it-footer">...</footer>
</body>
```

**SCRIPTS**: Possono essere diversi (Alpine.js vs Bootstrap Italia JS)

---

## 📚 Documentazione

### Theme Docs
- [MASTER_IMPLEMENTATION_PLAN.md](./MASTER_IMPLEMENTATION_PLAN.md) - Piano completo
- [ARCHITECTURAL_DECISIONS.md](./ARCHITECTURAL_DECISIONS.md) - Questo file
- [HTML_PARITY_VERIFICATION_REPORT.md](./HTML_PARITY_VERIFICATION_REPORT.md) - Verifiche
- [screenshots/](./screenshots/) - Screenshot comparisons

### Module Docs
- `Modules/Cms/docs/design-comuni-pages.md` - Page architecture
- `Modules/Cms/docs/design-comuni-blocks.md` - Block system

### QWEN.md
- Theme detection rule
- Vite manifest fix rule
- Blade architecture rule
- Block pattern rule

---

## 🎯 Principi Guida (RIEPILOGO)

1. **UNO [slug].blade.php** - Mai file specifici
2. **JSON per contenuti** - Blade per struttura
3. **Blocchi universali** - Mai page-specific
4. **Tailwind @apply** - Mai Bootstrap imports
5. **<x-layouts.app>** - Mai custom layouts
6. **<x-section>** - Mai inline HTML
7. **Namespace `pub_theme`** - Mai `sixteen::`
8. **Forward-Only Git** - Mai reset/revert
9. **Vite build + copy** - outDir: './public'
10. **HTML Parity** - Body tag identico (esclusi scripts)

---

## 🔗 References

- **GSD Method**: https://github.com/gsd-build/get-shit-done
- **BMAD Method**: https://github.com/bmad-code-org/BMAD-METHOD
- **Superpowers**: https://github.com/obra/superpowers
- **Ralph Loop**: https://github.com/snarktank/ralph
- **OpenViking**: https://github.com/volcengine/OpenViking

---

**Last Updated**: 2026-04-01  
**Status**: ✅ DEFINITIVE  
**Philosophy**: DRY + KISS + Forward-Only
