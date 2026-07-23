---
title: folio page-shell — data-page presentation only
type: concept
theme: sixteen
updated: 2026-05-29
related:
  - ../../../../../../docs/wiki/concepts/theme-as-vestito-philosophy.md
---

# Sixteen — `data-page="page-shell"`

## Regola

`resources/views/pages/index.blade.php` (e ogni Folio page) espone solo il **guscio** CMS:

```blade
<main data-page="page-shell">
    <x-page side="content" slug="home" />
</main>
```

Il contenuto dominio (elenco ticket, mappa, filtri) arriva dai **blocchi CMS** (`ticket.layout`), non da attributi dominio sul `<main>`.

## Vietato sul guscio Folio

| Valore | Perché |
|--------|--------|
| `ticket-list` | nome dominio |
| `ticket-list` | dominio + italiano |
| `home-content` | ancora legato a una route specifica |

## Hook JS/CSS (generici)

- Stili: `main[data-page="page-shell"]` in `resources/css/app.css`
- Root blocco: `#main-container` (Design Comuni)
- Mappa/filtri: `#block-map`, `.block-filter-checkbox`, `#block-results-count`

## Riferimento esterno

Parity visiva: URL Design Comuni `ticket-list` — solo commento/link, **non** come `data-page`.
