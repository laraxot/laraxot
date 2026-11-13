---
id: "sixteen-fo-08-footer-component-redesign"
type: "epic-story"
title: "Footer component redesign with Design Comuni"
description: "Creare componente footer universale cmp-footer conforme a design-comuni-pagine-statiche (it-footer, it-footer-main, it-footer-list, logo UE, 4 colonne) per sostituire footer custom attuali."
frontmatter:
  github_issue: "#62"
  github_discussion: "#62"
links:
  github_issue: "#62"
  github_discussion: "#62"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html"
  investigation_ref: "docs/investigation/homepage-design-comuni-investigation.md"
---

# Footer Component DesignComuni — Epic Story

## Goal
Creare componente footer universale `cmp-footer` (Blade component) conforme a `design-comuni-pagine-statiche` (`it-footer`, `it-footer-main`, `it-footer-list`, logo UE, 4 colonne) per sostituire tutti i footer custom attuali nel tema Sixteen.

## Background
- Referenza: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html` → sezione footer
- Footer attuali nel tema: `components/blocks/footer-institutional.blade.php`, `components/blocks/header/footer-institutional.blade.php`, `components/blocks/navigation/footer-institutional.blade.php`
- Sprite SVG: `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-{icon}`
- Logo UE: `/themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg`

## Acceptance Criteria

### Struttura Footer (DesignComuni v2.4.0)
- [ ] **Wrapper principale**: `<footer class="it-footer" id="footer">`
- [ ] **Sezione principale** (`it-footer-main`):
  - [ ] Container + row
  - [ ] **Colonna 1 - Logo UE + Brand** (`col-12 footer-items-wrapper logo-wrapper`):
    - [ ] Logo UE: `<img class="ue-logo" src=".../logo-eu-inverted.svg" alt="logo Unione Europea">`
    - [ ] Brand comune: `it-brand-wrapper` → link homepage + SVG `it-pa` + `it-brand-text` con titolo
  - [ ] **Colonna 2 - Link utili** (`col-md-3 footer-items-wrapper`):
    - [ ] Heading: `footer-heading-title` (es. "Link utili")
    - [ ] Lista: `ul.it-footer-list` → `li` → `a.list-item` (Home, Segnalazioni, Categorie, Servizi, Contatti)
  - [ ] **Colonna 3 - Servizi / Amministrazione** (`col-md-6 footer-items-wrapper`):
    - [ ] Heading: `footer-heading-title` (es. "Servizi online")
    - [ ] Grid 2 colonne interne: `row` → 2x `col-6` → `ul.it-footer-list` per gruppo
    - [ ] Gruppi: "Servizi al cittadino", "Servizi alle imprese", "Amministrazione trasparente", "Albo pretorio"
  - [ ] **Colonna 4 - Social / Contatti** (`col-md-3 footer-items-wrapper mt-md-4`):
    - [ ] Heading: `footer-heading-title` (es. "Seguici")
    - [ ] Social inline: `ul.list-inline.social` → `li` → `a.p-1.text-white` → SVG icon (`it-twitter`, `it-facebook`, `it-linkedin`, `it-youtube`, `it-instagram`)
    - [ ] Contatti: indirizzo, telefono, email, PEC
- [ ] **Sezione bottom** (`footer-bottom`):
  - [ ] Container + row + col-12
  - [ ] Copyright: "© {anno} {Comune} - Tutti i diritti riservati"
  - [ ] Link: Privacy Policy, Note legali, Accessibilità, Mappa del sito
  - [ ] Badge: "Realizzato con Design Comuni" + link a design-comuni-pagine-statiche

### Componente Blade
- [ ] File: `resources/views/components/cmp-footer.blade.php`
- [ ] Props:
  - `brandTitle` (string) — nome comune
  - `brandTagline` (string?) — tagline
  - `columns` (array) — 4 colonne con `title`, `links[][label, url, external?]`, `type` (links|social|contacts)
  - `showEuLogo` (bool, default true)
  - `showBottomBar` (bool, default true)
  - `customBottomLinks` (array?) — override link bottom
- [ ] Slot: `brand-logo`, `column-1`, `column-2`, `column-3`, `column-4`, `bottom-bar`

### Accessibilità
- [ ] `id="footer"` per skip link "Vai al footer"
- [ ] Heading hierarchy: `h2` per `footer-heading-title`, `h3` per gruppi interni
- [ ] `aria-label` su liste social ("Social network")
- [ ] Focus-visible su tutti i link
- [ ] Contrasto ≥4.5:1 (background scuro #002b5c, testo bianco)
- [ ] `role="contentinfo"` su footer

### Responsive
- [ ] Mobile: colonne stack verticali (col-12), social inline centrati
- [ ] Tablet: 2 colonne per riga (col-md-6)
- [ ] Desktop: 4 colonne (col-md-3 ciascuna)

## Non-Scopi
- Configurazione tenant-specific — props da config
- Analytics / cookie banner — componenti separati

## Dependency
- Componenti universali: `cmp-brand`, `cmp-footer-list`, `cmp-social-icons`, `cmp-footer-bottom`
- Story correlate: `01-homepage-redesign`, `02-tickets-list-redesign`, `03-tickets-create-segnalazione-flow`, `04-ticket-detail-redesign`, `05-tickets-track-redesign`, `06-categories-list-redesign`, `07-header-component-redesign`