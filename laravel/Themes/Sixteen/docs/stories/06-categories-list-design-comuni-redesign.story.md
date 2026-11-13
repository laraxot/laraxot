---
id: "sixteen-fo-06-categories-list-redesign"
type: "epic-story"
title: "Categories list page redesign with Design Comuni"
description: "Riallineare la pagina lista categorie (lista-categorie.blade.php) ai pattern di design-comuni-pagine-statiche, usando componenti universali cmp-* per griglia categorie con icone e contatori."
frontmatter:
  github_issue: "#60"
  github_discussion: "#60"
links:
  github_issue: "#60"
  github_discussion: "#60"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/temi.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Categories List Page DesignComuni — Epic Story

## Goal
Riallineare la pagina lista categorie (`resources/views/pages/lista-categorie.blade.php`) ai pattern di `design-comuni-pagine-statiche` (`temi.html` / `servizi.html`), usando componenti universali `cmp-*` per griglia categorie con icone, descrizioni e contatori ticket.

## Background
- Route: `/lista-categorie` (o `/it/lista-categorie`) — pagina standalone Fixcity
- Template DesignComuni: `temi.html` (griglia card temi) / `servizi.html` (elenco servizi)
- Pagina Fixcity esistente: `lista-categorie.blade.php` — attualmente usa layout custom con card Bootstrap
- Dati: categorie da DB (modello `Category`), ognuna con: nome, slug, icona, descrizione, contatore ticket attivi

## Acceptance Criteria
- [ ] Layout `it-page-section` con `container` + `row` + `col-*` griglia responsive
- [ ] Header pagina:
  - [ ] Titolo: "Categorie segnalazioni" (h1)
  - [ ] Sottotitolo: "Esplora le categorie per tipo di disservizio"
  - [ ] Breadcrumb: Home → Categorie (`cmp-breadcrumb`)
- [ ] **Griglia categorie** (`cmp-category-grid`):
  - [ ] Desktop: 3 colonne (`col-md-4`), Tablet: 2 colonne (`col-sm-6`), Mobile: 1 colonna
  - [ ] Ogni categoria come `cmp-card` (`card card-bg` pattern DesignComuni):
    - [ ] Icona categoria (SVG sprite `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-{icon}`)
    - [ ] Nome categoria (h3)
    - [ ] Descrizione breve (testo troncato, max 2 righe)
    - [ ] Contatore ticket attivi: badge `cmp-badge` (es. "12 segnalazioni aperte")
    - [ ] CTA: "Vedi segnalazioni" → `container0.index` con filtro categoria (`?category={slug}`)
    - [ ] Hover/focus: elevation + shadow (rispetta `prefers-reduced-motion`)
- [ ] **Empty state** (se nessuna categoria): illustrazione + messaggio + CTA "Segnala disservizio"
- [ ] **Accessibilità**:
  - [ ] `aria-label` su card categoria
  - [ ] Heading hierarchy corretta
  - [ ] Contrasto ≥4.5:1
  - [ ] Focus-visible su CTA
  - [ ] `prefers-reduced-motion`: disabilita hover elevation
- [ ] **Multi-lingua**: testi via `__('fixcity::categories...')`, descrizioni tradotte in DB
- [ ] **Multi-tenant**: routing `container0` se applicabile

## Non-Scopi
- Backend API categorie — usa model `Category` esistente
- Gestione categorie admin — Filament resource separato

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-breadcrumb`, `cmp-card`, `cmp-badge`, `cmp-button`, `cmp-category-grid`, `cmp-illustration`, `cmp-empty-state`
- Story correlate: `02-tickets-list-redesign` (filtro categoria), `01-homepage-redesign` (quick-actions), `10-header-footer-components`