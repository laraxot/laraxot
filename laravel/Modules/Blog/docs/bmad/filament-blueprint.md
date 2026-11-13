---
title: "Filament Blueprint — Blog"
type: filament-blueprint
status: active
module: Blog
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, filament, blueprint, blog]
qmd: "filament blueprint blog xotbase resource widget"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
---

# Scopo

Contratto Filament del modulo **Blog**: chrome BO e/o widget FO senza violare
i confini di dominio.

## Regole non negoziabili

1. Estendere solo `XotBaseResource` / `XotBaseWidget` / basi Xot — mai classi Filament dirette.
2. Nessun `->label()` hardcoded: chiavi in `lang/<locale>/` del modulo.
3. Business logic nelle Actions del modulo, non nei form hooks grassi.
4. FO: widget montati esplicitamente; non abusare `discoverWidgets` per chrome.

## Resource / widget

- `ArticleResource`
- `CategoryResource`
- `BannerResource`

FO: la cartella CMS è il contenitore. `pages/blog/index.json` (slug `index`) è la lista Novità; `pages/blog/view.json` (slug `view`) monta il dettaglio. Stesso schema per `pages/events/`. Il testo del singolo pezzo resta sull'Article.

## Form / Table / Infolist

Preferire triad schema dedicati (`*Form`, `*Table`, `*Infolist`) quando la Resource
cresce oltre pochi campi. Enum tipizzati al posto di option array.

## Policy e tenant

Ogni query Filament rispetta tenant + policy del modello owner. Nessun elenco
cross-tenant per comodita admin.

## Related

- Planning: [bmad-planning.md](../bmad-planning.md)
- Brainstorming: [bmad-brainstorming.md](../bmad-brainstorming.md)
