---
title: "Fiscal — knowledge base"
type: overview
tags: [fiscal, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Fiscal docs README purpose motore calcolo enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Fiscal

## Perché esiste

Motore di calcolo fiscale (Actions/Datas) senza CRUD pesante — supporta Billing/Quotation.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** |
| PHPStan / Pest | 0 reali / 6 passed |

[import-status.md](./import-status.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
