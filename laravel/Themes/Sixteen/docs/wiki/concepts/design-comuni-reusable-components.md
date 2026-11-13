---
title: "Design Comuni — componenti riutilizzabili"
type: concept
status: active
theme: Sixteen
created: 2026-09-01
updated: 2026-10-22
tags: [sixteen, design-comuni, reusable, dry, kiss]
qmd: "design comuni reusable components frontoffice routes aliases search"
related:
  - ../../rules/reusable-components.md
  - ../../bmad/filament-blueprint.md
  - ../../bmad/container0-index-shell-contract.md
  - ../../stories/STORY-BLOG003-reusable-components.md
---

# Design Comuni — Componenti Riutilizzabili

## Concetto

I blocchi (`components/blocks/`) e i pezzi FO di supporto sono pezzi di UI/logica
**riutilizzabili**. Configurabili via `@props` o Support tipizzati; mai hard-codati
nella shell Folio.

## Perché

Una pagina FO FixCity è composizione: CMS JSON + blocchi + Support URL/i18n.
Duplicare alias, route ticket o suggerimenti search in ogni Blade spezza DRY e
rompe la religione `container0.*` (mai `tickets.*` / `localizeUrl`).

## Layer

| Layer | Dove | Cosa |
|-------|------|------|
| Shell Folio | `pages/[container0]/index.blade.php` | Solo `render` → Action + Volt + `<x-page>` |
| Action | `Actions/Folio/ResolveContainer0IndexAction` | Alias 301, area-personale, 404 |
| Infrastructure URL | `Infrastructure/Url/Frontoffice*` | Path Folio / CMS / alias |
| Presentation | `Presentation/Search`, `Presentation/Metadata` | Default UI e meta |
| Blocchi | `components/blocks/*` | UI data-driven |

## Esempi implementati

| Pezzo | Configurazione | Consumatori |
|-------|----------------|-------------|
| `blocks/blog/index.blade.php` | `blog.index.json` | `/it/blog` |
| `blocks/events/index.blade.php` | `events.index.json` | `/it/events` |
| `sections/search-modal` | `@props links` o `FrontofficeSearchSuggestions` | header FO |
| `pages/services`, `pages/faq` | `FrontofficeRoutes` | catalogo / contatti |
| `[container0]/index` | `ResolveContainer0IndexAction` | ogni index CMS |

## Principi EEC / BMAD

- Minimo necessario (KISS)
- Zero duplicazione di logica (DRY)
- Presentazione separata dai dati
- Filament blueprint: tema senza Resource; mount widget solo da JSON

## Riferimenti

- **Regola**: [reusable-components.md](../../rules/reusable-components.md)
- **BMAD story**: [STORY-BLOG003-reusable-components.md](../../stories/STORY-BLOG003-reusable-components.md)
- **Blueprint**: [filament-blueprint.md](../../bmad/filament-blueprint.md)
- **Shell**: [container0-index-shell-contract.md](../../bmad/container0-index-shell-contract.md)
