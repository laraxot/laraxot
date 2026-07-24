---
title: "Import status — Document (replica, media risk)"
type: concept
tags: [document, replica, import, media-conflict, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Document import status enabled media spat ie conflict risk"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Document

> Replica Wave 3. **Non** merge in Media. **Non** cancellare.

## Stato runtime

- [x] Reshape `app/` + XotBase SP / Resources
- [x] `modules_statuses=true` (JSON 2026-07-24)
- [ ] **Debito critico:** `Modules\Document\Models\Media` usa ancora `protected $table = 'media'` → conflitto Spatie/Media
- [ ] Rename tabella dominio (`document_media`) o bridge esplicito **prima** di migrate/write massivi

## Conflitto `media`

| Owner | Tabella | Schema |
|---|---|---|
| Spatie / Media module | `media` (già in DB) | `model_type`, `file_name`, … |
| Document (SRC) | vuole `media` | `mediable_*`, `kind`, `path`, … |

Enable JSON ≠ schema safe. Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
