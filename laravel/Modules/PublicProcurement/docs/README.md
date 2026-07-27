---
title: "PublicProcurement — replica gestionale"
type: overview
tags: [publicprocurement, gestionale-commesse, replica, bmad]
created: 2026-07-23
updated: 2026-07-27
qmd: "PublicProcurement replica disabled gate appalti mysql index 64"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./bugfix-mysql-identifier-64.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# PublicProcurement

## Perché esiste

Verticale appalti / public procurement SRC. Replica — **non** enable senza gate PHPStan + ordine.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (riabilitato 2026-07-27, confermato dall'utente) |
| PHPStan L10 | 0 errori reali (riverificato 2026-07-27) |
| Pest | passed (`Modules/PublicProcurement/tests`, eseguito insieme a Compliance: 8/8) |

[import-status.md](./import-status.md) · [bugfix index ≤64](./bugfix-mysql-identifier-64.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
