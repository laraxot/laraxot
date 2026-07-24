---
title: "Import status — Catalog"
type: concept
tags: [import, gestionale-commesse, catalog, phpstan]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Catalog phpstan enabled metel"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Catalog

- [x] Cartella singolare / replica
- [x] Layout `app/` + XotBase SP
- [x] Resources / Pages / RelationManagers XotBase*
- [x] Migrazioni 1-per-modello
- [x] QueueableAction
- [x] `config/metel.php` senza `env()` (overlay in ServiceProvider)
- [x] **PHPStan L10** — 0 errori reali (2026-07-24)
- [x] `modules_statuses=true`
- [x] Pest Feature SRC migrati — **11 passed** (2026-07-24): vedi [tests-migration.md](./tests-migration.md)
