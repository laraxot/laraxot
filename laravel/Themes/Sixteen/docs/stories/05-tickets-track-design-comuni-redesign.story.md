---
id: "sixteen-fo-05-tickets-track-redesign"
type: "epic-story"
title: "Ticket track page redesign with Design Comuni"
description: "Riallineare le pagine di tracciamento ticket (tickets/track.blade.php e tickets/track/[code].blade.php) ai pattern di design-comuni-pagine-statiche, con ricerca per codice e visualizzazione stato."
frontmatter:
  github_issue: "#59"
  github_discussion: "#59"
links:
  github_issue: "#59"
  github_discussion: "#59"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Ticket Track Page DesignComuni — Epic Story

## Goal
Riallineare le pagine di tracciamento ticket (`resources/views/pages/tickets/track.blade.php` e `resources/views/pages/tickets/track/[code].blade.php`) ai pattern di `design-comuni-pagine-statiche`, usando componenti universali `cmp-*`.

## Background
- Route: `tickets.track` → `/tickets/track` (pagina ricerca)
- Route: `tickets.track` con `code` → `/tickets/track/{code}` (pagina risultato)
- Template DesignComuni: simile a `segnalazione-dettaglio.html` ma in modalità "sola lettura" per cittadini non autenticati
- Pagine Fixcity esistenti: `tickets/track.blade.php`, `tickets/track/[code].blade.php`

## Acceptance Criteria

### Pagina Ricerca (tickets/track.blade.php)
- [ ] Layout `it-page-section` centered (max-width `lg:max-w-2xl`)
- [ ] Header: "Traccia la tua segnalazione" + descrizione "Inserisci il codice di tracking ricevuto via email"
- [ ] Form ricerca (`cmp-form-field` + `cmp-button`):
  - [ ] Input codice tracking (pattern: 2 lettere + 7 cifre, es. AN4059281)
  - [ ] Validazione client-side (regex) + server-side
  - [ ] CTA: "Cerca" → redirect a `/tickets/track/{code}`
  - [ ] Link: "Non hai il codice? Contattaci" → pagina contatti
- [ ] Empty state iniziale: illustrazione + messaggio guida

### Pagina Risultato (tickets/track/[code].blade.php)
- [ ] Layout `it-page-section` con `container`
- [ ] **Header risultato**:
  - [ ] Titolo: "Segnalazione {tracking_id}"
  - [ ] Badge stato (`cmp-badge`): aperto/in lavorazione/chiuso/respinto
  - [ ] Meta: data invio, categoria, luogo
- [ ] **Dettagli segnalazione** (read-only, card `cmp-card`):
  - [ ] Descrizione completa
  - [ ] Luogo: indirizzo + mappa statica (`cmp-map-static`)
  - [ ] Allegati: elenco con download
- [ ] **Timeline stato** (`cmp-timeline` verticale):
  - [ ] Step cronologici: inviato → in lavorazione → chiuso (o respinto)
  - [ ] Ogni step: icona, label, data, nota operatore (se pubblica)
- [ ] **Azioni**:
  - [ ] "Torna alla ricerca" → `tickets.track`
  - [ ] "Nuova segnalazione" → `container0.view` create
  - [ ] Condividi: social buttons (FB, Twitter, LinkedIn, WhatsApp)
- [ ] **Stato "non trovato"**: se codice inesistente → pagina 404 custom con CTA "Riprova" + "Contattaci"
- [ ] Accessibilità: `aria-label` su input, focus-visible, heading hierarchy
- [ ] `prefers-reduced-motion`: nessuna animazione timeline
- [ ] Multi-lingua: testi via `__('fixcity::tickets.track...')`

## Non-Scopi
- Backend API track — usa Action esistente
- Autenticazione — pagina pubblica (no login required)

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-form-field`, `cmp-button`, `cmp-badge`, `cmp-card`, `cmp-timeline`, `cmp-map-static`, `cmp-social-share`, `cmp-empty-state`, `cmp-illustration`
- Story correlate: `01-homepage-redesign` (quick-actions CTA), `03-tickets-create-segnalazione-flow` (tracking ID generation), `10-header-footer-components`