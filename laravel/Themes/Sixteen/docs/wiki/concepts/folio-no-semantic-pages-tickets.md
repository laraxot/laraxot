---
qmd: "folio no semantic pages tickets"
issues: []
discussions: []
title: "folio — vietato pages/tickets e namespace sixteen nelle view"
type: concept
module: Sixteen
tags: [folio, pub_theme, anti-pattern, tickets, agent-guardrail]
created: 2026-06-06
updated: 2026-06-06
related:
  - ../../page-directory-structure.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/tickets-view-cms-folio-page.md
  - ../../../../../../docs/stories/STORY-285-folio-no-pages-tickets-pub-theme.md
status: draft
---

# Perché non esiste `pages/tickets/` nel tema

## Incidente (2026-06-06)

`GET /it/tickets` → `InvalidArgumentException: No hint path defined for [sixteen]`.

**Causa:** file spurio `resources/views/pages/tickets/index.blade.php` con `@extends('sixteen::layouts.app')` — pattern MVC legacy inventato da agente, **non** architettura Laraxot.

Folio preferisce path statico `pages/tickets/index` rispetto a `[container0]/index` → bypass del router CMS. Stesso meccanismo di `pages/domande-frequenti/index.blade.php`: [perché non deve esistere](../../bmad/knowledge/why-domande-frequenti-blade-php-must-not-exist.md).

## Regola

| Vietato | Obbligatorio |
|---------|----------------|
| `Themes/*/resources/views/pages/tickets/` | `[container0]/index.blade.php` + CMS `tickets.index` |
| `@extends('sixteen::layouts.app')` | `<x-layouts.app>` + `@volt` + `<x-page slug="…">` |
| Namespace `sixteen::` in nuove view FO | `pub_theme::` (SSoT tema pubblico) |
| Blade monolitica con header/footer inline | JSON CMS + blocchi `pub_theme::components.blocks.*` |

## Routing canonico ticket FO

| URL | Folio | CMS slug |
|-----|-------|----------|
| `/it/tickets` | `pages/[container0]/index.blade.php` | `tickets.index` |
| `/it/tickets/{id}` | `pages/[container0]/[slug0]/index.blade.php` | `tickets.view` |
| `/it/tickets/create` | `pages/[container0]/[slug0]/index.blade.php` | `tickets.create` via `container0.view` |

> Aggiornamento 2026-10-14: per `/it/tickets/{id}` la colonna `CMS slug` (`tickets.view`) non e' piu' il percorso attivo per un id numerico: un `/{lang}/tickets/{id}` con id numerico e' servito dal ramo `ticket-detail` di `Themes/Sixteen/resources/views/pages/[container0]/[slug0]/index.blade.php` (condizione `ctype_digit($slug0)`): chiama `BuildPublicTicketDetailAction`, risponde 404 se la segnalazione non e' pubblica, aggiunge `data-dc` a `.page-content` e include `components/pages/ticket-detail.blade.php`, che include il blocco `components/blocks/ticket-detail/design-comuni.blade.php` (DCP-148). Il blocco usa `coordinate-picker-lit` in sola lettura con icona del tipo e colore dello stato (DCP-152, vedi [coordinate-picker](../coordinate-picker.md)). Gli slug `tickets.create` e `tickets.index` hanno un JSON in `Modules/Fixcity/config/database/content/pages/`, `tickets.view` no.
## Checklist agente (prima di creare file sotto `pages/`)

1. Leggere [page-directory-structure.md](../../page-directory-structure.md)
2. `grep -r "pages/tickets" Themes/Sixteen/resources/views/pages` → deve essere vuoto
3. Pest: `NoSemanticFolioPageDirectoriesTest`
4. Mai `@extends` / `@section` su pagine Folio Volt

## Prevenzione

- CI: `NoSemanticFolioPageDirectoriesTest` (già presente)
- Story: STORY-285
