---
title: "Email — replica gestionale"
type: overview
tags: [email, gestionale-commesse, replica, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Email replica normalize Laraxot enabled Notify overlap"
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

# Email

Replica SRC (canale email gestionale). Overlap possibile con `Notify` — **non** merge in fase replica.

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** |
| Merge in Notify | vietato ora |

Dettaglio: [import-status.md](./import-status.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
