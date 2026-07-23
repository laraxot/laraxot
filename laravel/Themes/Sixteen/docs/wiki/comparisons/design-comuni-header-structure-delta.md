---
title: "Segnalazione step 1 — Design Comuni header HTML structure delta"
type: comparison
sources:
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-01-privacy.hbs"
  - "https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-02-dati.hbs"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, header, html-parity, structure, delta, sixteentheme]
related:
  - "../sources/design-comuni-header-reference.md"
  - "../concepts/header-section-owner-rule.md"
  - "../../../../docs/wiki/concepts/header-style-layer-rule.md"
---

# Header HTML Structure Delta — Design Comuni vs Sixteen

## Scope

Confronto strutturale dell'header 3-tier tra **Design Comuni reference** (`segnalazione-02-dati.hbs` / HTML pubblicato) e **implementazione Sixteen** (`v1.blade.php`).

**Importantissimo**: L'header Sixteen è SSoT in `resources/views/components/sections/header/v1.blade.php` per `<x-section slug="header" />`. Non duplicare in pagine Folio o moduli.

## Design Comuni header (ufficiale, da `cmp-base/base.hbs`)

```handlebars
{{#>cmp-base/base title="..." headerActive3=true}}
  <!-- Header 3-tier generato da cmp-base/base -->
{{/cmp-base/base}}
```

Espanso in HTML (`segnalazione-02-dati.html` lines 32–135):

```html
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
  <!-- Tier 1: Slim bar -->
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="it-header-slim-wrapper-content">
            <a class="d-lg-block navbar-brand" target="_blank" href="#" aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda" title="Vai al portale {Nome della Regione}">Nome della Regione</a>
            <div class="it-header-slim-right-zone" role="navigation">
              <!-- Language dropdown + Login CTA -->
              <div class="nav-item dropdown">…</div>
              <a class="btn btn-primary btn-icon btn-full" href="#">Accedi all'area personale</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tier 2: Center (logo + brand) -->
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="it-header-center-content-wrapper">
              <div class="it-brand-wrapper">
                <a href="homepage.html">
                  <svg class="icon" aria-hidden="true"><image xlink:href="logo-comune.svg"/></svg>
                  <div class="it-brand-text">
                    <div class="it-brand-title">Il mio Comune</div>
                    <div class="it-brand-tagline d-none d-md-block">Un comune da vivere</div>
                  </div>
                </a>
              </div>
              <div class="it-right-zone">
                <!-- Socials + search -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tier 3: Navbar -->
    <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="navbar navbar-expand-lg has-megamenu">
              <!-- Mobile toggler + collapsable menu -->
              …
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
```

## Sixteen header (attuale, `v1.blade.php`)

**Owner**: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`  
**Full file**: 324 lines, 3-tier structure identica.

**Delta strutturali già risolti**:
- ✅ Slim wrapper classi corrette (`it-header-slim-wrapper`, `it-header-slim-wrapper-content`)
- ✅ Region name link ora `class="navbar-brand"` (era `text-white` → colore grigio, fix 2026-05-04)
- ✅ Center wrapper + brand text + tagline
- ✅ Navbar wrapper + mobile toggler + menu items (Amministrazione, Novità, Servizi, Vivere il Comune)
- ✅ Alpine.js integration per mobile menu (`x-data="headerMobileNav"`, non Bootstrap JS — evitare `headerMobileNav()` che richiederebbe una funzione globale)

## Delta visivi/strutturali ancora aperti

| # | Elemento | Reference Design Comuni | Sixteen attuale | Priorità | Owner |
|---|----------|------------------------|----------------|----------|-------|
| H1 | **Skiplink** | Presente (`visually-hidden-focusable` → `#main-container`) | Assente in v1.blade.php | P2 | Tema |
| H2 | **`data-bs-target`** su `<header>` | `data-bs-target="#header-nav-wrapper"` | Presente ✅ | — | — |
| H3 | **Region name `d-lg-block`** | `class="d-lg-block navbar-brand"` | Solo `navbar-brand` (Desktop: block sempre; no `d-lg-block` ma `navbar-brand` è `display: block` di default in BI) | P3 | Tema (accettabile) |
| H4 | **Slim right zone ordering** | Language dropdown first, then login CTA | Uguale ✅ | — | — |
| H5 | **Social icons** | `icon-white` class on `<svg>` | Presente ✅ | — | — |
| H6 | **Mobile toggler attributes** | `data-bs-target="#nav4" data-bs-toggle="navbarcollapsible"` | Sixteen usa Alpine: `@click="toggle()"` senza `data-bs-*` | P2 | Tema (Alpine instead of BI JS — **deliberato**) |
| H7 | **Accordion side nav placeholder** | Pages with `cmp-navscroll` show left sidebar on `col-lg-3` | Sixteen: sidebar assente in pages standard | P1 | Tema (da aggiungere in pages che la richiedono) |
| H8 | **Breadcrumb** | `cmp-breadcrumbs` partial sotto `col-12 col-lg-10` | **Assente** in Sixteen v1 — deve essere aggiunto via `@include` o component | **P1** | Tema |
| H9 | **`it-brand-text` flex column** | `display: flex; flex-direction: column;` per tagline sotto title | Presente in `v1.blade.php` con `<div class="it-brand-tagline">` | ✅ | — |

