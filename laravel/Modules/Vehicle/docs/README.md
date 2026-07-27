---
title: "Vehicle — knowledge base"
type: overview
tags: [vehicle, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-27
qmd: "Vehicle docs README purpose flotta media debito inventory warehouse"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../Inventory/docs/bugfix-warehouses-vehicle-fk-order.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Vehicle

## Perché esiste

**Parco mezzi** e documenti veicolo: asset logistici del field-service (chi va dove, con quale mezzo, con quali scadenze documentali).
Inventory può collegare un magazzino mobile (`warehouses.vehicle_id`) — parent obbligatorio in migrate: vedi [bugfix Inventory](../../Inventory/docs/bugfix-warehouses-vehicle-fk-order.md).

## Cosa NON è

- Non è Document domain completo. Debito: `vehicle_documents.media_id` punta a Spatie `media` mentre Document (disabled) voleva altro schema.

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Panel | `vehicle/admin/vehicles` |
| Document | `statuses=true` ma **RISCHIO** tabella `media` — vedi matrice |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist + debito media |
| [README.md](./README.md) | Scopo |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
