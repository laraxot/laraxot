---
title: "Platform — copertura model / migration / seeder / factory"
type: reference
tags: [platform, models, factory, migration, seeder, coverage]
created: 2026-07-24
updated: 2026-07-24
qmd: "Platform models migration seeder factory coverage audit"
---

# Platform — copertura Model → Migration / Seeder / Factory

Obiettivo: ogni modello concreto deve avere migration + seeder + factory.

## Modelli concreti (`app/Models/*.php`)

| Modello | Tipo | Tabella | Migration | Seeder | Factory |
|---|---|---|---|---|---|
| `AuditLog` | XotBaseModel (final) | `audit_logs` | `2026_03_03_000001_create_audit_logs_table` | via seeder demo/synthetic | **`AuditLogFactory` (creata)** |
| `PlatformSetting` | XotBaseModel (final) | `platform_settings` | `2026_02_25_000003_create_platform_settings_table` | `OperationalLookupsSeeder` + bootstrap seeders | **`PlatformSettingFactory` (creata)** |

Entrambi i modelli espongono la factory tramite il trait `HasXotFactory` ereditato da
`XotBaseModel` (risoluzione via `Modules\Xot\Actions\Factory\GetFactoryAction` sul
namespace convenzionale `Modules\Platform\Database\Factories\<Model>Factory`). Nessuna
modifica ai modelli è quindi necessaria: identico pattern dei modelli di `WorkOrder`.

## File creati

- `database/factories/AuditLogFactory.php` — `definition()` da `$guarded = []` (tutti i
  campi): `action`, `subject_type`, `subject_id`, `actor_user_id` (uuid), `panel_id`,
  `route`, `ip`, `user_agent`, `before`/`after` (json), `created_at` (`timestamps = false`).
- `database/factories/PlatformSettingFactory.php` — `definition()` da `$guarded = []`:
  `key` (unique), `value_json` (array cast), `updated_by_user_id` (uuid).

## Skip motivati

Nessun modello saltato: entrambi i modelli in `app/Models/` sono concreti, non-pivot,
non-STI e su tabella propria dedicata.

## Note migration

Le 2 migration (`create_platform_settings_table`, `create_audit_logs_table`) sono
**create pure** (`XotBaseMigration::tableCreate`). `actor_user_id` / `updated_by_user_id`
sono `uuid` indicizzati senza FK: i modelli Platform vivono sulla connessione `xot`,
mentre `User` è su connessione separata (FK cross-connection non applicabile).
