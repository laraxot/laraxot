---
title: "Porting gestionale_commesse — Bom (redo reshape)"
type: concept
tags: [import, gestionale-commesse, bom, xotbase, reshape, redo]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting Bom gestionale_commesse XotBase reshape redo"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Porting gestionale_commesse — Bom (redo reshape, 2026-07-23)

Il modulo era stato riportato ad una copia grezza DDD (rinominata singolare) da un ripristino precedente. Questa sessione ha **rifatto il reshape XotBase** in loco:

## Stato all'ingresso di questa sessione

- `app/Models` già presenti ed estendevano `XotBaseModel` (Bom, BomItem) — non erano DDD grezzi come atteso, ma mancava il resto del reshape.
- `app/Filament/Resources/BomResource` estendeva `\Filament\Resources\Resource` (non `XotBaseResource`), con `form()`/`table()` diretti.
- `BomItemsRelationManager` estendeva `\Filament\Resources\RelationManagers\RelationManager` (non `XotBaseRelationManager`).
- Migrazione unica `2026_02_25_000020_create_bom_tables.php` con `Schema::create` per entrambe le tabelle (`boms`, `bom_items`) in un solo file, `$table->timestamps()` esplicito, `extends Migration` semplice.
- Nessun `app/Actions` (il modulo non ha logica applicativa oltre CRUD Filament).
- Nessun `lang/`, nessuna `tests/`, nessuna factory.

## Interventi di questa sessione

1. **Filament**: `BomResource extends XotBaseResource` (metodo `getFormSchema(): array` invece di `form(Schema)`, dato che `XotBaseResource::form()` è `final` e richiede l'abstract `getFormSchema()`); `BomItemsRelationManager extends XotBaseRelationManager` con `getFormSchema()` (anche qui `form()` di `XotBaseRelationManager` è `final`).
2. **Migrazioni**: consolidate 1-per-modello, `XotBaseMigration`, `tableCreate()`/`tableUpdate()` + `updateTimestamps()`:
   - `2026_02_25_000020_create_boms_table.php`
   - `2026_02_25_000021_create_bom_items_table.php`
   - (il vecchio file unico `create_bom_tables.php` rimosso)
3. **composer.json**: aggiunta dipendenza `spatie/queueable-action` in `require`, repository path `../Catalog` (dipendenza da `Modules\Catalog\Models\Product`).
4. **lang/{it,en}/boms.php**: chiavi `navigation`, `bom`, `boms`, `fields.*` — sostituite le label hardcoded italiane nel Resource/RelationManager.
5. **tests/**: `TestCase extends XotBaseTestCase`, `Pest.php`, `tests/Unit/BomModelTest.php` (creazione Bom, link BomItem→Bom); `database/factories/BomFactory.php`, `BomItemFactory.php` (con `HasFactory` aggiunto ai model).
6. **PHPStan**: fix `ViewBom::getMaxContentWidth()` return type (`Width|string|null` → `Width`, mai null/string in pratica).

## Verifica

- `php -l` su tutti i file del modulo: OK.
- `phpstan analyse Modules/Bom` (insieme a Inventory/Production): errori residui sono rumore di baseline già presente in moduli accettati (es. `Vehicle`) — `missingType.generics` su `HasFactory`, `property.notFound` su proprietà magiche (`guarded = []`), `method.internalClass` nei test Pest, `classConstant.unused` sulle costanti `ACCOUNT_FEATURE_*` (usate dinamicamente via `defined()/constant()` in `HasAccountFeatureVisibility`). Nessun errore bloccante introdotto da questa sessione.

## Non ancora fatto (fuori scope minimale di questa sessione)

- Nessuna Action dedicata: il modulo resta CRUD puro via Filament, coerente con la sorgente.
