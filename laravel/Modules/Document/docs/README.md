---
title: "Document — knowledge base"
type: overview
tags: [document, gestionale-commesse, docs, media-conflict, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Document docs README purpose media conflict risk enabled statuses"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./composer-manifest.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Document

## Perché esiste

Allegati di dominio gestionale (kind document/photo/signature/sketch…) — **replica**, non merge in Media Spatie.

## Runtime (attenzione)

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (JSON) |
| Tabella model `Media` | ancora `media` → **conflitto Spatie** |
| Migrate / write cieco | **vietato** finché rename (`document_media`) o bridge |

Dettaglio: [import-status.md](./import-status.md) · matrice [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist + conflitto |
| [composer-manifest.md](./composer-manifest.md) | Manifest |

## Hub / BMAD

Story [2.4](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
