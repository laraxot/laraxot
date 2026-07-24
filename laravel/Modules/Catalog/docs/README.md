---
title: "Catalog — knowledge base"
type: overview
tags: [catalog, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Catalog docs README purpose prodotti listini"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "./gestionale-commesse-port.md"
  - "./tests-migration.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
---

# Catalog

## Perché esiste

**Listino / prodotti / unità / tipi**: alimenta righe preventivo e materiali. Dipendenza soft di Quotation (`product_id`).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | products + catalog_* migrate path OK |
| Panel | da verificare/estendere (focus wave = CRUD Quotation) |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [gestionale-commesse-port.md](./gestionale-commesse-port.md) | Port |
| [tests-migration.md](./tests-migration.md) | Pest SRC → DEST (11 passed) |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
- Story [2.5 test migration](../../../../docs/stories/2.5.migrate-gestionale-tests.story.md)
