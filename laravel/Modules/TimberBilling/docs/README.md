---
title: "TimberBilling — replica gestionale"
type: overview
tags: [timberbilling, gestionale-commesse, replica, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "TimberBilling replica disabled gate legno fatturazione"
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

# TimberBilling

## Perché esiste

Verticale fatturazione timber SRC. Replica — enable solo con ordine dopo gate.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **false** |

[import-status.md](./import-status.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
