---
title: "TimberBilling — knowledge base"
type: overview
tags: [timberbilling, gestionale-commesse, replica, bmad, migrations]
created: 2026-07-23
updated: 2026-07-27
qmd: "TimberBilling replica XotBaseMigration one model wood billing"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./models-migrations-seeders-factories.md"
  - "./bugfix-users-cross-db-fk.md"
  - "./wiki/concepts/migration-xotbase-one-per-model.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# TimberBilling

## Perché esiste

Verticale fatturazione timber SRC. Replica — enable solo con ordine dopo gate.

## Religione schema

**29 modelli = 29 `create_*` `XotBaseMigration`.** Mai `Illuminate\Migration` / `Schema::create('tabella')`.  
Canon: [migration-xotbase-one-per-model.md](./wiki/concepts/migration-xotbase-one-per-model.md) · [parity](./models-migrations-seeders-factories.md)

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **false** |

[import-status.md](./import-status.md) · [users FK 1824](./bugfix-users-cross-db-fk.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
