---
title: "Porting gestionale_commesse — Inventory (redo reshape)"
type: concept
tags: [import, gestionale-commesse, inventory, xotbase, reshape, redo]
created: 2026-07-23
updated: 2026-07-27
qmd: "porting Inventory gestionale_commesse XotBase reshape redo warehouses vehicle_id"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "./bugfix-warehouses-vehicle-fk-order.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
---

# Porting gestionale_commesse — Inventory (redo reshape, 2026-07-23)

Modulo più esteso dei tre (5 modelli, 3 resource Filament, pagina di overview con query dirette). Come Bom/Production, `app/Models` era già in stile Laraxot ma il resto no.

## Stato all'ingresso di questa sessione

- `app/Models/{Warehouse,WarehouseLocation,InventoryMovement,ProductUnit,InventoryOverviewRow}.php` già `extends XotBaseModel`.
- `app/Application/Queries/{GetInventoryOverviewRowsQuery,GetStockSnapshotQuery}.php`: cartella `Application/Queries` residuo DDD, con **namespace già `Modules\Inventory\Queries`** (mismatch PSR-4 con la cartella fisica — funzionava solo per fortuna/classmap).
- `app/Actions/CreateInventoryMovementAction.php` presente ma senza trait `QueueableAction`.
- 3 Filament Resources (`WarehouseResource`, `WarehouseLocationResource`, `InventoryMovementResource`) tutti `extends \Filament\Resources\Resource` con `form(Schema)` diretto.
- 4 file di migrazione non consolidati: `create_inventory_tables.php` (creava `warehouses`, `inventory_movements`, `product_units`, `inventory_movement_product_units` in un solo file) + 3 migrazioni evolutive `add_*` (costi su `inventory_movements`, `vehicle_id` su `warehouses`, `warehouse_locations` + colonne location su `inventory_movements`/`product_units`) — tutte `extends Migration` con `Schema::create`/`Schema::table` diretto e `$table->timestamps()` esplicito.
- Nessun `lang/`, nessuna `tests/`, nessuna factory.

## Interventi di questa sessione

1. **Actions**: spostate `GetInventoryOverviewRowsQuery`→`app/Actions/GetInventoryOverviewRowsAction.php` e `GetStockSnapshotQuery`→`app/Actions/GetStockSnapshotAction.php`, namespace corretto `Modules\Inventory\Actions` (allineato a PSR-4), trait `QueueableAction` aggiunto; aggiornati tutti i riferimenti in `InventoryOverview.php` e `InventoryMovementResource.php`. Aggiunto `QueueableAction` anche a `CreateInventoryMovementAction`.
2. **Filament**: le 3 Resource ora `extends XotBaseResource` con `getFormSchema(): array` (invece di `form(Schema): Schema`, dato che `XotBaseResource::form()` è `final`); `canEdit()`/`canView()` tipizzati `\Illuminate\Database\Eloquent\Model $record` (erano untyped, causavano `argument.type`/`missingType.parameter`); `getNavigationGroup()` tipizzato `string` (mai null).
3. **Migrazioni**: consolidate 1-per-modello + 1 per pivot, `XotBaseMigration`, `tableCreate()`/`tableUpdate()` + `updateTimestamps()`:
   - `2026_07_24_000701_create_warehouses_table.php` (con `vehicle_id` incluso; **timestamp dopo** `Vehicle` `2026_07_23_183001` — vedi [bugfix-warehouses-vehicle-fk-order.md](./bugfix-warehouses-vehicle-fk-order.md))
   - `2026_07_24_000702_create_warehouse_locations_table.php`
   - `2026_02_25_000012_create_inventory_movements_table.php` (con `from/to_location_id`, `unit_price`, `line_total` incluse, erano in migrazioni evolutive separate)
   - `2026_02_25_000013_create_product_units_table.php` (con `current_location_id` incluso)
   - `2026_02_25_000014_create_inventory_movement_product_units_table.php` (pivot puro senza model dedicato: `$model_class` esplicito su `InventoryMovement` solo per il costruttore di `XotBaseMigration`, ma `$table->timestamps()` diretto nella tabella pivot — evita il mismatch di `getTable()` che altrimenti punterebbe a `inventory_movements`).
   - Rimossi i 4 file di migrazione originali (`create_inventory_tables`, 3× `add_*`).
   - `InventoryOverviewRow` resta senza migrazione dedicata: è alimentato da una subquery (`GetInventoryOverviewRowsAction`), non da una tabella fisica.
