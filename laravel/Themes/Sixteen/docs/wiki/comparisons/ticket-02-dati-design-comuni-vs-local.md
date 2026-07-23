---
title: "Segnalazione step2 (dati) — Design Comuni static vs wizard locale"
type: comparison
sources:
  - "https://github.com/italia/design-comuni-pagine-statiche"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, segnalazione-crea, wizard, dati, tailwind, lit, html-parity, header]
related:
  - "../concepts/theme-css-only-parity-rule.md"
  - "../concepts/design-comuni-site-wide-component-css-rule.md"
  - "../../../../../Modules/Fixcity/docs/wiki/comparisons/segnalazione-02-dati-design-comuni-vs-local.md"
  - "../../../../../_bmad-output/implementation-artifacts/7-106-segnalazione-crea-step2-header-visual-parity.md"
---

# Confronto: `segnalazione-02-dati` (statico) vs `/it/tests/segnalazione-crea?step=form.data::data::wizard-step` (step2)

## Fonti

| Fonte | Descrizione |
|--------|----------------|
| [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) | Template ufficiali comuni: Handlebars, SCSS, Webpack; build in `dist/` |
| [Pagina statica step2](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html) | Reference HTML pubblicato |
| Locale | `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.data::data::wizard-step` — Step2 (dati) del wizard |

---

## HTML di riferimento (Design Comuni — Header step2)

```html
<header class="it-header-wrapper">
  <!-- Slim bar: regione + lingua + accedi -->
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="it-header-slim-wrapper-content">
        <a class="navbar-brand text-white">Nome della Regione</a>
        <!-- language switcher + login btn -->
        <a class="btn btn-primary btn-icon btn-full" href="#">Accedi all'area personale</a>
      </div>
    </div>
  </div>
  
  <!-- Center: logo + brand + social + cerca -->
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <div class="it-brand-wrapper">
        <a href="homepage.html">
          <svg><!-- logo-comune.svg --></svg>
          <div class="it-brand-text">
            <div class="it-brand-title">Il mio Comune</div>
            <div class="it-brand-tagline d-none d-md-block">Un comune da vivere</div>
          </div>
        </a>
      </div>
      <!-- Socials + Cerca -->
    </div>
    
    <!-- Navbar: Amministrazione | Novità | Servizi | Vivere il Comune -->
    <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
      <nav aria-label="Principale">
        <ul class="navbar-nav" data-element="main-navigation">
          <li class="nav-item"><a class="nav-link" href="#">Amministrazione</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Novità</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Servizi</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Vivere il Comune</a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>
```

**Note header step2**:
- Same 3-tier structure as step1
- "Servizi" link has `class="nav-link active"` (active state)
- All other elements identical to step1

---

## Header Locale (Sixteen Theme — v1.blade.php)

