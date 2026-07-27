---
title: "Porting gestionale_commesse — Production (redo reshape)"
type: concept
tags: [import, gestionale-commesse, production, xotbase, reshape, redo]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting Production gestionale_commesse XotBase reshape redo"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Porting gestionale_commesse — Production (redo reshape, 2026-07-23)

Come per Bom/Inventory, il modulo era tornato a uno stato intermedio: `app/Models`/`app/Actions` già in stile Laraxot ma Filament e migrazioni ancora nello stile grezzo.

## Stato all'ingresso di questa sessione

- `app/Models/ProductionOrder.php`, `ProductionPhase.php` già `extends XotBaseModel`.
- `app/Actions/CreateProductionOrderAction.php`, `AdvanceProductionPhaseAction.php` presenti ma **senza** trait `Spatie\QueueableAction\QueueableAction`.
- `ProductionOrderResource extends \Filament\Resources\Resource` con `form(Schema)` diretto; `ProductionPhasesRelationManager extends \Filament\Resources\RelationManagers\RelationManager` con `form(Schema)` diretto.
- Migrazione unica `2026_02_25_000030_create_production_tables.php` (`Schema::create` per `production_orders` e `production_phases` nello stesso file, `$table->timestamps()` esplicito, `extends Migration`).
- `lang/{it,en}/production.php` **non esisteva** ma il Resource/RelationManager referenziavano già chiavi `production.fields.*`, `production.statuses.*`, `production.phase` — creati ora i file lang corrispondenti.
- Nessuna `tests/`, nessuna factory.

## Interventi di questa sessione

1. **Actions**: aggiunto `use QueueableAction;` a `CreateProductionOrderAction` e `AdvanceProductionPhaseAction`.
2. **Filament**: `ProductionOrderResource extends XotBaseResource` con `getFormSchema(): array` (era `form(Schema): Schema`, ma `XotBaseResource::form()` è `final`); `ProductionPhasesRelationManager extends XotBaseRelationManager` con `getFormSchema()` (stesso motivo, `form()` di `XotBaseRelationManager` è `final`); `table()` resta overridabile (non final) quindi invariato.
3. **Migrazioni**: consolidate 1-per-modello, `XotBaseMigration`, `tableCreate()`/`tableUpdate()` + `updateTimestamps()`:
   - `2026_02_25_000030_create_production_orders_table.php`
   - `2026_02_25_000031_create_production_phases_table.php`
4. **composer.json**: `spatie/laravel-queueable-action` in `require`, repository path `../Catalog`.
5. **lang/{it,en}/production.php**: chiavi `navigation`, `production_order(s)`, `phase`, `fields.*`, `statuses.*` (planned/in_progress/completed/cancelled/blocked).
6. **tests/**: `TestCase`, `Pest.php`, `tests/Unit/ProductionOrderModelTest.php` (creazione ordine, link fase→ordine); `database/factories/ProductionOrderFactory.php`, `ProductionPhaseFactory.php`; `HasFactory` aggiunto ai model.

## Verifica

- `php -l`: OK su tutti i file.
- `phpstan analyse` (insieme a Bom/Inventory): nessun errore bloccante residuo (`final method`/`abstract method` risolti). Il rumore residuo (`missingType.generics` su HasFactory, `property.notFound` su proprietà magiche, `classConstant.unused` su `ACCOUNT_FEATURE_*`) è identico per categoria a quello già presente in moduli accettati (es. `Vehicle`).

## Verificato 2026-07-24 — `vendor/bin/pest Modules/Production/tests` FALLISCE in isolamento

`ProductionOrderFactory` dipende da `Modules\Catalog\Models\Product`, ma
`Modules\Production\Tests\TestCase::getPackageProviders()` non registra `CatalogServiceProvider`.
Eseguito da solo, 2 test falliscono con `QueryException: no such table: products`. Nessuna testsuite
`Production` in `phpunit.xml` root, quindi non gira mai in CI. Stesso fix di Bom/Inventory:
registrare `CatalogServiceProvider` nel `TestCase` + aggiungere testsuite dedicata.
