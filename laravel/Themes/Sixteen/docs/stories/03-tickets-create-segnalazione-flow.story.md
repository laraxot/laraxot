---
id: "sixteen-fo-03-tickets-create-segnalazione-flow"
type: "epic-story"
title: "Ticket create flow (segnalazione 4 steps) redesign with Design Comuni"
description: "Implementare il flusso completo di segnalazione in 4 step (privacy → dati → riepilogo → conferma) seguendo design-comuni-pagine-statiche, sostituendo l'attuale tickets/create.blade.php."
frontmatter:
  github_issue: "#57"
  github_discussion: "#57"
links:
  github_issue: "#57"
  github_discussion: "#57"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
  design_ref_2: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
  design_ref_3: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html"
  design_ref_4: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html"
  investigation_ref: "docs/investigation/homepage-design-comuni-investigation.md"
---

# Ticket Create Flow (Segnalazione 4 Steps) DesignComuni — Epic Story

## Goal
Implementare il flusso completo di segnalazione in 4 step seguendo `design-comuni-pagine-statiche`, sostituendo l'attuale `resources/views/pages/tickets/create.blade.php` (e pagine correlate) con pagine conformi al template ufficiale.

## Background
- Flusso DesignComuni (4 step):
  1. **Step 1/4**: `segnalazione-01-privacy` — Informativa privacy + consenso GDPR art.13
  2. **Step 2/4**: `segnalazione-02-dati` — Dati segnalazione: luogo (autocomplete), dettagli disservizio, allegati, dati autore
  3. **Step 3/4**: `segnalazione-03-riepilogo` — Riepilogo informazioni (dichiarazione sostitutiva)
  4. **Step 4/4**: `segnalazione-04-conferma` — Conferma invio con tracking ID (es. AN4059281)
- Route attuale: `container0.view` con `container0=tickets, slug0=create` → `/tickets/create`
- Pagine Fixcity esistenti: `tickets/create.blade.php`, `tickets/confirmation.blade.php`
- Header DesignComuni con step indicator (progress bar) obbligatorio in ogni step

## Acceptance Criteria

### Step 1 — Privacy (segnalazione-01-privacy)
- [ ] Pagina: `resources/views/pages/tickets/create/step-1-privacy.blade.php`
- [ ] Header con step indicator: "1 di 4 — Informativa privacy"
- [ ] Contenuto: Testo informativa privacy (GDPR art.13) + checkbox consenso obbligatorio
- [ ] CTA: "Continua" (disabled fino a consenso) → naviga a step 2
- [ ] Link "Annulla" → torna a homepage/lista ticket

### Step 2 — Dati (segnalazione-02-dati)
- [ ] Pagina: `resources/views/pages/tickets/create/step-2-dati.blade.php`
- [ ] Header con step indicator: "2 di 4 — Dati segnalazione"
- [ ] Form con sezioni:
  - [ ] **Luogo**: Autocomplete indirizzo (Mapbox/Geocoding) + mappa interattiva per pin
  - [ ] **Categoria**: Select dropdown (da API categorie)
  - [ ] **Titolo**: Input testo obbligatorio
  - [ ] **Descrizione**: Textarea obbligatorio
  - [ ] **Allegati**: Drag-drop upload (max 5 file, 10MB cadauno, tipi: jpg, png, pdf)
  - [ ] **Dati autore**: Nome, email, telefono (opzionale se loggato, precompilato)
- [ ] Validazione client-side (Alpine.js) + server-side
- [ ] CTA: "Indietro" (step 1) + "Continua" (step 3)
- [ ] Salvataggio bozza in session/localStorage per recovery

### Step 3 — Riepilogo (segnalazione-03-riepilogo)
- [ ] Pagina: `resources/views/pages/tickets/create/step-3-riepilogo.blade.php`
- [ ] Header con step indicator: "3 di 4 — Riepilogo"
- [ ] Visualizzazione read-only di tutti i dati inseriti (card per sezione)
- [ ] Dichiarazione sostitutiva di atto notorio (testo legale)
- [ ] Checkbox conferma veridicità dati
- [ ] CTA: "Indietro" (step 2) + "Invia segnalazione" (step 4)

### Step 4 — Conferma (segnalazione-04-conferma)
- [ ] Pagina: `resources/views/pages/tickets/create/step-4-conferma.blade.php`
- [ ] Header con step indicator: "4 di 4 — Conferma"
- [ ] Messaggio successo con tracking ID (es. "AN4059281")
- [ ] Dettagli: categoria, data invio, tracking ID, tempo stimato gestione
- [ ] Azioni: "Copia codice tracking", "Torna alle segnalazioni" (link a `container0.index`), "Nuova segnalazione"
- [ ] Condivisione social: Facebook, Twitter, LinkedIn, WhatsApp (pattern DesignComuni)

## Requisiti Tecnici
- [ ] State management: Alpine.js `x-data` con store persistito (sessionStorage)
- [ ] Validazione: Laravel Form Request + client-side mirror
- [ ] File upload: signed URL → S3/local storage, cleanup su abort
- [ ] Geocoding: Mapbox Geocoding API (già configurato in progetto)
- [ ] Accessibilità: WCAG 2.1 AA, `aria-describedby` su errori, focus management tra step
- [ ] `prefers-reduced-motion`: disabilitare animazioni transizione step
- [ ] Multi-lingua: tutti i testi via `__('fixcity::tickets.create...')`

## Non-Scopi
- Backend API create ticket — usa Action esistente `CreateTicketAction`
- Email/SMS notification — gestito da Notify module

## Dependency
- Componenti universali: `cmp-step-indicator`, `cmp-form-field`, `cmp-file-upload`, `cmp-autocomplete-address`, `cmp-map-picker`, `cmp-card`, `cmp-button`, `cmp-alert`
- Story correlate: `01-homepage-redesign` (CTA da hero), `02-tickets-list-redesign` (link "Tutte le segnalazioni"), `10-header-footer-components`