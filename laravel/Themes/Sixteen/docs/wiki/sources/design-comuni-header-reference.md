---
title: "Design Comuni Header Source Reference"
type: source
sources:
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-02-dati.hbs"
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-01-privacy.hbs"
  - "https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/main/src/pages/sito/segnalazione-02-dati.hbs"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, header, reference, bootstrap-italia, handlebars, source-of-truth]
related:
  - "../concepts/header-styling-requirements.md"
  - "../concepts/design-comuni-header-green-navbar-rule.md"
  - "../concepts/bootstrap-tailwind-mapping.md"
  - "../comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md"
  - "../../../../docs/wiki/concepts/header-section-owner-rule.md"
---

# Design Comuni Header Structure — Source Reference

## Sorgente ufficiale

Repository: [italia/design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche)  
Template Handlebars: `src/pages/sito/segnalazione-02-dati.hbs`, `segnalazione-01-privacy.hbs`  
Pagina pubblicata: [segnalazione-02-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html)

## Struttura header ufficiale (3-tier)

```handlebars
{{#>cmp-base/base title="Segnalazione disservizio - Nome del Comune" headerActive3=true}}
{{/cmp-base/base}}
```

Il partial `cmp-base/base.hbs` compone l'header 3-tier:

1. **Slim wrapper** — `.it-header-slim-wrapper`  
   Regione + selettore lingua + CTA "Accedi all'area personale"
2. **Center wrapper** — `.it-header-center-wrapper`  
   Logo + brand text (comune + tagline) + social links + search
3. **Navbar wrapper** — `.it-header-navbar-wrapper`  
   Menu principale (Amministrazione, Novità, Servizi, Vivere il Comune) + secondario

## Region name link — Specifiche esatte

Da `segnalazione-02-dati.html` (line 45):

```html
<a class="d-lg-block navbar-brand" target="_blank" href="#" 
   aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda" 
   title="Vai al portale {Nome della Regione}">
  Nome della Regione
</a>
```

**Classi CSS obbligatorie**: `navbar-brand` (o `d-lg-block navbar-brand` per display control)  
**Colore testo**: white (`#fff`) — garantito da regola CSS `.it-header-slim-wrapper .navbar-brand { color: #fff !important; }`  
**Anti-pattern critico**: Non usare `class="text-white"` su `<a>` generico. Il selettore `.it-header-slim-wrapper a` in `style-apply.css:87` imposta `color: #191919` (grigio scuro) che sovrascrive `text-white`. La specificità di `.navbar-brand` (classe + `!important`) vince su `.it-header-slim-wrapper a`.

**Fix applicato in Sixteen**: `v1.blade.php:71` ora usa `class="navbar-brand"` (era `text-white`). Confermato da CSS compilato: `it-header-slim-wrapper .navbar-brand{color:#fff!important}`.

## Partial/components mapping (Design Comuni → Sixteen)

| Design Comuni (HBS partial) | Sixteen equivalent | Owner file | Status |
|-----------------------------|-------------------|------------|--------|
| `cmp-base/base` | Header 3-tier orchestrator | `sections/header/v1.blade.php` | ✅ Owner SSoT |
| `cmp-breadcrumbs/cmp-breadcrumbs` | Breadcrumbs | `sections/breadcrumb.blade.php` | ⚠️ Da implementare |
| `cmp-heading/cmp-heading` | Page title | `blocks/heading.blade.php` | ✅ Esistente |
| `cmp-info-progress/cmp-info-progress` | Stepper | `blocks/stepper.blade.php` | ✅ Esistente |
| `cmp-navscroll/cmp-navscroll` | Side nav (accordion) | `blocks/navscroll.blade.php` | ⚠️ Da verificare |
| `cmp-card/cmp-card-content-box` | Card with header | `blocks/card.blade.php` | ⚠️ Da mappare |
| `cmp-input-autocomplete/input-autocomplete` | Autocomplete location | Custom Filament field (Geo module) | ✅ Modulo |
| `cmp-select/select` | Select (inefficiency type) | Filament `Select` | ✅ Modulo |
| `cmp-input/input` | TextInput (title) | Filament `TextInput` | ✅ Modulo |
| `cmp-text-area/text-area` | Textarea (details) | Filament `Textarea` | ✅ Modulo |
| `cmp-button/cmp-button` | Button component | `blocks/buttons/primary.blade.php` | ✅ Esistente |
| `cmp-info-button-card/cmp-info-button-card` | User info card | Custom Filament (Fixcity) | ✅ Modulo |
| `cmp-nav-steps/cmp-nav-steps` | Wizard step nav | Custom wizard (Fixcity) | ✅ Modulo |
| `cmp-contacts/cmp-contacts` | Contact card section | `blocks/contacts.blade.php` | ⚠️ Da implementare |

