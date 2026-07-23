# Design Comuni Italia - Master Implementation Plan

**Data**: 2026-04-01  
**Status**: 🔄 In Progress  
**Priority**: CRITICAL  
**Methodology**: GSD + BMAD + Superpowers

---

## 🎯 Obiettivo Principale

Replicare **ESATTAMENTE** le 38 pagine statiche di Design Comuni Italia:
- **Source**: https://italia.github.io/design-comuni-pagine-statiche/
- **Target**: http://fixcity.local/it/tests/[slug]
- **HTML Parity**: 100% match dentro `<body>` (esclusi scripts)
- **Visual Parity**: 100% match visivo (screenshot comparison)

---

## 📋 Lista Completa Pagine (38 totali)

### 1. Pagine Generali (9)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 1 | homepage | [homepage.html](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html) | `/it/tests/homepage` | `tests.homepage.json` | ⏳ |
| 2 | domande-frequenti | [domande-frequenti.html](https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html) | `/it/tests/domande-frequenti` | `tests.domande-frequenti.json` | ⏳ |
| 3 | risultati-ricerca | [risultati-ricerca.html](https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html) | `/it/tests/risultati-ricerca` | `tests.risultati-ricerca.json` | ⏳ |
| 4 | argomenti | [argomenti.html](https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html) | `/it/tests/argomenti` | `tests.argomenti.json` | ⏳ |
| 5 | argomento | [argomento.html](https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html) | `/it/tests/argomento` | `tests.argomento.json` | ⏳ |
| 6 | lista-risorse | [lista-risorse.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse.html) | `/it/tests/lista-risorse` | `tests.lista-risorse.json` | ⏳ |
| 7 | lista-categorie | [lista-categorie.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html) | `/it/tests/lista-categorie` | `tests.lista-categorie.json` | ⏳ |
| 8 | lista-risorse-categorie | [lista-risorse-categorie.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse-categorie.html) | `/it/tests/lista-risorse-categorie` | `tests.lista-risorse-categorie.json` | ⏳ |
| 9 | mappa-sito | [mappa-sito.html](https://italia.github.io/design-comuni-pagine-statiche/sito/mappa-sito.html) | `/it/tests/mappa-sito` | `tests.mappa-sito.json` | ⏳ |

### 2. Amministrazione (2)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 10 | amministrazione | [amministrazione.html](https://italia.github.io/design-comuni-pagine-statiche/sito/amministrazione.html) | `/it/tests/amministrazione` | `tests.amministrazione.json` | ⏳ |
| 11 | documenti-dati | [documenti-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/documenti-dati.html) | `/it/tests/documenti-dati` | `tests.documenti-dati.json` | ⏳ |

### 3. Novità (2)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 12 | novita | [novita.html](https://italia.github.io/design-comuni-pagine-statiche/sito/novita.html) | `/it/tests/novita` | `tests.novita.json` | ⏳ |
| 13 | novita-dettaglio | [novita-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/novita-dettaglio.html) | `/it/tests/novita-dettaglio` | `tests.novita-dettaglio.json` | ⏳ |

### 4. Servizi (3)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 14 | servizi | [servizi.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizi.html) | `/it/tests/servizi` | `tests.servizi.json` | ⏳ |
| 15 | servizi-categoria | [servizi-categoria.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizi-categoria.html) | `/it/tests/servizi-categoria` | `tests.servizi-categoria.json` | ⏳ |
| 16 | servizio-dettaglio | [servizio-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizio-dettaglio.html) | `/it/tests/servizio-dettaglio` | `tests.servizio-dettaglio.json` | ⏳ |

### 5. Vivere il Comune / Eventi (2)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 17 | eventi | [eventi.html](https://italia.github.io/design-comuni-pagine-statiche/sito/eventi.html) | `/it/tests/eventi` | `tests.eventi.json` | ⏳ |
| 18 | evento-dettaglio | [evento-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/evento-dettaglio.html) | `/it/tests/evento-dettaglio` | `tests.evento-dettaglio.json` | ⏳ |

### 6. Prenotazione Appuntamento (8)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 19 | appuntamento-01-ufficio | [appuntamento-01-ufficio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio.html) | `/it/tests/appuntamento-01-ufficio` | `tests.appuntamento-01-ufficio.json` | ⏳ |
| 20 | appuntamento-01-ufficio-luogo | [appuntamento-01-ufficio-luogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio-luogo.html) | `/it/tests/appuntamento-01-ufficio-luogo` | `tests.appuntamento-01-ufficio-luogo.json` | ⏳ |
| 21 | appuntamento-02-data-orario | [appuntamento-02-data-orario.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-02-data-orario.html) | `/it/tests/appuntamento-02-data-orario` | `tests.appuntamento-02-data-orario.json` | ⏳ |
| 22 | appuntamento-03-dettagli | [appuntamento-03-dettagli.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-03-dettagli.html) | `/it/tests/appuntamento-03-dettagli` | `tests.appuntamento-03-dettagli.json` | ⏳ |
| 23 | appuntamento-04-richiedente | [appuntamento-04-richiedente.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente.html) | `/it/tests/appuntamento-04-richiedente` | `tests.appuntamento-04-richiedente.json` | ⏳ |
| 24 | appuntamento-04-richiedente-autenticato | [appuntamento-04-richiedente-autenticato.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente-autenticato.html) | `/it/tests/appuntamento-04-richiedente-autenticato` | `tests.appuntamento-04-richiedente-autenticato.json` | ⏳ |
| 25 | appuntamento-05-riepilogo | [appuntamento-05-riepilogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-05-riepilogo.html) | `/it/tests/appuntamento-05-riepilogo` | `tests.appuntamento-05-riepilogo.json` | ⏳ |
| 26 | appuntamento-06-conferma | [appuntamento-06-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-06-conferma.html) | `/it/tests/appuntamento-06-conferma` | `tests.appuntamento-06-conferma.json` | ⏳ |

### 7. Richiesta Assistenza (2)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 27 | assistenza-01-dati | [assistenza-01-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-01-dati.html) | `/it/tests/assistenza-01-dati` | `tests.assistenza-01-dati.json` | ⏳ |
| 28 | assistenza-02-conferma | [assistenza-02-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-02-conferma.html) | `/it/tests/assistenza-02-conferma` | `tests.assistenza-02-conferma.json` | ⏳ |

### 8. Segnalazione Disservizio (7)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 29 | segnalazione-dettaglio | [segnalazione-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html) | `/it/tests/segnalazione-dettaglio` | `tests.segnalazione-dettaglio.json` | ⏳ |
| 30 | segnalazione-01-privacy | [segnalazione-01-privacy.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html) | `/it/tests/segnalazione-01-privacy` | `tests.segnalazione-01-privacy.json` | ⏳ |
| 31 | segnalazione-02-dati | [segnalazione-02-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html) | `/it/tests/segnalazione-02-dati` | `tests.segnalazione-02-dati.json` | ⏳ |
| 32 | segnalazione-03-riepilogo | [segnalazione-03-riepilogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html) | `/it/tests/segnalazione-03-riepilogo` | `tests.segnalazione-03-riepilogo.json` | ⏳ |
| 33 | segnalazione-04-conferma | [segnalazione-04-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html) | `/it/tests/segnalazione-04-conferma` | `tests.segnalazione-04-conferma.json` | ⏳ |
| 34 | segnalazione-area-personale | [segnalazione-area-personale.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html) | `/it/tests/segnalazione-area-personale` | `tests.segnalazione-area-personale.json` | ⏳ |
| 35 | ticket-list | [ticket-list.html](https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html) | `/it/tests/ticket-list` | `tests.ticket-list.json` | ⏳ |

### 9. Pagine Extra (3)
| # | Slug | Originale | Target | JSON | Status |
|---|------|-----------|--------|------|--------|
| 36 | index | N/A (Index tests) | `/it/tests/` | `tests.index.json` | ⏳ |
| 37 | permessi (varianti) | Multiple | `/it/tests/permessi-*` | `tests.permessi-*.json` | ⏳ |
| 38 | pagamenti (varianti) | Multiple | `/it/tests/pagamenti-*` | `tests.pagamenti-*.json` | ⏳ |

---

## 🏗️ Architettura

### 1. Theme Detection

```bash
APP_URL=http://fixcity.local
→ remove protocol: fixcity.local
→ remove www: fixcity.local
→ explode by ".": ['fixcity', 'local']
→ reverse: ['local', 'fixcity']
→ join "/": 'local/fixcity'
→ config path: laravel/config/local/fixcity/xra.php
→ pub_theme: "Sixteen"
→ Theme folder: laravel/Themes/Sixteen/
```

### 2. Layout Hierarchy

```
x-layouts.main (base HTML structure)
└── x-layouts.app (public frontend wrapper)
    ├── <x-section slug="header" />
    │   └── Header blocks (slim, center, navbar)
    ├── <x-page side="content" :slug="$pageSlug" :data="$data" />
    │   └── Content blocks from JSON
    └── <x-section slug="footer" />
        └── Footer blocks
```

### 3. Blade Pattern

**File**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

```php
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
    public array $data = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = ['slug' => $slug];
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

### 4. JSON Content Pattern

**File**: `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`

```json
{
  "id": "tests.homepage",
  "title": {"it": "Homepage", "en": "Homepage"},
  "slug": "tests.homepage",
  "category": "Home",
  "content_blocks": {
    "it": [
      {
        "id": "block-001",
        "type": "header-slim",
        "weight": 1,
        "active": true,
        "data": {
          "view": "pub_theme::components.blocks.header.slim",
          "region": "Nome della Regione"
        }
      }
      // ... more blocks
    ]
  }
}
```

### 5. Block Pattern

**Pattern**: `pub_theme::components.blocks.<type>.<blade>`

**Examples**:
- `pub_theme::components.blocks.header.slim`
- `pub_theme::components.blocks.hero.homepage`
- `pub_theme::components.blocks.news.carousel`
- `pub_theme::components.blocks.footer.main`

**Block Types** (inspirati da):
- https://flowbite.com/blocks/
- https://tailwindcss.com/plus/ui-blocks
- https://daisyui.com/components/
- https://italia.github.io/bootstrap-italia/docs/componenti/introduzione/

---

## 🔧 Fix Critici Applicati

### 1. Vite Manifest Fix ✅

**Problema**: `Vite manifest not found at: /var/www/_bases/base_fixcity_fila5/public_html/themes/Sixteen/manifest.json`

**Soluzione**:
```bash
cd laravel/Themes/Sixteen
composer update -W
npm install
npm run build
npm run copy
```

**vite.config.js**:
```js
build: {
  outDir: './public',
  manifest: 'manifest.json',
}
```

**package.json**:
```json
"copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"
```

### 2. Git Conflicts Resolution ✅

- `vite.config.js` - Fixed merge conflict
- `app.css` - Fixed merge conflict
- `app.blade.php` - Fixed merge conflict

### 3. Blade Architecture Fix ✅

**PRIMA** (❌ SBAGLIATO):
```blade
<x-layouts.design-comuni>
@includeIf('pub_theme::design-comuni.pages.'.$slug)
```

**DOPO** (✅ CORRETTO):
```blade
<x-layouts.app>
@volt('tests.view')
    <x-page side="content" :slug="$pageSlug" :data="$data" />
@endvolt
</x-layouts.app>
```

### 4. Namespace Fix ✅

**PRIMA** (❌ SBAGLIATO):
```blade
<x-sixteen::blocks.navigation.header-main>
```

**DOPO** (✅ CORRETTO):
```blade
<x-pub_theme::blocks.navigation.header-main>
<x-section slug="header" />
```

### 5. Bootstrap Italia Fix ✅

**PRIMA** (❌ SBAGLIATO):
```css
@import url('https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css');
```

**DOPO** (✅ CORRETTO):
```css
@import "tailwindcss";
@import './style-apply.css';
/* Tailwind @apply per replicare classi Bootstrap Italia */
```

---

## 📊 Block Analysis (47 Componenti)

### Tier 1 - Fondamentali (100% - 7 componenti)
1. **cmp-base** - Layout wrapper (100% pagine)
2. **cmp-breadcrumbs** - Navigazione (97% pagine)
3. **cmp-contacts** - Contatti footer (95% pagine)
4. **cmp-rating** - Feedback (87% pagine)
5. **cmp-hero** - Hero section (79% pagine)
6. **cmp-card** - Card generiche (92% pagine)
7. **cmp-button** - Bottoni (85% pagine)

### Tier 2 - Comuni (70%+ - 10 componenti)
8. cmp-navigation - Menu navigazione
9. cmp-search - Search bar
10. cmp-accordion - Contenuti espandibili
11. cmp-form - Form elements
12. cmp-modal - Modali
13. cmp-carousel - Carousel
14. cmp-pagination - Pagination
15. cmp-tags - Tag/argomenti
16. cmp-list - Liste
17. cmp-grid - Griglie

### Tier 3 - Specifici (50%+ - 10 componenti)
18-27. Componenti per flussi multi-step

### Tier 4 - Specializzati (30%+ - 10 componenti)
28-37. Componenti per pagine specifiche

### Tier 5 - Utility (10%+ - 10 componenti)
38-47. Componenti utility e decorativi

---

## 📐 Regole Fondamentali

### DRY (Don't Repeat Yourself)
- ✅ UNO [slug].blade.php per TUTTE le pagine
- ✅ MAI file specifici (homepage.blade.php, argomenti.blade.php, etc.)
- ✅ SEMPRE dinamico con slug
- ✅ JSON per contenuti
- ✅ Blade per struttura

### KISS (Keep It Simple, Stupid)
- ✅ `<x-layouts.app>` (NOT custom layouts)
- ✅ `<x-section slug="header" />` (NOT inline HTML)
- ✅ `<x-page>` component (NOT @includeIf)
- ✅ Tailwind @apply (NOT Bootstrap imports)

### Universal Blocks
- ✅ Blocchi riutilizzabili (NOT page-specific)
- ✅ Pattern: `pub_theme::components.blocks.<type>.<blade>`
- ✅ <tipo> generico (hero, card, navigation, etc.)
- ✅ NOT `tests.argomenti` (page-specific)

### Forward-Only Git
- ✅ MAI `git reset`, `git revert`, `git checkout` vecchi commit
- ✅ Studiare versioni vecchie per capire il "perché"
- ✅ Migliorare, non ripristinare

---

## 📚 Documentazione Richiesta

### Theme Docs (laravel/Themes/Sixteen/docs/)
- [x] `design-comuni/MASTER_IMPLEMENTATION_PLAN.md` (questo file)
- [ ] `design-comuni/HTML_PARITY_VERIFICATION_REPORT.md` (per ogni pagina)
- [ ] `design-comuni/screenshots/` (screenshot comparisons)
- [ ] `design-comuni/screenshots/[pagina]-comparison.md` (analisi differenze)
- [ ] `blocks/README.md` (block index)
- [ ] `blocks/[tipo]/README.md` (block type docs)

### Module Docs (laravel/Modules/Cms/docs/)
- [ ] `design-comuni-pages.md` (page architecture)
- [ ] `design-comuni-blocks.md` (block system)
- [ ] `design-comuni-json-structure.md` (JSON content format)

### QWEN.md Updates
- [ ] Theme detection rule
- [ ] Vite manifest fix rule
- [ ] Blade architecture rule
- [ ] Block pattern rule
- [ ] Bootstrap Italia → Tailwind rule

---

## 🚀 Implementation Workflow

### Per Ogni Pagina (38 volte)

1. **Scarica Originale**
   ```bash
   curl -s https://italia.github.io/design-comuni-pagine-statiche/sito/[pagina].html > /tmp/agid-[pagina].html
   ```

2. **Analizza HTML**
   - Estrai body (esclusi scripts)
   - Identifica sezioni
   - Identifica blocchi

3. **Crea JSON**
   - `laravel/config/local/fixcity/database/content/pages/tests.[pagina].json`
   - Definisci blocchi con type generici
   - Set weight per ordinamento

4. **Crea/Verifica Block Views**
   - `laravel/Themes/Sixteen/resources/views/components/blocks/<type>/<blade>.blade.php`
   - Usa classi Bootstrap Italia (replicate con Tailwind @apply)

5. **Test Pagina**
   ```
   http://fixcity.local/it/tests/[pagina]
   ```

6. **Screenshot Comparison**
   - Screenshot originale
   - Screenshot FixCity
   - Analisi differenze
   - Save in `docs/design-comuni/screenshots/[pagina]/`

7. **Documenta**
   - `docs/design-comuni/screenshots/[pagina]-comparison.md`
   - HTML parity report
   - Visual parity report

---

## ✅ Checklist Generale

### Infrastructure ✅
- [x] Theme detection verified (Sixteen)
- [x] Vite manifest fixed
- [x] Build pipeline working (composer, npm, build, copy)
- [x] Git conflicts resolved

### Architecture ✅
- [x] [slug].blade.php corrected (Folio + Volt)
- [x] index.blade.php corrected
- [x] x-layouts.app extends x-layouts.main
- [x] <x-section slug="header" /> pattern
- [x] <x-page> component pattern

### Content ✅
- [ ] 38 JSON files created
- [ ] 47 block views created
- [ ] All blocks use generic types
- [ ] No page-specific blocks

### Parity ✅
- [ ] HTML parity 100% (body tag, esclusi scripts)
- [ ] Visual parity 100% (screenshot match)
- [ ] Header parity (colors, spacing, logo)
- [ ] Footer parity (layout, links, social)

### Documentation ✅
- [ ] Theme docs updated (bidirectional links)
- [ ] Module docs updated (bidirectional links)
- [ ] QWEN.md updated
- [ ] Screenshot analysis created
- [ ] Block index created

### GitHub ✅
- [ ] Issues created (one per page)
- [ ] Discussions created (architecture decisions)
- [ ] Labels added (design-comuni, html-parity, blocks)

---

## 🎯 Next Steps (Immediate)

1. **Homepage HTML Parity** (Priority: CRITICAL)
   - Verify body HTML exact match
   - Fix header (colors, logo, slogan visibility)
   - Fix footer (layout, links)
   - Screenshot comparison

2. **Block Creation** (Priority: HIGH)
   - Create missing Tier 1 blocks
   - Document block types
   - Ensure reusability

3. **JSON Content** (Priority: HIGH)
   - Create all 38 JSON files
   - Verify block references
   - Test with <x-page>

4. **Documentation** (Priority: MEDIUM)
   - Update all docs folders
   - Add bidirectional links
   - Create screenshot comparisons

---

## 📖 References

- **Design Comuni**: https://github.com/italia/design-comuni-pagine-statiche
- **Live Pages**: https://italia.github.io/design-comuni-pagine-statiche/
- **Bootstrap Italia**: https://italia.github.io/bootstrap-italia/
- **Flowbite Blocks**: https://flowbite.com/blocks/
- **Tailwind UI**: https://tailwindcss.com/plus/ui-blocks
- **DaisyUI**: https://daisyui.com/components/

---

**Last Updated**: 2026-04-01  
**Status**: 🔄 IN PROGRESS  
**Next Review**: After homepage HTML parity verification
