---
id: "sixteen-fo-13-search-pages-redesign"
type: "epic-story"
title: "Search pages redesign with Design Comuni"
description: "Riallineare le pagine di ricerca (globale, segnalazioni avanzata, autocomplete) ai pattern di design-comuni-pagine-statiche (ricerca.html), con header ricerca, filtri, risultati, paginazione, suggerimenti."
frontmatter:
  github_issue: "#67"
  github_discussion: "#67"
links:
  github_issue: "#67"
  github_discussion: "#67"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/ricerca.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Search Pages DesignComuni — Epic Story

## Goal
Riallineare le pagine di ricerca ai pattern di `design-comuni-pagine-statiche` (`ricerca.html`), usando componenti universali `cmp-*` per: ricerca globale (header), ricerca avanzata segnalazioni, autocomplete suggerimenti, pagina risultati.

## Background
- Route: `/search` (globale), `/tickets/search` (avanzata), `/api/search/autocomplete` (suggerimenti)
- Template DesignComuni: `ricerca.html` — header con input grande, filtri sidebar, risultati card, paginazione
- Componenti esistenti: `components/blocks/search/*.blade.php` (input, results, support-links, feedback, final)

## Acceptance Criteria

### Ricerca Globale (/search)
- [ ] **Hero ricerca** (`cmp-hero` variant search):
  - [ ] Titolo: "Cerca nel sito" + sottotitolo "Trova segnalazioni, servizi, informazioni"
  - [ ] Input grande (`cmp-form-field` + `cmp-search-input`): `type="search"`, `aria-label="Cerca nel sito"`, `autocomplete="off"`, placeholder "Cosa cerchi?"
  - [ ] Submit: `cmp-button` primary con icona `it-search` "Cerca"
  - [ ] Suggerimenti popolari: pill `cmp-chip` cliccabili (es. "segnalazioni stradali", "illuminazione", "rifiuti")
- [ ] **Risultati** (`cmp-search-results`):
  - [ ] Header: "X risultati per '{query}'" + tempo ricerca + `cmp-dropdown` ordinamento (rilevanza, data, titolo)
  - [ ] Gruppi per tipo: "Segnalazioni", "Servizi", "Pagine", "News", "Eventi"
  - [ ] Ogni risultato: `cmp-card` → titolo (link), tipo badge, snippet evidenziato, categoria, data
  - [ ] Highlight termini ricerca nel snippet (`<mark>`)
  - [ ] Paginazione (`cmp-pagination`) o infinite scroll
- [ ] **Nessun risultato** (`cmp-empty-state`): illustrazione + "Nessun risultato per '{query}'" + suggerimenti: "Prova parole diverse", "Verifica ortografia", "Filtra per categoria"

### Ricerca Avanzata Segnalazioni (/tickets/search)
- [ ] **Layout**: `it-page-section` + sidebar filtri (`col-lg-3`) + risultati (`col-lg-9`)
- [ ] **Filtri** (`cmp-filter-sidebar`):
  - [ ] Testo libero (titolo, descrizione)
  - [ ] Categoria: `cmp-form-field` select multipla (da API categorie)
  - [ ] Stato: `cmp-form-field` checkbox group (aperto, in lavorazione, chiuso, respinto)
  - [ ] Data: `cmp-form-field` date range picker (da/a)
  - [ ] Luogo: `cmp-autocomplete-address` + raggio km
  - [ ] Autore: me / tutti (se autenticato)
  - [ ] `cmp-button` primary "Applica filtri" + outline "Reset"
- [ ] **Risultati**: come lista ticket (`cmp-card-grid`) con mappa toggle
- [ ] **URL sincronizzato**: query params per condivisione link filtrato

### Autocomplete Suggerimenti (Header Search)
- [ ] **Componente**: `cmp-search-autocomplete` (Alpine.js + Lit)
- [ ] **Trigger**: input header (`cmp-search-input`) su `input` event (debounce 300ms, min 2 char)
- [ ] **Sorgenti**: ticket titles, categorie, servizi, pagine CMS, FAQ
- [ ] **Rendering**: dropdown `role="listbox"` → `option` `role="option"` con:
  - [ ] Icona tipo (ticket, categoria, servizio, pagina)
  - [ ] Titolo + sottotitolo (categoria / percorso)
  - [ ] Highlight match
  - [ ] Navigazione tastiera (freccia su/giù, Enter, ESC)
- [ ] **Accessibilità**: `aria-autocomplete="list"`, `aria-controls`, `aria-activedescendant`
- [ ] **Click fuori**: chiude dropdown
- [ ] **Mobile**: full-screen modal su focus (Alpine.js)

### Ricerca in Header (Global)
- [ ] **Input header** (`cmp-search-input`): compatto, espande su focus
- [ ] **Icona lente**: click → focus input
- [ ] **Shortcut**: `/` o `Cmd/Ctrl+K` → focus input (Alpine.js `document.addEventListener('keydown')`)
- [ ] **Chiusura**: ESC, click fuori, submit

## Requisiti Tecnici
- [ ] **Backend**: Laravel Scout (Meilisearch/Algolia) o query SQL ottimizzate con indici fulltext
- [ ] **Indici**: `tickets` (title, content), `categories` (name, description), `pages` (title, content)
- [ ] **Peso risultati**: ticket > categorie > servizi > pagine > news > eventi
- [ ] **Rate limiting**: autocomplete 30/min per IP
- [ ] **Cache**: risultati frequenti (Redis, TTL 1h)
- [ ] **Accessibilità**: WCAG 2.1 AA, `role="search"` su form, `aria-live="polite"` su risultati autocomplete
- [ ] **`prefers-reduced-motion`**: no animazioni dropdown autocomplete
- [ ] **Dark mode**: completo
- [ ] **Multi-lingua**: ricerca per lingua corrente + fallback

## Non-Scopi
- Backend search engine setup — separato
- Indicizzazione PDF/documenti — modulo separato
- Analytics ricerche — modulo Analytics

## Dependency
- Componenti universali: `cmp-hero`, `cmp-form-field`, `cmp-search-input`, `cmp-search-autocomplete`, `cmp-chip`, `cmp-card`, `cmp-badge`, `cmp-pagination`, `cmp-empty-state`, `cmp-filter-sidebar`, `cmp-dropdown`, `cmp-illustration`, `cmp-page-section`
- Story correlate: `07-header-component-redesign` (search in header), `02-tickets-list-redesign` (filtri ticket), `10-universal-components-cmp-library`