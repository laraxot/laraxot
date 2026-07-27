---
title: "Compliance — replica gestionale"
type: overview
tags: [compliance, gestionale-commesse, replica, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Compliance replica disabled phpstan equipment readiness"
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

# Compliance

## Perché esiste

Conformità / readiness / equipment / documenti compliance di cantiere (SRC). Replica indipendente — **non** merge in altri moduli.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (riabilitato 2026-07-27, confermato dall'utente) |
| PHPStan L10 | 0 errori (riverificato 2026-07-27) |
| Pest | passed (`Modules/Compliance/tests`, eseguito insieme a PublicProcurement: 8/8) |

Dettaglio checklist: [import-status.md](./import-status.md).  
Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).  
Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14).
