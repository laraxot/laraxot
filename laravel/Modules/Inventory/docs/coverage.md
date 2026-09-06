---
name: inventory-coverage
description: Stato PHPStan e Pest per Modules/Inventory dopo la campagna zero-errori 2026-09-06
metadata:
  module: Inventory
  updated: 2026-09-06
---

# Coverage e qualita — Modules/Inventory

## PHPStan

- Prima: 52 errori (`cast.int` / `cast.string` su valori `mixed` provenienti da closure Filament `$get()`,
  `Model::getAttribute()`, `Collection::pluck()->all()`; piu' `method.deprecated` su `Table::actions()` /
  `Table::bulkActions()` e `method.deprecatedClass` / `staticMethod.deprecatedClass` su
  `Filament\Forms\Components\Placeholder`).
- Dopo: **0 errori** (`./vendor/bin/phpstan analyse Modules/Inventory` con cache pulita, livello del
  `phpstan.neon` del repo, invariato — nessuna modifica al file).

File corretti:
- `app/Filament/Pages/InventoryOverview.php`
- `app/Filament/Resources/InventoryMovementResource.php`
- `app/Filament/Resources/InventoryMovementResource/Pages/CreateInventoryMovement.php`
- `app/Filament/Resources/InventoryMovementResource/Pages/EditInventoryMovement.php`
- `app/Filament/Resources/WarehouseLocationResource.php`
- `app/Filament/Resources/WarehouseResource.php`
- `tests/Feature/WarehouseAndInventoryMovementCrudTest.php`

Pattern applicato: sostituiti tutti i cast diretti `(int)`/`(string)` su valori `mixed` con
`Modules\Xot\Actions\Cast\SafeIntCastAction::cast()` / `SafeStringCastAction::cast()` (azioni canoniche
gia' usate in Geo/Blog/Catalog per lo stesso problema — vedi
`Modules/Xot/docs/safe-casting-actions.md`), che tracciano il tipo reale invece di forzare un cast cieco.
`Filament\Forms\Components\Placeholder` (deprecata, estende `TextEntry`) sostituita con
`Filament\Infolists\Components\TextEntry` + `->state()` al posto di `->content()`.
`Table::actions()` / `Table::bulkActions()` (deprecati in Filament v4) sostituiti con
`recordActions()` / `toolbarActions()`, pattern gia' in uso in `Modules/Catalog`.

## PHPMD

`./tools/phpmd.sh Modules/Inventory text phpmd.xml` (il path `../docs/phpmd.ruleset.xml` indicato nella
consegna non esiste in questo repo; usato `laravel/phpmd.xml`, il ruleset effettivo del progetto)
segnala solo violazioni preesistenti in file non toccati da questo intervento
(`Actions/CreateInventoryMovementAction.php` ExcessiveParameterList, `Actions/GetStockSnapshotAction.php`
ShortVariable, `Policies/*` UnusedFormalParameter, `Providers/*ServiceProvider.php` CamelCasePropertyName).
Nessuna nuova violazione introdotta dai file modificati.

## PHPInsights

Non eseguito: rimosso dal repo, incompatibile con Pest 5 (vedi memoria second-brain
`pest5-incompatibile-con-phpinsights`), confermato anche dall'hook di post-edit di questo repo.

## Pest

`./vendor/bin/pest Modules/Inventory/tests --no-coverage`: 9 failed, 1 passed — **blocco preesistente,
non causato da questa modifica**: `SQLSTATE[42S02]: Base table or view not found: 1146 Table
'workorder_data_test.warehouses' doesn't exist`. Verificato con `php artisan migrate:status --env=testing`
che il DB di test condiviso ha decine di migrazioni `Pending` su piu' moduli (non solo Inventory) — stato
del DB di test antecedente a questa sessione, non un side-effect dei cast fix (che non toccano
migrazioni/schema). Non eseguito `migrate` per non rischiare interferenze con altri agenti che stanno
lavorando in concorrenza sullo stesso DB condiviso (regola "data sacra": nessuna operazione distruttiva o
a rischio sul DB condiviso senza autorizzazione esplicita del proprietario).

Nessuna regressione rispetto allo stato pre-esistente: i fallimenti sono tutti a livello di inserimento
DB (`WarehouseFactory` / `InventoryMovementFactory`), prima ancora che il codice modificato (Filament
Resources/Pages) venga eseguito.
