---
title: "Bugfix — warehouses.vehicle_id FK prima di vehicles (MySQL 1824)"
type: troubleshooting
tags: [inventory, warehouse, vehicle, migration, foreign-key, mysql-1824]
created: 2026-07-27
updated: 2026-07-27
qmd: "warehouses vehicle_id foreign key 1824 Failed to open referenced table vehicles migration order"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./gestionale-commesse-port.md"
  - "./model-persistence-status.md"
  - "./bugfix-migration-users-uuid-fk.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
  - "../../../../docs/wiki/memories/one-migration-per-model-bump-timestamp.md"
  - "../../Vehicle/docs/README.md"
---

# Bugfix — `warehouses` → `vehicles` (errore 1824)

## Perché (business)

Un **Warehouse** può essere:

| Tipo | Significato |
|------|-------------|
| Fisso | Sede / deposito (`vehicle_id` null) |
| Mobile | Stock sul furgone → `vehicle_id` → `Vehicle` |

La relazione esiste in dominio gestionale_commesse (port consolidato in una sola `create_warehouses`). Eloquent: `Warehouse::vehicle()`.

## Filosofia / politica / zen

| Livello | Intent |
|---------|--------|
| Religione | 1 model = 1 migration; evoluzione = edit + bump timestamp |
| Politica moduli | Vehicle `priority` 100, Inventory 120 — Vehicle è **parent** |
| Stessa connection | `xot` / `workorder_data` → FK DB ammessa **solo se** `vehicles` già esiste |
| Zen | Colonna da `foreignIdFor(Vehicle::class)`; vincolo DB **differito** e idempotente |
| KISS | Non `constrained()` dentro `tableCreate` cross-modulo fragile sull’ordine |

## Sintomo

```
SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'vehicles'
alter table `warehouses` add constraint `warehouses_vehicle_id_foreign` ...
```

Laravel in `Schema::create` emette spesso CREATE + ALTER FK. Se `vehicles` manca: CREATE riesce, ALTER fallisce → tabella orfana **senza** vincolo.

## Causa radice

Timestamp Inventory storico `2026_02_25_*` **prima** di `Vehicle` `2026_07_23_183001_create_vehicles_table`. Ordine cronologico del filename ≠ dipendenza di dominio.

## Fix applicato

File: `database/migrations/2026_07_24_000701_create_warehouses_table.php` (dopo `create_vehicles`).

1. `foreignIdFor(Vehicle::class)->nullable()->index()` in `tableCreate` — **senza** `constrained()`
2. `ensureVehicleForeignKey()` a fine `up()` — aggiunge FK solo se `vehicles` c’è e il vincolo manca
3. `tableUpdate` ripara colonna assente (idempotente)

## Checklist agente

- [ ] `migrate:status` → `create_vehicles` prima di `create_warehouses`
- [ ] Nessun secondo file `add_vehicle_id_to_warehouses`
- [ ] Stesso pattern per altre FK cross-modulo opzionali (es. movements → work_orders)

## Collegamenti

- Canon FK: [migration-foreign-id-for.md](../../../../docs/wiki/concepts/migration-foreign-id-for.md)
- Bump timestamp: [one-migration-per-model-bump-timestamp.md](../../../../docs/wiki/memories/one-migration-per-model-bump-timestamp.md)
- Port GC: [gestionale-commesse-port.md](./gestionale-commesse-port.md)
