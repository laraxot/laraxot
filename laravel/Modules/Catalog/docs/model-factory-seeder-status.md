---
title: "Catalog — model / migration / factory / seeder coverage"
type: status
tags: [catalog, models, factory, seeder, migration, ponytail]
created: 2026-07-24
updated: 2026-07-24
qmd: "Catalog copertura modelli concreti factory seeder migration"
related:
  - "./README.md"
  - "./import-status.md"
---

# Catalog — copertura modelli concreti

Regola: ogni modello concreto (`app/Models/*.php`, escluso abstract/trait/contract/enum/pivot/STI)
deve avere migration + factory + seeder.

## Modelli concreti

Tutti e 7 i modelli in `app/Models/` sono classi `final` concrete che estendono `Modules\Xot\Models\XotBaseModel`.
Nessun pivot, nessuna STI, nessuna tabella condivisa, nessun astratto: quindi nessuno skip.

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| `CatalogFrequency` | `catalog_frequencies` | esistente | **nuova** `CatalogFrequencyFactory` | **nuovo** `CatalogFrequencySeeder` |
| `CatalogMeasureUnit` | `catalog_measure_units` | esistente | **nuova** `CatalogMeasureUnitFactory` | **nuovo** `CatalogMeasureUnitSeeder` |
| `CatalogPartType` | `catalog_part_types` | esistente | **nuova** `CatalogPartTypeFactory` | **nuovo** `CatalogPartTypeSeeder` |
| `CatalogModel` | `catalog_models` | esistente | **nuova** `CatalogModelFactory` | **nuovo** `CatalogModelSeeder` |
| `Product` | `products` (soft deletes) | esistente | **nuova** `ProductFactory` | **nuovo** `ProductSeeder` |
| `MetelCatalog` | `metel_catalogs` | esistente | **nuova** `MetelCatalogFactory` | `MetelDemoSeeder` (esistente) |
| `MetelCatalogItem` | `metel_catalog_items` | esistente | **nuova** `MetelCatalogItemFactory` | `MetelDemoSeeder` (esistente) |

## Skip motivati

- Nessun modello saltato. Non esistono pivot, STI, tabelle condivise o classi astratte in `app/Models/`.
- Nessuna migration creata: tutte le 7 tabelle avevano già la propria migration `XotBaseMigration`
  con `updateTimestamps()` in `tableUpdate`. Regola forward-only rispettata (nessuna modifica alle migration esistenti).

## Note factory

- Le factory usano il namespace `Modules\Catalog\Database\Factories` risolto da
  `Modules\Xot\Actions\Factory\GetFactoryAction` via `HasXotFactory` (già in `XotBaseModel`).
- `ProductFactory` genera le 4 FK di lookup tramite le rispettive factory (`CatalogModel`,
  `CatalogPartType`, `CatalogMeasureUnit`, `CatalogFrequency`).
- `MetelCatalogItemFactory` genera `metel_catalog_id` via `MetelCatalog::factory()`; `product_id` resta `null`
  (relazione opzionale, matching prodotto lato import).
- `definition()` derivata da `$fillable`/`$guarded` + `casts()`; valori realistici del dominio idrotermosanitario.

## Seeder

- `CatalogDatabaseSeeder` orchestra: lookup (frequenze, unità, tipi, modelli) → `ProductSeeder` → `MetelDemoSeeder`.
- Tutti i seeder sono idempotenti (guard `exists()` o `updateOrCreate`).
- I due modelli Metel restano coperti dall'esistente `MetelDemoSeeder` (dati demo realistici) invece di duplicare dataset.
