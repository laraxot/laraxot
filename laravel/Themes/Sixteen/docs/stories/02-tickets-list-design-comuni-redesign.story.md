---
id: "sixteen-fo-02-tickets-list-redesign"
type: "epic-story"
title: "Tickets list page redesign with Design Comuni"
description: "Riallineare la pagina elenco ticket ([container0]/index.blade.php per tickets) ai pattern di design-comuni-pagine-statiche, usando componenti universali cmp-* e layout it-page-section."
frontmatter:
  github_issue: "#56"
  github_discussion: "#56"
links:
  github_issue: "#56"
  github_discussion: "#56"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/lista-segnalazioni.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Tickets List Page DesignComuni — Epic Story

## Goal
Riallineare la pagina elenco ticket (`resources/views/pages/[container0]/index.blade.php` per `container0=tickets`) ai pattern di `design-comuni-pagine-statiche`, usando componenti universali `cmp-*` e layout `it-page-section`.

## Background
- Route: `container0.index` con `container0=tickets` → `/tickets` (o `/it/tickets`)
- Template attuale: usa layout Blade con sidebar + contenuto principale, mix di Bootstrap Italia e custom CSS
- Referenza DesignComuni: `lista-segnalazioni.html` (pattern elenco con filtri, card, paginazione)
- Componenti esistenti riutilizzabili: `ticket-list/layout.blade.php`, `ticket-list/ticket-card.blade.php`, `ticket-list/filters-sidebar.blade.php`, `ticket-list/map-filters-column.blade.php`

## Acceptance Criteria
- [ ] Layout `it-page-section` con `container` + `row` + `col-*` griglia responsive
- [ ] Header pagina con titolo "Segnalazioni" + eyebrow "Elenco segnalazioni" + breadcrumb (`cmp-breadcrumb`)
- [ ] Sidebar filtri (`cmp-nav-sidebar` o `cmp-filter-sidebar`) con:
  - [ ] Ricerca testuale
  - [ ] Filtro per categoria (dropdown)
  - [ ] Filtro per stato (aperto/chiuso/in lavorazione)
  - [ ] Filtro per data (range)
  - [ ] Toggle mappa/lista
- [ ] Area principale con:
  - [ ] Toggle vista mappa/lista (Alpine.js `x-data`)
  - [ ] Lista ticket come `cmp-card` grid (desktop: 1 col, tablet: 2 col, mobile: 1 col)
  - [ ] Ogni card: titolo, categoria badge, stato badge, data, posizione, CTA "Visualizza"
  - [ ] Paginazione (`cmp-pagination`) allineata a DesignComuni
- [ ] Vista mappa: `<map-lit>` full-width con clustering, legend, CTA "Segnala disservizio"
- [ ] Empty state: illustrazione + messaggio + CTA "Segnala disservizio"
- [ ] Accessibilità: skip link, `aria-label` su filtri, focus-visible, contrasto
- [ ] Multi-tenant: routing `container0` per separazione tenant
- [ ] Multi-lingua: tutti i testi via `__('fixcity::...')`

## Non-Scopi
- API backend (`/api/tickets/geojson`, `/api/tickets`) — resta invariata
- Logica filtri lato server — resta in Actions esistenti

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-card`, `cmp-breadcrumb`, `cmp-nav-sidebar`, `cmp-pagination`, `cmp-badge`, `cmp-empty-state`
- Story correlate: `03-tickets-create-segnalazione-flow` (CTA create), `10-header-footer-components`