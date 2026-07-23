---
title: "folio — vietato pages/tickets e namespace sixteen nelle view"
type: concept
module: Sixteen
tags: [folio, pub_theme, anti-pattern, tickets, agent-guardrail]
created: 2026-06-06
updated: 2026-06-06
related:
  - ../../page-directory-structure.md
  - ../../../../Modules/Fixcity/docs/wiki/concepts/tickets-view-cms-folio-page.md
  - ../../../../../../docs/stories/STORY-285-folio-no-pages-tickets-pub-theme.md
---

# Perché non esiste `pages/tickets/` nel tema

## Incidente (2026-06-06)

`GET /it/tickets` → `InvalidArgumentException: No hint path defined for [sixteen]`.

**Causa:** file spurio `resources/views/pages/tickets/index.blade.php` con `@extends('sixteen::layouts.app')` — pattern MVC legacy inventato da agente, **non** architettura Laraxot.

Folio preferisce path statico `pages/tickets/index` rispetto a `[container0]/index` → bypass del router CMS.

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
| `/it/tickets/create` | `Modules/Fixcity/resources/views/pages/tickets/create.blade.php` | `tickets.create` |

## Checklist agente (prima di creare file sotto `pages/`)

1. Leggere [page-directory-structure.md](../../page-directory-structure.md)
2. `grep -r "pages/tickets" Themes/Sixteen/resources/views/pages` → deve essere vuoto
3. Pest: `NoSemanticFolioPageDirectoriesTest`
4. Mai `@extends` / `@section` su pagine Folio Volt

## Prevenzione

- CI: `NoSemanticFolioPageDirectoriesTest` (già presente)
- Story: STORY-285
