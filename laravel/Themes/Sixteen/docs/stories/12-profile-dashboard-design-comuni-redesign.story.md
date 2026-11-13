---
id: "sixteen-fo-12-profile-dashboard-redesign"
type: "epic-story"
title: "User profile / dashboard redesign with Design Comuni"
description: "Riallineare la dashboard utente / area personale ai pattern di design-comuni-pagine-statiche (profilo-utente.html), con sidebar navigazione, sezioni: segnalazioni, preferenze, notifiche, dati personali."
frontmatter:
  github_issue: "#66"
  github_discussion: "#66"
links:
  github_issue: "#66"
  github_discussion: "#66"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/profilo-utente.html"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# User Profile / Dashboard DesignComuni — Epic Story

## Goal
Riallineare la dashboard utente / area personale (`/dashboard`, `/profile`, `/profile/*`) ai pattern di `design-comuni-pagine-statiche` (`profilo-utente.html`), usando componenti universali `cmp-*` con layout a due colonne (sidebar + contenuto).

## Background
- Route: `/dashboard` (home area personale), `/profile` (dati anagrafici), `/profile/tickets` (mie segnalazioni), `/profile/notifications` (notifiche), `/profile/preferences` (preferenze), `/profile/security` (sicurezza/2FA)
- Template DesignComuni: `profilo-utente.html` — sidebar navigazione + card contenuto
- Layout: `it-page-section` + `container` + `row` → `col-lg-3` sidebar + `col-lg-9` main
- Multi-tenant: isolamento dati per `container0`

## Acceptance Criteria

### Layout Generale
- [ ] **Wrapper**: `it-page-section` + `container` + `row`
- [ ] **Sidebar** (`col-lg-3`, `cmp-nav-sidebar`):
  - [ ] Avatar utente (`cmp-avatar`) + nome + email
  - [ ] Badge ruolo (cittadino, operatore, admin) — `cmp-badge-status`
  - [ ] Navigazione: "Dashboard", "Le mie segnalazioni", "Notifiche", "Dati personali", "Preferenze", "Sicurezza", "Esci"
  - [ ] Active state su voce corrente
  - [ ] Mobile: drawer laterale (Alpine.js `x-data`, `x-show`), toggle in header
- [ ] **Main** (`col-lg-9`): `cmp-page-section` per ogni sezione

### Dashboard (/dashboard)
- [ ] **Hero utente**: `cmp-card` elevated → avatar grande, nome, ruolo, ultima attività, CTA "Nuova segnalazione" → `container0.view` create
- [ ] **Statistiche** (`cmp-stat-card` grid 4 colonne):
  - [ ] Segnalazioni aperte (link → `/profile/tickets?status=open`)
  - [ ] In lavorazione (link → `/profile/tickets?status=processing`)
  - [ ] Chiuse (link → `/profile/tickets?status=closed`)
  - [ ] Notifiche non lette (link → `/profile/notifications`)
- [ ] **Attività recente** (`cmp-timeline`): ultime 5 azioni (segnalazioni create, commenti, cambi stato)
- [ ] **Notifiche urgenti** (`cmp-alert` warning): scadenze, risposte operatori

### Le Mie Segnalazioni (/profile/tickets)
- [ ] **Header**: titolo "Le mie segnalazioni" + `cmp-button` primary "Nuova segnalazione"
- [ ] **Filtri** (`cmp-filter-sidebar` collassabile): stato, categoria, data range, ricerca testo
- [ ] **Lista** (`cmp-card-grid`): card per ticket con:
  - [ ] Titolo + categoria badge (`cmp-badge-status`)
  - [ ] Stato badge (aperto=verde, in lavorazione=giallo, chiuso=blu, respinto=rosso)
  - [ ] Data creazione + tracking ID
  - [ ] CTA: "Visualizza" → `container0.view` ticket, "Aggiorna" (se aperto)
- [ ] **Paginazione** (`cmp-pagination`)
- [ ] **Empty state**: `cmp-empty-state` con illustrazione + "Nessuna segnalazione" + CTA "Crea la prima"

### Notifiche (/profile/notifications)
- [ ] **Header**: titolo + badge count non lette + `cmp-button` outline "Segna tutte lette"
- [ ] **Lista** (`cmp-card` per notifica):
  - [ ] Icona tipo (segnalazione, commento, sistema) + titolo
  - [ ] Messaggio breve + timestamp relativo
  - [ ] Stato: non letta (bold + dot blu) / letta
  - [ ] CTA: "Visualizza" → link contestuale
- [ ] **Filtro**: tutte / non lette / lette
- [ ] **Paginazione** + infinite scroll (Alpine.js)

### Dati Personali (/profile)
- [ ] **Form** (`cmp-auth-form` style): nome, cognome, email, telefono, codice fiscale, indirizzo
- [ ] **Sezioni**: `cmp-accordion` per "Dati anagrafici", "Contatti", "Indirizzi"
- [ ] **Salvataggio**: `cmp-button` primary "Salva modifiche" + toast success
- [ ] **Validazione**: email unique, CF format (regex IT), telefono format

### Preferenze (/profile/preferences)
- [ ] **Notifiche email**: toggle per tipo (nuova segnalazione, aggiornamenti, newsletter, digest settimanale)
- [ ] **Notifiche push**: toggle (se supportato)
- [ ] **Lingua**: `cmp-language-switcher` (IT/EN)
- [ ] **Tema**: `cmp-dropdown` light/dark/system
- [ ] **Privacy**: toggle dati analytics, marketing
- [ ] **Salvataggio**: auto-save on change (debounce 500ms) + toast "Preferenze salvate"

### Sicurezza (/profile/security)
- [ ] **Password**: form cambio password (attuale, nuova, conferma) + strength meter
- [ ] **2FA** (se abilitato): setup TOTP (QR code + codice), backup codes, disable
- [ ] **Sessioni attive**: lista device (browser, IP, last active) + `cmp-button` danger "Revoca" per ognuna
- [ ] **Account**: `cmp-button` danger "Elimina account" → modal conferma (`cmp-modal`) con digitazione email

## Requisiti Tecnici
- [ ] **Alpine.js** per: sidebar toggle mobile, accordion, tabs, auto-save preferences, infinite scroll
- [ ] **Accessibilità**: WCAG 2.1 AA, heading hierarchy, `aria-current` su sidebar, focus management
- [ ] **`prefers-reduced-motion`**: no animazioni sidebar drawer, timeline
- [ ] **Dark mode**: completo
- [ ] **Multi-lingua**: tutti i testi tradotti
- [ ] **CSRF**: su tutti i form
- [ ] **Rate limiting**: cambio password 3/ora, elimina account 1/giorno

## Non-Scopi
- Backend API profilo — usa Actions esistenti
- 2FA backend — modulo separato
- Admin panel — Filament separato

## Dependency
- Componenti universali: `cmp-page-section`, `cmp-nav-sidebar`, `cmp-avatar`, `cmp-badge-status`, `cmp-stat-card`, `cmp-timeline`, `cmp-card`, `cmp-card-grid`, `cmp-filter-sidebar`, `cmp-pagination`, `cmp-empty-state`, `cmp-accordion`, `cmp-form-field`, `cmp-button`, `cmp-alert`, `cmp-toast`, `cmp-progress`, `cmp-dropdown`, `cmp-language-switcher`, `cmp-modal`, `cmp-avatar`
- Story correlate: `07-header-component-redesign` (link area personale), `02-tickets-list-redesign` (lista ticket), `04-ticket-detail-redesign` (dettaglio ticket), `10-universal-components-cmp-library`