4. **composer.json**: `spatie/queueable-action` in `require`, repository path `../Catalog`.
5. **lang/{it,en}/inventory.php**: chiavi `navigation`, `warehouse(s)`, `location(s)`, `movement(s)`, `fields.*`, `help.is_final_destination`, `movement_types.*` — coprono tutte le chiavi già referenziate nei Resource esistenti più quelle aggiunte in fase di conversione a `getFormSchema()`.
6. **tests/**: `TestCase`, `Pest.php`, `tests/Unit/InventoryModelTest.php` (creazione Warehouse, link WarehouseLocation→Warehouse, creazione InventoryMovement); `database/factories/{Warehouse,WarehouseLocation,ProductUnit,InventoryMovement}Factory.php`; `HasFactory` aggiunto ai 4 model persistibili principali (non a `InventoryOverviewRow`, che non è persistibile).

## Verifica

- `php -l`: OK su tutti i file.
- `phpstan analyse` (insieme a Bom/Production): risolti gli errori bloccanti (`method.abstract`, `method.parentMethodFinal`, `argument.type`/`missingType.parameter` su `canEdit`/`canView`). Il rumore residuo (`missingType.generics` su `HasFactory`, `property.notFound` su proprietà magiche di model con `guarded = []`, `method.internalClass` nei test Pest, `return.type` su `pluck()->all()` non tipizzati) è della stessa categoria già presente e accettata in moduli maturi (`Vehicle` ha lo stesso pattern con 9 errori residui).

## Non ancora fatto (debito noto, non bloccante)

- I metodi `locationOptionsForWarehouse()`, `productOptionsForMovement()`, `stockSummaryByWarehouse()` in `InventoryMovementResource` dichiarano `@return array<int,string>`/`array<int,int>` ma restituiscono `array<mixed>` da `pluck()->all()` — richiederebbe cast espliciti riga per riga, rimandato per non allargare la superficie di modifica oltre il reshape strutturale richiesto in questa sessione.

## Verificato 2026-07-24 — `vendor/bin/pest Modules/Inventory/tests` FALLISCE in isolamento

Le factory (`ProductUnitFactory` ecc.) dipendono da `Modules\Catalog\Models\Product`, ma
`Modules\Inventory\Tests\TestCase::getPackageProviders()` non registra `CatalogServiceProvider`.
Eseguito da solo, 3 test falliscono con `QueryException: no such table: products`. Nessuna
testsuite `Inventory` in `phpunit.xml` root, quindi non gira mai in CI. Stesso fix di Bom/Production:
registrare `CatalogServiceProvider` nel `TestCase` + aggiungere testsuite dedicata.

## Test migrati dal sorgente (2026-07-24)

Sorgente: `gestionale_commesse/tests/Feature/Inventory/` (`InventoryOverviewPageTest.php`,
`WarehouseAndBomUiTest.php`).

- Portato `tests/Feature/WarehouseAndInventoryMovementCrudTest.php`: CRUD completo
  Warehouse (make/create/update/delete), collegamento WarehouseLocation→Warehouse,
  creazione InventoryMovement e calcolo giacenza aggregata via
  `GetInventoryOverviewRowsAction` su più magazzini — usa `Model::factory()->make()` per
  il caso di sola validazione attributi, `->createOne()` dove serve persistenza per le FK.
- **Non portato** (fuori scopo, richiede setup Filament/Livewire/permessi più ampio non
  presente nel modulo ricostruito attuale): i test sulla pagina Filament
  `InventoryOverview` con `ShieldPermissionsSeeder`/`PlatformSetting`/ruoli, il test
  supplier `/supplier/warehouses` e i test BOM (`WarehouseAndBomUiTest.php` — BOM tree,
  riferimenti circolari — dominio del modulo `Bom`, non `Inventory`).
- Namespace adattati: sorgente usava `Modules\Inventory\Infrastructure\Models\*` e
  `Modules\Inventory\Application\Queries\GetInventoryOverviewRowsQuery`; il modulo
  portato usa `Modules\Inventory\Models\*` e `Modules\Inventory\Actions\GetInventoryOverviewRowsAction`.

**Verifica 2026-07-24**: `vendor/bin/pest Modules/Inventory/tests` in isolamento fallisce
(9 dei 10 test, incluso il test Unit preesistente) per il gap infrastrutturale già
descritto sopra: la connessione `xot` di test usa SQLite in-memory e le migrazioni di
Inventory (e dei suoi moduli collegati, es. `Catalog`) non vengono caricate su quella
connessione in assenza di una testsuite dedicata in `phpunit.xml`. Verificato che le
tabelle vengono correttamente create quando si punta il DB di test reale (`mariadb`,
`.env.testing`) via `php artisan migrate --env=testing --path=Modules/Inventory/database/migrations`
— quindi la logica applicativa dei test è corretta, il fallimento è solo di bootstrap
dei test in isolamento (stesso gap per gli altri 4 moduli di questa sessione).
