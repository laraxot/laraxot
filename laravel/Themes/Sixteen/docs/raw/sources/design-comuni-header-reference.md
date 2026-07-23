---
title: "Design Comuni Header Structure Reference"
type: source
sources:
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-02-dati.hbs"
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-01-privacy.hbs"
  - "https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/main/src/pages/sito/segnalazione-02-dati.hbs"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, header, reference, bootstrap-italia, handlebars, source-of-truth]
---

# Design Comuni Header Structure — Source Reference

## Sorgente ufficiale

Repository: [italia/design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche)  
Template Handlebars: `src/pages/sito/segnalazione-02-dati.hbs`, `segnalazione-01-privacy.hbs`  
Pagina pubblicata: [segnalazione-02-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html)

## Struttura header ufficiale (3-tier)

```handlebars
{{#>cmp-base/base headerActive3=true}}
  <!-- Header 3-tier embedded in cmp-base/base partial -->
{{/cmp-base/base}}
```

Il partial `cmp-base/base.hbs` include:

1. **Slim wrapper** — `it-header-slim-wrapper`  
   Regione + lingua + CTA area personale
2. **Center wrapper** — `it-header-center-wrapper`  
   Logo + brand text + social + search
3. **Navbar wrapper** — `it-header-navbar-wrapper`  
   Menu principale: Amministrazione | Novità | Servizi | Vivere il Comune + secondario

## Region name link (riga 32 in segnalazione-02-dati.html)

```html
<a class="d-lg-block navbar-brand" target="_blank" href="#" 
   aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda" 
   title="Vai al portale {Nome della Regione}">
  Nome della Regione
</a>
```

**Classi obbligatorie**: `navbar-brand` (o `d-lg-block navbar-brand`)  
**Colore testo**: white (`#fff`) — garantito da CSS Bootstrap Italia / Design Comuni  
**Anti-pattern**: Non usare `text-white` su `<a>` generico — viene sovrascritto da `.it-header-slim-wrapper a { color: #191919 }` in `style-apply.css`.

## Mapping componenti Design Comuni → Sixteen

| Design Comuni (HBS partial) | Sixteen equivalent | Owner file | Note |
|-----------------------------|-------------------|------------|------|
| `cmp-base/base` | Header 3-tier Blade orchestrator | `sections/header/v1.blade.php` | Owner SSoT |
| `cmp-breadcrumbs/cmp-breadcrumbs` | Breadcrumbs component | `components/sections/breadcrumb.blade.php` | Da implementare |
| `cmp-heading/cmp-heading` | Title + subtitle | `components/blocks/heading.blade.php` | Esistente |
| `cmp-info-progress/cmp-info-progress` | Stepper widget | `components/blocks/stepper.blade.php` | Esistente |
| `cmp-navscroll/cmp-navscroll` | Side navigation | `components/blocks/navscroll.blade.php` | Da verificare |
| `cmp-card/cmp-card-content-box` | Card with header/subtitle | `components/blocks/card.blade.php` | Da mappare |
| `cmp-input-autocomplete/input-autocomplete` | Autocomplete input | Custom Filament field | Modulo-specifico |
| `cmp-select/select` | Select dropdown | Filament `Select` | Modulo-specifico |
| `cmp-input/input` | Text input | Filament `TextInput` | Modulo-specifico |
| `cmp-text-area/text-area` | Textarea | Filament `Textarea` | Modulo-specifico |
| `cmp-button/cmp-button` | Button component | `components/blocks/buttons/primary.blade.php` | Esistente |
| `cmp-info-button-card/cmp-info-button-card` | User info card | Custom Filament | Modulo-specifico |
| `cmp-nav-steps/cmp-nav-steps` | Wizard step navigation | Custom wizard component | Modulo-specifico |
| `cmp-contacts/cmp-contacts` | Contact card section | `components/blocks/contacts.blade.php` | Da implementare |

## Token CSS Design Comuni rilevanti per header

Da `bootstrap-italia-comuni.css` e `bootstrap-italia.css`:

```css
.it-header-slim-wrapper {
  background-color: #00402b; /* Dark green */
  color: #fff;
}

.it-header-slim-wrapper a,
.it-header-slim-wrapper .nav-link {
  color: #fff !important;
}

.it-header-slim-wrapper .navbar-brand {
  color: #fff !important;
  font-size: 14px;
  line-height: 21px;
  font-weight: 400;
  padding: 12px 0;
}

.it-header-center-wrapper {
  background-color: #007a52; /* Primary green */
  color: #fff;
}

.it-header-navbar-wrapper {
  background-color: #007a52;
  color: #fff;
}

.it-header-navbar-wrapper .nav-link {
  color: #fff !important;
  font-weight: 700;
}
```

## Regole di parity (da osservare)

1. **Region name link**: usare `class="navbar-brand"` (non `text-white`), specificità garantisce colore bianco.
2. **Slim header height**: 48px (padding 0 su wrapper, contenuto 48px di altezza).
3. **Center header height**: 80px (padding verticale 1.25rem = 20px × 2 + logo 82px ≈ 80px).
4. **Navbar**: verde `#007a52`, link bianchi, active underline bianco al centro.
5. **No inline CSS**: tutto nei file CSS del tema Sixteen, mai nei Blade module.

## Come usare questa sorgente

- Copiare la struttura HTML da `segnalazione-02-dati.html` (non da `cmp-base/base.hbs` che è parzializzato)
- Mappare ogni classi Bootstrap Italia in Tailwind usando `bootstrap-tailwind-mapping.md`
- Verificare che il CSS generato abbia la stessa cascade: `header-footer-colors.css` → `app.css` (importato per ultimo)
- Testare in browser all'URL reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`

## File Sixteen owner

- Markup: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`
- CSS: `laravel/Themes/Sixteen/resources/css/header-footer-colors.css`, `app.css`
- Build: `npm run build` + `npm run copy` in `laravel/Themes/Sixteen/`

## Related

- [[../concepts/header-styling-requirements.md]] — requisiti header Sixteen
- [[../concepts/design-comuni-header-green-navbar-rule.md]] — navbar verde obbligatoria
- [[../concepts/bootstrap-tailwind-mapping.md]] — mappa completa Bootstrap → Tailwind
- [[segnalazione-01-privacy-design-comuni-vs-local-wizard.md]] — delta visivo step 1
- Project wiki: [[../../../../docs/wiki/concepts/header-section-owner-rule.md]]
