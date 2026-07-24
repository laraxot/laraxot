---
title: "EnergyBroker — replica gestionale"
type: overview
tags: [energybroker, gestionale-commesse, replica, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "EnergyBroker replica disabled phpstan vertical energy"
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

# EnergyBroker

## Perché esiste

Verticale energy-broker (documenti/pratiche SRC). Replica singolare — enable solo con ordine dopo gate.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **false** |
| PHPStan L10 | 0 errori reali |

[import-status.md](./import-status.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
