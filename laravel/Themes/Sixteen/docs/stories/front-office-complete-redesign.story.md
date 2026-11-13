---
title: "Redesign front office completo con DesignComuni v2.4.0"
type: epic-story
status: planning
tags: [design-comuni, front-office, ui-ux, accessibility, wcss-21-AA]
priority: high
links:
  github_issue: "https://github.com/italia/design-comuni-pagine-statiche"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/index.html"
  segnalazione_ref: "https://italia.github.io/design-comuni-pagine-statiche/index.html#segnalazione"
  investigation: "docs/investigation/front-office-redesign-planning.md"
  tech_spec: "docs/bmad/front-office-redesign-tech-spec.md"
  prd: "docs/bmad/front-office-redesign-prd.md"
  decision_log: "docs/decision-log.md"
---

# Redesign front office completo con DesignComuni v2.4.0

## Obiettivo
Ricalare tutte le pagine front office del tema Sixteen secondo i pattern `design-comuni-pagine-statiche` v2.4.0 (Italia Developers), garantendo WCAG 2.1 AA, zero CDN Bootstrap Italia (solo Tailwind `@apply`), AlpineJS + Lit per interattività, mappa con marker visibili.

## Pagine da rivedere
1. **Homepage** (`home.blade.php`, `index.blade.php`) — hero, mappa, come funziona, quick-actions
2. **Catalogo ticket** (`tickets/index.blade.php`) — lista, mappa con marker visibili
3. **Creazione segnalazione** (`tickets/create/*.blade.php`) — wizard 4-step (privacy, dati, riepilogo, conferma)
4. **Dettaglio ticket** (`tickets/show.blade.php`) — stato, interazione
5. **Traccia ticket** (`tickets/track.blade.php`) — ricerca per codice
6. **Persone** (`categories/*.blade.php`) — elenco per area
7. **Errori** (`errors/*.blade.php`) — 404, 500
8. **Accessibilità** (`accessibility.blade.php`) — dichiarazione

## Pattern DesignComuni da applicare
- **Header** (`it-header-wrapper`) → `it-header-slim-wrapper` + `it-nav-wrapper`
- **Skip links** → `[Vai ai contenuti](#main-container) [Vai al footer](#footer)`
- **Footer** (`it-footer`) → 4 colonne + EU logo + social
- **Card** → `card card-bg` con `rounded shadow-sm border-0`
- **Button** → `btn btn-primary` / `btn btn-link` secondo pattern
- **Marker mappa** → `L.divIcon` con SVG nero/bianco (WCAG 2.1 AA)
- **Wizard step** → `it-step-indicator` con progress bar

## Strumenti
- Tailwind CSS con `@apply` per classi Bootstrap Italia
- AlpineJS per stato interattivo (skip, mobile menu)
- `<map-lit>` Web Component per mappa
- Nessun CDN Bootstrap Italia; file servi da `design-comuni.css` locale
- Symlink `public_html/themes/Sixteen → laravel/Themes/Sixteen/public`

## Archivi BMAD
- `docs/stories/` — epics + tasks
- `docs/investigation/` — analisi per pagina
- `docs/bmad/` — tech spec, PRD, decision log, epics
- `docs/decision-log.md` — aggiornamento finale

## Stato attuale
- Pagina `home.blade.php` in fase avanzata
- `tickets/index.blade.php` con marker mappa WCAG 2.1 AA
- `CreateTicketWizardWidget.php` — fix `owner_id` null (vedi story `03-tickets-create-segnalazione-flow.story.md`)
- Symlink creato ✅
- Documenti BMAD creati ✅