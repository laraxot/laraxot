---
title: "HR — knowledge base (replica, NON merge Employee)"
type: overview
tags: [hr, gestionale-commesse, replica, do-not-delete, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "HR docs README purpose replica overlap Employee enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./composer-manifest.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/gestionale-commesse-comparison/STORY-002-hr-vs-employee.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# HR

## Perché esiste

Replica HR SRC (persone/organigramma gestionale). Overlap con `Employee` ≠ licenza a delete/merge in fase attuale.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (2026-07-24) |
| Merge in Employee | **vietato** ora |
| Gap analisi | [1.5 employee-hr](../../../../docs/wiki/skills/bmad-create-story/stories/1.5.employee-hr-gap.story.md) |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist reshape |
| [composer-manifest.md](./composer-manifest.md) | Manifest |

## Hub / BMAD

[Matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md)
