---
title: "Signature — knowledge base"
type: overview
tags: [signature, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Signature docs README purpose firma sketch closeout"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/wave1-fo-operator-note.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Signature

## Perché esiste

**Close-out sul campo**: firma e sketch legati all’intervento (accettazione lavoro / rilievo). Valore legale/operativo, non cosmetico UI.

## Cosa NON è

- Non è upload generico documenti (→ Document/Media).
- Non vive nei Themes in Wave 1 → panel `signature/admin` Capture*.

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | `signatures`, `sketches` |
| Panel | pages Capture (no Resource CRUD ancora) |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [README.md](./README.md) | Scopo |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- [wave1-fo-operator-note.md](../../../../laravel/Themes/docs/wave1-fo-operator-note.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md)
