---
title: "Auth — knowledge base (replica, NON merge User)"
type: overview
tags: [auth, gestionale-commesse, replica, do-not-delete, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Auth docs README purpose replica overlap User enabled"
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
  - "../../../../docs/gestionale-commesse-comparison/STORY-001-auth-vs-user.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Auth

## Perché esiste

Replica del modulo Auth SRC (policy/flussi auth gestionale). **Overlap** con `User` ≠ collisione path: **non** cancellare, **non** merge ora.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (2026-07-24) |
| PHPStan L10 | 0 errori reali (vedi import-status) |
| Login FO primario | ancora User Folio Laraxot — Auth = replica coesistente |
| Merge in User | **vietato** in fase replica |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist + fix User model |
| [composer-manifest.md](./composer-manifest.md) | Manifest |

## Hub / BMAD

[Matrice runtime](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md) · Story [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
