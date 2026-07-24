---
title: "EnergyBroker — stato modelli ↔ migration ↔ seeder ↔ factory"
type: reference
tags: [energybroker, models, migrations, seeders, factories, persistence]
created: 2026-07-24
updated: 2026-07-24
qmd: "EnergyBroker persistence coverage models migrations seeders factories"
related:
  - "./README.md"
---

# EnergyBroker — copertura persistenza

Ogni modello concreto di dominio deve avere migration + seeder + factory.
Le factory sono risolte automaticamente da `XotBaseModel` via `HasXotFactory` →
`GetFactoryAction` (namespace atteso `Modules\EnergyBroker\Database\Factories\<Model>Factory`),
quindi **non** serve `newFactory()` o `HasFactory` nei modelli.

## Matrice copertura

| Modello | Tabella | Migration | Factory | Seeder | Note |
|---|---|---|---|---|---|
| `EnergyBrokerImportBatch` | `energy_broker_import_batches` | ✅ | ✅ (creata) | ✅ (creata) | radice import, FK user nullable |
| `EnergyBrokerDocument` | `energy_broker_documents` | ✅ | ✅ (creata) | ✅ (creata) | SoftDeletes, `public_id` uuid auto |
| `EnergyBrokerCustomerMatch` | `energy_broker_customer_matches` | ✅ | ✅ (creata) | ✅ (creata) | FK document required |
| `EnergyBrokerSupplyPoint` | `energy_broker_supply_points` | ✅ | ✅ (creata) | ✅ (creata) | SoftDeletes, FK customer required |
| `EnergyBrokerSupplyHistory` | `energy_broker_supply_histories` | ✅ | ✅ (creata) | ✅ (creata) | FK supply_point required |
| `EnergyBrokerLargeUpload` | `energy_broker_large_uploads` | ✅ | ✅ (creata) | ✅ (creata) | SoftDeletes, upload chunk |

Nessuno skip: tutti e 6 i modelli sono classi `final` concrete che estendono
`XotBaseModel`. Non esistono pivot, STI, tabelle condivise o modelli astratti
nel modulo, quindi nessun file è stato saltato.

## File creati (2026-07-24)

Factory (`database/factories/`):
- `EnergyBrokerImportBatchFactory.php`
- `EnergyBrokerDocumentFactory.php`
- `EnergyBrokerCustomerMatchFactory.php`
- `EnergyBrokerSupplyPointFactory.php`
- `EnergyBrokerSupplyHistoryFactory.php`
- `EnergyBrokerLargeUploadFactory.php`

Seeder (`database/seeders/`):
- `EnergyBrokerImportBatchSeeder.php`
- `EnergyBrokerDocumentSeeder.php`
- `EnergyBrokerCustomerMatchSeeder.php`
- `EnergyBrokerSupplyPointSeeder.php`
- `EnergyBrokerSupplyHistorySeeder.php`
- `EnergyBrokerLargeUploadSeeder.php`
- `EnergyBrokerDatabaseSeeder.php` (orchestratore, ordine per dipendenze FK)

Ogni seeder è idempotente (guard `if (Model::query()->exists()) return;`) e
crea 2 record via factory, come il pattern `WorkOrder`. Le factory usano
`Customer::factory()` / `EnergyBrokerDocument::factory()` / `EnergyBrokerSupplyPoint::factory()`
per le FK obbligatorie; le FK nullable sono lasciate a `null` (YAGNI).

## Note migration

Le migration erano già presenti e conformi a `XotBaseMigration`
(`tableCreate` + `updateTimestamps(..., hasSoftDeletes: true)` per i timestamp,
non in `tableCreate`). Nessuna migration è stata creata o modificata:
tutte le tabelle esistevano già. `energy_broker_large_uploads` usa ancora lo
stile `Schema::create` classico (migration più vecchia, 2026-05-13) ma la
tabella esiste, quindi forward-only nessun intervento.