## Specificità CSS: perché `navbar-brand` funziona e `text-white` no

### Caso 1: `.it-header-slim-wrapper a` (generic)

```css
/* style-apply.css:87 */
.it-header-slim-wrapper a {
  color: #191919; /* Grigio scuro — sovrascrive text-white */
}
```

- Specificità: (0,1,1) — una classe (`.it-header-slim-wrapper`) + un elemento (`a`)
- `text-white` è utility Tailwind: (0,0,1) → perde

### Caso 2: `.it-header-slim-wrapper .navbar-brand`

```css
/* header-footer-colors.css:107 */
.it-header-slim-wrapper .navbar-brand {
  color: #ffffff !important; /* Bianco */
}
```

- Specificità: (0,2,0) — due classi → vince su `a` singolo
- `!important` ensures victory even if other rules with equal specificity appear later
- Risultato: testo bianco su sfondo verde scuro ✅

**Lessons learned**:
- Non fidarsi delle utility Tailwind (`text-white`) quando esistono regole base con selettori più specifici
- Usare sempre le classi semantiche Bootstrap Italia (`navbar-brand`, `nav-link`) in combinazione con i CSS del tema
- Verificare `computed style` in DevTools dopo le modifiche

## To-do per full parity

### P1 (critici)
- [ ] **Breadcrumb component** implementare `sections/breadcrumb.blade.php` e iniettare in v1 sotto `col-12 col-lg-10` (dopo header center, prima titolo pagina). Reference: `segnalazione-01-privacy.html` lines 71–77.
- [ ] **Contacts section** (`cmp-contacts`) —底部 grigio dopo main, per pagina segnalazione; implementare come partial riusabile `sections/contacts.blade.php` o partial locale sotto `header/partials/`.

### P2 (importanti)
- [ ] **Side nav (`cmp-navscroll`)** — per pagine con sidenav (step 2 dati ha `col-lg-3` sidebar). Attualmente Sixteen non include sidebar in pages standard; va gestito via CMS block o includes condizionali.
- [ ] **`d-lg-block` su region name** — opzionale; `navbar-brand` di default è `display: block` in BI, ma `d-lg-block` forza block anche su desktop se override. Attualmente accettabile senza.

### P3 (miglioramenti)
- [ ] **Skiplink** — `visually-hidden-focusable` link a `#main-container` — Va aggiunto come primo elemento nel `<body>` di layout principale (`layouts/app.blade.php` o design-comuni layout).

## Commit reference

- Fix region name: `v1.blade.php:71` → `class="navbar-brand"` (non `text-white`)
- Build assets: `npm run build` → `public/assets/app-Dr4I6Xrd.css` contiene `it-header-slim-wrapper .navbar-brand{color:#fff!important}`

## Related

- Design Comuni source reference: [[design-comuni-header-reference.md]]
- Header styling requirements: [[header-styling-requirements.md]]
- Bootstrap ↔ Tailwind mapping: [[bootstrap-tailwind-mapping.md]]
- Project-wide SSoT rule: [[../../../../docs/wiki/concepts/header-section-owner-rule.md]]
- Comparison step 1: [[segnalazione-01-privacy-design-comuni-vs-local-wizard.md]]
