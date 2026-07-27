---
title: "Inventory — knowledge base"
type: overview
tags: [inventory, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-27
qmd: "Inventory docs README purpose gestionale warehouse vehicle"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "./bugfix-warehouses-vehicle-fk-order.md"
  - "./bugfix-migration-users-uuid-fk.md"
  - "./model-persistence-status.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Inventory

## Perché esiste

Magazzino / movimenti / ubicazioni: stock a supporto di interventi e produzione.
Un warehouse può essere **mobile** (furgone) → `vehicle_id` verso modulo Vehicle.

## Runtime

| Gate | Stato |
|---|---|
| `modules_statuses` | **true** |
| Docs | [import-status.md](./import-status.md) |

## Indice docs

| Doc | Scopo |
|-----|--------|
| [import-status.md](./import-status.md) | Enable / gap runtime |
| [gestionale-commesse-port.md](./gestionale-commesse-port.md) | Port reshape XotBase |
| [model-persistence-status.md](./model-persistence-status.md) | Migration / factory / seeder |
| [bugfix-warehouses-vehicle-fk-order.md](./bugfix-warehouses-vehicle-fk-order.md) | MySQL 1824 `vehicles` order |
| [bugfix-migration-users-uuid-fk.md](./bugfix-migration-users-uuid-fk.md) | UUID user FK |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- Story [2.3](../../../../docs/stories/2.3.deep-quality-enabled-modules.story.md) · [2.4](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
- [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
