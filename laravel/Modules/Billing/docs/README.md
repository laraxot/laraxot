---
title: "Billing — knowledge base"
type: overview
tags: [billing, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Billing docs README purpose fatture supplier"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Billing

## Perché esiste

**Fatturazione** cliente e ciclo fornitore (invoice, delivery notes, supplier invoices): monetizza la commessa dopo preventivo/lavoro.

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | invoices* + supplier* (timestamp dopo work_orders) |
| Soft-dep | DomPDF/PhpWord ancora opzionali |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [README.md](./README.md) | Scopo |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
