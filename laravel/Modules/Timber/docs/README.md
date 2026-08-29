---
title: "Timber (ex TimberBilling) — knowledge base"
type: overview
tags: [timber, gestionale-commesse, replica, bmad, migrations]
created: 2026-07-23
updated: 2026-07-27
qmd: "Timber (ex TimberBilling) replica XotBaseMigration one model wood billing"
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

# Timber (ex TimberBilling)

## Perché esiste

Verticale fatturazione timber SRC. Replica — enable solo con ordine dopo gate.

## Religione schema

**29 modelli = 29 `create_*` `XotBaseMigration`.** Mai `Illuminate\Migration` / `Schema::create('tabella')`.  
Canon: [migration-xotbase-one-per-model.md](./wiki/concepts/migration-xotbase-one-per-model.md) · [parity](./models-migrations-seeders-factories.md)

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** (riabilitato 2026-07-27, confermato dall'utente) |
| PHPStan L10 | 0 errori reali (riverificato 2026-07-27, dopo refactor `BaseModel`) |
| Pest | 86/169 passed — residuo quasi tutto legato a ruoli/permessi assenti (vedi sotto), non bug Timber (ex TimberBilling) |

[import-status.md](./import-status.md) · [users FK 1824](./bugfix-users-cross-db-fk.md) · [matrice](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
