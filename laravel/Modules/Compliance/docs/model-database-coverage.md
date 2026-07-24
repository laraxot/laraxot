# Compliance — Copertura Model / Migration / Seeder / Factory

Stato aggiornato: 2026-07-24 (Ponytail Mode)

Obiettivo: ogni **modello concreto** del modulo deve avere migration + seeder + factory.

## Modelli concreti (`app/Models/`)

Tutti i 9 modelli sono concreti (`final`, estendono direttamente `XotBaseModel`,
tabella dedicata). Nessun modello e' pivot / STI / astratto / a tabella condivisa.

| Modello | Tabella | Migration | Factory | Seeder (per-model) |
|---|---|---|---|---|
| `Boiler` | `boilers` | esistente | `BoilerFactory` (nuovo) | `BoilerSeeder` (nuovo) |
| `ComplianceDocument` | `compliance_documents` | esistente | `ComplianceDocumentFactory` (nuovo) | `ComplianceDocumentSeeder` (nuovo) |
| `CustomerSystem` | `customer_systems` | esistente | `CustomerSystemFactory` (nuovo) | `CustomerSystemSeeder` (nuovo) |
| `Equipment` | `equipment` | esistente | `EquipmentFactory` (nuovo) | `EquipmentSeeder` (nuovo) |
| `HeatingSystem` | `heating_systems` | esistente | `HeatingSystemFactory` (nuovo) | `HeatingSystemSeeder` (nuovo) |
| `MaintenanceControl` | `maintenance_controls` | esistente | `MaintenanceControlFactory` (nuovo) | `MaintenanceControlSeeder` (nuovo) |
| `Subcontractor` | `subcontractors` | esistente | `SubcontractorFactory` (nuovo) | `SubcontractorSeeder` (nuovo) |
| `WorkOrderChangeOrder` | `work_order_change_orders` | esistente | `WorkOrderChangeOrderFactory` (nuovo) | `WorkOrderChangeOrderSeeder` (nuovo) |
| `WorkOrderReadinessItem` | `work_order_readiness_items` | esistente | `WorkOrderReadinessItemFactory` (nuovo) | `WorkOrderReadinessItemSeeder` (nuovo) |

## Skip motivati

- **Migration**: nessuna creata. Tutte e 9 le tabelle hanno gia' la migration
  (`database/migrations/2026_07_24_0003xx_*`) con `updateTimestamps()` gestito da
  `XotBaseMigration`. Regola forward-only rispettata: non si creano migration
  quando la tabella esiste gia'.
- **Modelli scartati**: nessuno. Non ci sono classi astratte, trait, contracts,
  enums o modelli STI/pivot in `app/Models/`.

## Note di implementazione

- Factory namespace `Modules\Compliance\Database\Factories`, risolte
  automaticamente da `HasXotFactory` / `GetFactoryAction` (mappatura
  `Modules\<Mod>\Models\X` -> `Modules\<Mod>\Database\Factories\XFactory`).
- `definition()` derivata da `$fillable` (`$guarded = []`) e dai `casts()` di ogni
  modello, seguendo il pattern dei moduli `WorkOrder` / `Quotation`.
- Le FK verso altri moduli sono lasciate `null` di default dove opzionali
  (es. `ComplianceDocument`), mentre le FK obbligatorie usano `::factory()`
  (`WorkOrder`, `Customer`, `HeatingSystem`) per generare la catena minima.
- Seeder per-model idempotenti (early-return se la tabella ha gia' record),
  come `WorkOrderSeeder` / `WorkOrderStatusSeeder`. Restano i 2 seeder demo
  preesistenti (`ComplianceDemoSeeder`, `HeatingDemoSeeder`) per dati realistici.
- Validazione: `php -l` OK su tutti i 18 file. Non eseguiti composer/phpstan/pest
  (fuori scope task), `phpstan.neon` non toccato.
