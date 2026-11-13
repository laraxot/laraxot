---
id: "sixteen-fo-07-header-component-redesign"
type: "epic-story"
title: "Header component redesign with Design Comuni"
description: "Creare componente header universale cmp-header conforme a design-comuni-pagine-statiche (it-header-wrapper, it-header-slim-wrapper, it-nav-wrapper, it-brand-wrapper, it-search-wrapper) per sostituire header custom attuali."
frontmatter:
  github_issue: "#61"
  github_discussion: "#61"
links:
  github_issue: "#61"
  github_discussion: "#61"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html"
  investigation_ref: "docs/investigation/homepage-design-comuni-investigation.md"
---

# Header Component DesignComuni — Epic Story

## Goal
Creare componente header universale `cmp-header` (Blade component) conforme a `design-comuni-pagine-statiche` (`it-header-wrapper`, `it-header-slim-wrapper`, `it-nav-wrapper`, `it-brand-wrapper`, `it-search-wrapper`) per sostituire tutti gli header custom attuali nel tema Sixteen.

## Background
- Referenza: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html` → sezione header
- Header attuali nel tema: `components/blocks/header/*.blade.php` (main, slim, exact, navbar, nav-wrapper, page, top-bar)
- Sprite SVG: `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-{icon}`
- Logo Comune: `/themes/Sixteen/design-comuni/assets/images/logo-comune.svg`
- Logo UE: `/themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg`

## Acceptance Criteria

### Struttura Header (DesignComuni v2.4.0)
- [ ] **Wrapper principale**: `<header class="it-header-wrapper">`
- [ ] **Barra slim** (`it-header-slim-wrapper`):
  - [ ] Container + row + col-12
  - [ ] **Logo Comune** (`it-brand-wrapper`):
    - [ ] Link a homepage (`/`)
    - [ ] SVG logo comune (`<use href=".../logo-comune.svg">`)
    - [ ] Testo brand: `it-brand-title` (nome comune) + `it-brand-tagline` (es. "Comune di...")
  - [ ] **Zona destra** (`it-header-slim-right-zone`):
    - [ ] **Area Personale** (se autenticato): btn primary con icona `it-user` + "Area Personale"
    - [ ] **Login/Register** (se non autenticato): btn outline "Accedi" + btn primary "Registrati"
    - [ ] **Ricerca**: button `search-link` con icona `it-search` + `aria-label="Cerca nel sito"` → apre modal ricerca
- [ ] **Navbar principale** (`it-nav-wrapper`):
  - [ ] Container + `it-header-center-wrapper` + `it-header-center-content-wrapper`
  - [ ] **Navigation** (`it-nav`): lista link principali (Home, Segnalazioni, Categorie, Servizi, Amministrazione, Novità, Eventi)
  - [ ] **Mobile toggle**: hamburger button (`navbar-toggler`) per responsive
  - [ ] **Lingua**: dropdown selezione lingua (IT/EN) con `localizeURL`

### Componente Blade
- [ ] File: `resources/views/components/cmp-header.blade.php`
- [ ] Props:
  - `brandTitle` (string) — nome comune
  - `brandTagline` (string?) — tagline
  - `navigation` (array) — array di `[label, url, children?]`
  - `showSearch` (bool, default true)
  - `showAuth` (bool, default true)
  - `currentRoute` (string?) — per active state
- [ ] Slot: `brand-logo` (per override logo), `nav-right` (per elementi custom a destra)
- [ ] Alpine.js per: mobile menu toggle, search modal toggle, dropdown lingua

### Varianti
- [ ] **Slim only**: per pagine interne (solo `it-header-slim-wrapper` + navbar collassata)
- [ ] **Full**: homepage (slim + navbar completa)
- [ ] **Transparent**: per hero fullscreen (background transparent, testo bianco)

### Accessibilità
- [ ] Skip link: "Vai ai contenuti" (`href="#main-container"`) + "Vai al footer" (`href="#footer"`) — **primo elemento in body**
- [ ] `aria-label` su tutti i controlli
- [ ] Focus-visible su tutti gli elementi interattivi
- [ ] `aria-expanded` / `aria-controls` su dropdown e mobile menu
- [ ] `role="search"` su form ricerca
- [ ] Contrasto testi ≥4.5:1 su entrambi i temi (light/dark)

### Responsive
- [ ] Mobile (<768px): navbar collassata in drawer laterale (Alpine.js `x-data` + `x-show`)
- [ ] Tablet (768-991px): navbar compatta, logo ridotto
- [ ] Desktop (≥992px): navbar completa, tutti i link visibili

## Non-Scopi
- Logica autenticazione — usa `auth()` helper Laravel
- Ricerca backend — usa Action esistente / Scout
- Multi-tenant branding — props da config tenant

## Dependency
- Componenti universali: `cmp-button`, `cmp-dropdown`, `cmp-search-modal`, `cmp-language-switcher`
- Story correlate: `01-homepage-redesign`, `02-tickets-list-redesign`, `03-tickets-create-segnalazione-flow`, `04-ticket-detail-redesign`, `05-tickets-track-redesign`, `06-categories-list-redesign`, `08-footer-component-redesign`