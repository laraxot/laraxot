---
title: "Quotation — knowledge base"
type: overview
tags: [quotation, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Quotation docs README purpose preventivo sopralluogo"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Quotation

## Perché esiste

**Pre-vendita → offerta**: preventivi, template, sopralluoghi (`SiteSurvey`). Nasce dal cliente (e a volte dalla commessa) e può generare lavoro operativo.

## Cosa NON è

- Non è fatturazione (→ Billing).
- Non è catalogo prodotti (→ Catalog; `product_id` senza FK DB forzata).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | quotations* + site_surveys (timestamp dopo Customer/WorkOrder) |
| Panel | `quotation/admin` |
| User FK | `performed_by_user_id` = `string(36)` |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [gestionale-commesse-port.md](./gestionale-commesse-port.md) | Note port |
| [README.md](./README.md) | Scopo |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md) · story [1.13](../../../../docs/stories/1.13.functional-readiness-migrate-panels.story.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