## Token CSS header (priorità cascade)

**Import order in `app.css`** (le ultime regole vincono):

1. `@import './style-apply.css';` — Bootstrap Italia converted to Tailwind (early)
2. `@import './components/header-footer-colors.css';` — **QUI: `.it-header-slim-wrapper .navbar-brand { color:#fff!important; }`**
3. `@import './design-comuni-header-fix.css';` — override specifici (green backgrounds)
4. `@import './segnalazione-parity.css';` — parity page-specific (ultimo, vince)

**Regole critiche**:

```css
/* header-footer-colors.css — row 24–110 */
.it-header-slim-wrapper {
  background-color: var(--color-italia-dark) !important; /* #00402b */
  color: #FFFFFF !important;
}

.it-header-slim-wrapper a,
.it-header-slim-wrapper .nav-link {
  color: #FFFFFF !important; /* This is overridden by style-apply.css for generic <a> */
}

.it-header-slim-wrapper .navbar-brand {
  color: #ffffff !important; /* ✅ Specificity wins — use this class */
}

/* style-apply.css — row 87–90 (OVERRIDES generic <a> inside slim wrapper) */
.it-header-slim-wrapper a {
  @apply no-underline text-base;
  color: #191919; /* ❌ Dark gray — overrides text-white on plain <a> */
}
```

## Parity checklist header (step 2 — segnalazione-02-dati)

- ✅ Slim wrapper bg: `#00402b` (dark green) — `header-footer-colors.css:24`
- ✅ Region name link: `class="navbar-brand"` → white text — `v1.blade.php:71`
- ✅ Center wrapper bg: `#007a52` (primary green) — `header-footer-colors.css:35`
- ✅ Navbar wrapper bg: `#007a52` — `design-comuni-header-fix.css:73–75`
- ✅ Navbar link colore: white (`#fff`) — `header-footer-colors.css:72–74`
- ✅ Navbar active underline: white centered — `header-footer-colors.css:90–104`
- ⚠️ Breadcrumb: **mancante** in Sixteen — priority P1 (vedi `segnalazione-01-privacy` comparison)
- ⚠️ Side nav (`cmp-navscroll`): da verificare parity con accordion Design Comuni
- ⚠️ Contact section (`cmp-contacts`): **assente** nel tema Sixteen — priority P1

## Regole permanenti (da non violare)

1. **Niente inline `<style>` in Blade modulo** — CSS solo nel tema Sixteen (`resources/css/`)
2. **Header owner unico**: `sections/header/v1.blade.php` — non duplicare inFolio pages o moduli
3. **Build + copy obbligatori**: dopo ogni modifica CSS, `npm run build && npm run copy` in `laravel/Themes/Sixteen/`
4. **Specificity matters**: usare `navbar-brand` per region name, non `text-white` su `<a>` generico
5. **Verifica browser**: controllare computed style in DevTools; non fidarsi di `grep` CSS — la cascade è complessa

## Come usare questa sorgente

1. **Copy HTML structure** da `segnalazione-02-dati.html` (non da HBS parzializzato) per vedere il DOM completo
2. **Map Bootstrap → Tailwind** usando `bootstrap-tailwind-mapping.md`
3. **Check cascade**: le regole finali stanno in `segnalazione-parity.css` (importato per ultimo in `app.css`)
4. **Validate in browser**: allineamento visivo con reference ufficiale su `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`

## File ownership Sixteen

- **Markup owner**: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`
- **CSS owner**: `laravel/Themes/Sixteen/resources/css/` — `header-footer-colors.css`, `design-comuni-header-fix.css`, `segnalazione-parity.css`
- **Assets build**: `laravel/Themes/Sixteen/resources/css/app.css` (entry) → Vite → `public/assets/app-*.css`
- **Deploy copy**: `npm run copy` → `public_html/themes/Sixteen/` (per frontoffice non-Vite)

## Related in project wiki

- [[../../../../../docs/wiki/concepts/header-section-owner-rule.md]] — SSoT header rule (project-wide)
- [[../../../../../docs/wiki/concepts/header-color-parity.md]] — slim vs center green distinction
- [[../../../../../docs/wiki/concepts/visual-parity-verification-rule.md]] — after every change, verify in browser
- [[segnalazione-01-privacy-design-comuni-vs-local-wizard.md]] — step 1 comparison (breadcrumb missing, etc.)

## Log updates

- **2026-05-04**: Region name link fix: `text-white` → `navbar-brand` (specificity issue). Wiki created.
- **2026-05-04**: Raw sources ingested: `docs/raw/articles/design-comuni/segnalazione-01-privacy.html`, `segnalazione-02-dati.html`
