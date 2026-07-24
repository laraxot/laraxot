---
title: "Inventory — Model persistence coverage (migration / seeder / factory)"
type: status
tags: [inventory, seeders, factories, migrations, coverage]
created: 2026-07-24
updated: 2026-07-24
qmd: "Inventory model migration seeder factory coverage status"
---

# Inventory — Model persistence coverage

Ogni modello concreto (`app/Models/*.php`, esclusi astratti/trait/contract/enum)
deve avere migration + seeder + factory. Stato aggiornato al 2026-07-24.

## Copertura

| Modello             | Tabella               | Migration | Factory | Seeder |
|---------------------|-----------------------|-----------|---------|--------|
| Warehouse           | warehouses            | OK        | OK      | OK (nuovo) |
| WarehouseLocation   | warehouse_locations   | OK        | OK      | OK (nuovo) |
| InventoryMovement   | inventory_movements   | OK        | OK      | OK (nuovo) |
| ProductUnit         | product_units         | OK        | OK      | OK (nuovo) |
| InventoryOverviewRow | (nessuna — read model) | SKIP     | SKIP    | SKIP   |

## Seeder creati

- `WarehouseSeeder` — 3 record via `WarehouseFactory`
- `WarehouseLocationSeeder` — 5 record via `WarehouseLocationFactory`
- `ProductUnitSeeder` — 5 record via `ProductUnitFactory`
- `InventoryMovementSeeder` — 5 record via `InventoryMovementFactory`
- `InventoryDatabaseSeeder` — orchestratore (Warehouse -> WarehouseLocation ->
  ProductUnit -> InventoryMovement, ordine di dipendenza)

Namespace: `Modules\Inventory\Database\Seeders`. Ogni seeder e idempotente
(early-return se la tabella contiene gia record) seguendo il pattern
di `Modules/WorkOrder/database/seeders`.

## Skip motivati

- **InventoryOverviewRow** — NON e una entita persistita: e un *read model /
  proiezione*. Non ha tabella fisica ne migration (`create_inventory_overview_rows_table`
  assente), non usa il trait `HasFactory`, ha `timestamps = false`,
  `incrementing = false` e chiave stringa. Viene idratato a runtime da
  `GetInventoryOverviewRowsAction` tramite `fromSub()` (vedi
  `app/Filament/Pages/InventoryOverview.php`). Di conseguenza **non richiede**
  ne factory ne seeder ne migration: la "factory mancante" segnalata coincide
  con questo modello e va legittimamente saltata.
- **inventory_movement_product_units** — tabella pivot pura (relazione
  `belongsToMany` tra `ProductUnit` e `InventoryMovement`), nessun modello
  dedicato: nessun seeder/factory autonomo. Popolabile in futuro tramite
  `attach()` all'interno dei seeder esistenti se necessario.