**File**: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php` (324 linee)

### Elementi presenti ✅

| Elemento Design Comuni | Implementazione Locale | Stato Parity |
|------------------------|----------------------|-----------------|
| `it-header-wrapper` | `class="it-header-wrapper"` (line 63) | ✅ Match |
| `it-header-slim-wrapper` | `class="it-header-slim-wrapper"` (line 65) | ✅ Match |
| `navbar-brand text-white` | `class="navbar-brand text-white"` (line 71) | ✅ Match |
| `it-header-slim-right-zone` | `@include(language-switcher)` + guest/user dropdown | ✅ Match |
| `it-nav-wrapper` | `class="it-nav-wrapper"` (line 96) | ✅ Match |
| `it-brand-wrapper` | `class="it-brand-wrapper"` (line 107) | ✅ Match |
| Logo SVG 82x82 | `<svg width="82" height="82">` (line 109) | ✅ Match |
| `it-brand-text` | `class="it-brand-text"` (line 112) | ✅ Match |
| `it-brand-title` | `class="it-brand-title"` (line 113) | ✅ Match |
| `it-brand-tagline d-none d-md-block` | `class="it-brand-tagline d-none d-md-block"` (line 114) | ✅ Match |
| `it-header-navbar-wrapper` | `class="it-header-navbar-wrapper"` (line 186) | ✅ Match |
| `navbar-nav` + `data-element="main-navigation"` | `class="navbar-nav"` (line 218) | ✅ Match |
| Nav items: Amministrazione, Novità, Servizi, Vivere il Comune | Presenti (lines 219-238) | ✅ Match |
| `nav-link active` on Servizi | `class="nav-link active"` (line 230) | ✅ Match |

### Classi Bootstrap→Tailwind (in `app.css`)

| Bootstrap Class | Tailwind Equivalent (app.css) | Stato |
|----------------|-------------------------------|-------|
| `navbar-brand` | `display: inline-block; padding: 0.5rem; font-size: 1.25rem; font-weight: 600;` | ✅ Mapped |
| `text-white` | `color: #ffffff;` | ✅ Native Tailwind |
| `it-header-slim-wrapper` | `background-color: #0066CC;` (from design-comuni-tokens.css) | ✅ Mapped |
| `nav-link` | `padding: 0.5rem 1rem; color: #0066CC; text-decoration: none;` | ✅ Mapped |
| `nav-link.active` | `font-weight: 600; color: #0066CC; text-decoration: underline;` | ✅ Mapped |
| `it-brand-title` | `font-size: 1.5rem; font-weight: 700; line-height: 1.2;` | ✅ Mapped |
| `it-brand-tagline` | `font-size: 0.875rem; color: #6c757d;` | ✅ Mapped |

---

## Stepper Step2 (Design Comuni vs Locale)

### Design Comuni (segnalazione-02-dati.html)

```html
<div class="steppers">
  <div class="steppers-header">
    <ul>
      <li class="">Autorizzazioni e condizioni</li>
      <li class="active">Dati di segnalazione
        <span class="visually-hidden">Attivo</span>
      </li>
      <li class="">Riepilogo</li>
    </ul>
    <span class="steppers-index" aria-hidden="true">2/3</span>
  </div>
</div>
```

### Locale (Filament Wizard — Step2)

**Rendered via**: `CreateTicketWizardWidget` (Filament v5 Wizard)

Expected output:
- Stepper shows "1/3" → "2/3" → "3/3" 
- Step2 label: "Dati di segnalazione" (active)
- Step1 label: "Autorizzazioni e condizioni" (completed)
- Step3 label: "Riepilogo" (pending)

**CSS Class Mapping** (from app.css):
- `.steppers` → `display: flex; justify-content: space-between; margin-bottom: 2rem;`
- `.steppers-header.active` → `background-color: #0066CC; color: white; font-weight: 600;`
- `.steppers-index` → `font-size: 0.875rem; color: #6c757d;`

---

## Gaps & Action Items

### Header (Priority: LOW — already parity ✅)

- [x] Slim bar background color (`#0066CC`) — ✅ in design-comuni-tokens.css
- [x] Brand logo size (82x82) — ✅ correct in v1.blade.php
- [x] Active nav state ("Servizi") — ✅ `class="nav-link active"` present
- [x] Mobile hamburger — ✅ Alpine.js `x-data="headerMobileNav"` (`Alpine.data`, senza parentesi)
- [ ] **Verify**: Screenshot comparison local vs Design Comuni for visual confirmation

### Stepper (Priority: MEDIUM)

- [ ] Verify Filament Wizard renders with correct step labels at step2
- [ ] Check "2/3" index displays correctly
- [ ] Verify "Dati di segnalazione" is `active` at step2
- [ ] Compare with Design Comuni: visually identical stepper style

---

## Verifica Browser

1. **Local**: `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.data::data::wizard-step`
2. **Design Comuni**: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`
3. **Screenshot comparison**: Header section only (slim + center + navbar)

---

## Wiki Updates (After Verification)

- [ ] Update `laravel/Themes/Sixteen/docs/wiki/concepts/segnalazione-visual-parity-correction-plan.md` (add step2 header parity)
- [ ] Update `docs/wiki/log.md` (add entry for step2 comparison)
- [ ] QMD update: `qmd update --name theme-sixteen`

---

*Comparison created: 2026-05-04 — Story 7-106 prerequisite*
