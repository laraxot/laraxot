---
id: "sixteen-fo-04-ticket-detail-redesign"
type: "epic-story"
title: "Ticket detail page redesign with Design Comuni"
description: "Riallineare la pagina dettaglio ticket ([container0]/[slug0]/index.blade.php per ticket) ai pattern di design-comuni-pagine-statiche (segnalazione-dettaglio.html), con header, dettagli, commenti, timeline, condivisione."
frontmatter:
  github_issue: "#58"
  github_discussion: "#58"
links:
  github_issue: "#58"
  github_discussion: "#58"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Ticket Detail Page DesignComuni — Epic Story

## Goal
Riallineare la pagina dettaglio ticket (`resources/views/pages/[container0]/[slug0]/index.blade.php` per ticket) ai pattern di `design-comuni-pagine-statiche` (`segnalazione-dettaglio.html`), usando componenti universali `cmp-*`.

## Background
- Route: `container0.view` con `container0=tickets, slug0={id}` → `/tickets/{id}`
- Template DesignComuni: `segnalazione-dettaglio.html` — struttura:
  - Header: titolo segnalazione, stato badge, categoria, data
  - Azioni: "Segnala disservizio" (se non autore) / "Tutte le segnalazioni" + Condividi (FB, Twitter, LinkedIn, WhatsApp)
  - Dettagli: descrizione completa, luogo (mappa), allegati
  - Timeline/Storico: stati, note interne, risposte
  - Commenti/Discussione: thread commenti (nuovo modulo Comment)
- Pagine Fixcity esistenti: `tickets/[id].blade.php`, componenti `ticket/ticket-card.blade.php`, `ticket/layout.blade.php`

## Acceptance Criteria
- [ ] Layout `it-page-section` con `container` + `row` + `col-lg-8` (main) + `col-lg-4` (sidebar)
- [ ] **Header sezione** (main):
  - [ ] Titolo segnalazione (`h1`)
  - [ ] Badge stato: `cmp-badge` con varianti (aperto=verde, in lavorazione=giallo, chiuso=blu, respinto=rosso)
  - [ ] Badge categoria + icona
  - [ ] Meta: data creazione, autore (se pubblico), tracking ID
- [ ] **Azioni header** (DesignComuni pattern):
  - [ ] CTA primaria: "Segnala disservizio" (se utente non è autore) → `container0.view` create
  - [ ] CTA secondaria: "Tutte le segnalazioni" → `container0.index`
  - [ ] Condividi: dropdown con FB, Twitter, LinkedIn, WhatsApp (sprite SVG)
- [ ] **Dettagli segnalazione** (main):
  - [ ] Descrizione completa (prose, supporto markdown base)
  - [ ] Luogo: indirizzo + `<map-lit>` statica (solo marker, no interazione)
  - [ ] Allegati: griglia `cmp-card` con anteprima (immagini) / icona file + download
- [ ] **Timeline/Storico** (main):
  - [ ] Lista cronologica stati (vertical timeline `cmp-timeline`)
  - [ ] Ogni step: icona, label stato, data, autore, nota (se presente)
  - [ ] Note interne: badge "Nota interna" (visibile solo staff)
- [ ] **Commenti/Discussione** (main):
  - [ ] Thread commenti (modulo Comment polimorfico)
  - [ ] Form aggiunta commento (solo autenticati) — `cmp-form-field` + `cmp-button`
  - [ ] Commenti ordinati cronologici, avatar, nome, data, contenuto
- [ ] **Sidebar** (col-lg-4):
  - [ ] Card "Informazioni" con meta dati strutturati
  - [ ] Card "Segui segnalazione" — toggle subscribe (se autenticato)
  - [ ] Card "Condividi" — pulsanti social verticali
- [ ] Accessibilità: `aria-labelledby`, heading hierarchy (h1→h2→h3), focus-visible, skip link
- [ ] `prefers-reduced-motion`: nessuna animazione timeline
- [ ] Multi-lingua: testi via `__('fixcity::tickets.detail...')`

## Non-Scopi
- Backend API ticket detail — usa Action esistente
- Logica commenti — modulo Comment separato
- Notifiche — Notify module

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-badge`, `cmp-card`, `cmp-button`, `cmp-timeline`, `cmp-comment-thread`, `cmp-social-share`, `cmp-map-static`
- Story correlate: `02-tickets-list-redesign` (link da lista), `03-tickets-create-segnalazione-flow` (CTA "Segnala disservizio"), `05-tickets-track-redesign` (tracking), `10-header-footer-components`