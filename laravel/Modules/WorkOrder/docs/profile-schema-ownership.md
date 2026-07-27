---
title: profile schema ownership
type: concept
module: WorkOrder
tags: [profile, migration, uuid, main-module]
updated: 2026-07-27
related:
  - ../../User/docs/fix-profile-id-uuid.md
  - ../../User/docs/profiles-id-uuid-policy.md
  - ../../Xot/docs/database/convert-id-uuid-to-bigint.md
---

# Profile — owner schema (WorkOrder)

## Perché

`config/local/workorder/xra.php` imposta `main_module` = `WorkOrder`.  
`XotData::getProfileClass()` risolve `Modules\WorkOrder\Models\Profile`.

La tabella `profiles` vive sulla connessione `user` (`workorder_user`), ma la **migrazione canonica** è in questo modulo.

## Contratto id + uuid

| Colonna | Tipo | Ruolo |
|---------|------|-------|
| `id` | bigint unsigned AUTO_INCREMENT | PK interna, join |
| `uuid` | char(36) indexed | API, Android, riferimenti esterni |

`BaseProfile::booted()` genera solo `uuid`; `id` è autoincrement.

## Migrazione canonica

`database/migrations/2026_07_27_111500_create_profiles_table.php`

- `tableCreate` — installazioni nuove
- `convertIdFromUuidToBigintIfNeeded` — legacy con `id` UUID char(36)
- `tableUpdate` — colonne additive idempotenti + `updateTimestamps`

## Parità artefatti

| Artefatto | Path |
|-----------|------|
| Model | `app/Models/Profile.php` |
| Migration | `database/migrations/2026_07_27_111500_create_profiles_table.php` |
| Factory | `database/factories/ProfileFactory.php` |
| Seeder | `database/seeders/ProfileSeeder.php` |

## Incident 2026-07-27

**Errore:** `Field 'id' doesn't have a default value` su insert `profiles`.

**Causa:** schema legacy con `id` char(36) PK senza default; modello non valorizza `id`.

**Fix:** migrazione WorkOrder + conversione UUID→bigint.

## User module

Le migrazioni `create_profiles_table` duplicate in User sono in `User/database/migrations/_bak/*.merged`.  
User mantiene `BaseProfile`, `User\Models\Profile` (Filament), factory/seeder User — non owner schema per questo progetto.
