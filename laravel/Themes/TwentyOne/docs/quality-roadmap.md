---
title: Quality roadmap — TwentyOne
type: concept
tags: [twentyone, theme, quality, folio, perfection]
created: 2026-08-31
updated: 2026-08-31
qmd: twentyone theme quality roadmap folio container0
related:
  - ./wiki/concepts/folio-container0-filament-way.md
  - ./routing-architecture.md
  - ../../docs/progetto-perfezione-roadmap.md
  - ../../../Modules/Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Theme TwentyOne

## Stato (2026-08-31)

| Area | Stato |
|------|-------|
| Folio container0 | Documentato |
| Directory semantiche `pages/` | **Presenti** (`articles`, `blog`, `dashboard`, …) — non inventariate |
| PHPStan | Non misurato in dettaglio |
| Test | `FolioPageMountContractTest` |

---

## Azioni

1. Creare regola “no semantic dirs” come Sixteen + test Pest.
2. Inventario `resources/views/pages/` e piano migrazione CMS.
3. Misurare PHPStan su `Themes/TwentyOne` prima del gate globale.

Hub: [progetto-perfezione-roadmap.md](../../docs/progetto-perfezione-roadmap.md)
