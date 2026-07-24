# Employee — Copertura Modello / Migration / Seeder / Factory

Stato aggiornato: 2026-07-24

Obiettivo: ogni modello **concreto** con **tabella propria** deve avere migration + seeder + factory.

## Modelli concreti (`app/Models/`)

| Modello | Tabella | Tipo tabella | Migration | Seeder | Factory |
|---|---|---|---|---|---|
| `AbsenceRequest` | `absence_requests` | propria (conn. `xot`) | esistente | **AbsenceRequestSeeder** (nuovo) | esistente |
| `Department` | `departments` | propria (conn. `xot`) | **nuova** | **DepartmentSeeder** (nuovo) | esistente |
| `Position` | `positions` | propria (conn. `xot`) | **nuova** | **PositionSeeder** (nuovo) | esistente |
| `TimeEntry` | `time_entries` | propria (conn. `xot`) | **nuova** | **TimeEntrySeeder** (nuovo) | riempita (era stub `[]`) |
| `TimeRecord` | `time_records` | propria (conn. `xot`) | **nuova** | **TimeRecordSeeder** (nuovo) | riempita (era stub `[]`) |
| `WorkHour` | `work_hours` | propria (conn. `xot`) | esistente | esistente (WorkHourSeeder) | esistente |
| `User` | `users` | **condivisa** (owner: modulo Auth) | — (skip) | — (skip) | stub `[]` |
| `Admin` | `users` | **condivisa** (`extends User`) | — (skip) | — (skip) | stub `[]` |
| `Employee` | `users` | **condivisa** (`extends User`, `$table='users'`) | — (skip) | — (skip) | esistente |

`BaseModel` è `abstract` → escluso.

## Skip motivati

- **`User`, `Admin`, `Employee`** mappano tutti la tabella `users`, definita in
  `Modules/Auth/database/migrations/0001_01_01_000000_create_users_table.php`.
  Non è tabella propria del modulo Employee: creare una migration qui la
  duplicherebbe e violerebbe l'ownership del modulo Auth → **nessuna migration**.
  Non vengono generati seeder: il popolamento della tabella `users` è
  responsabilità del modulo owner; inoltre `UserFactory`/`AdminFactory` del
  modulo Employee sono stub (`return []`) e non produrrebbero righe valide.
- **`BaseModel`** è astratto: non mappa alcuna tabella.

## Perché le migration erano 3

Le migration presenti (`work_hours` ×2 con guardia `tableExists`, `absence_requests`)
coprivano solo 2 tabelle proprie. Le tabelle `departments`, `positions`,
`time_entries`, `time_records` non esistevano in **nessun** modulo (verificato via
`grep table_name`), pur avendo modello + factory: da qui il gap colmato.

## Convenzioni applicate alle nuove migration

- Estendono `XotBaseMigration`, forward-only (solo `up()`), stile modulo.
- Colonne in `tableCreate(...)`; `updateTimestamps()` invocato in
  `tableUpdate(...)` con `hasSoftDeletes: true` (non dentro `tableCreate`).
- Nessuna FK cross-database verso `users` (conn. `user` vs `xot`): gli id
  (`user_id`, `employee_id`, `manager_id`, `approved_by`, `created_by`,
  `updated_by`) sono `unsignedBigInteger` indicizzati, relazioni a livello Eloquent.

## Nota factory riempite

`TimeEntryFactory` e `TimeRecordFactory` erano stub (`return []`) e non avrebbero
prodotto righe valide per i rispettivi seeder. Sono state completate per allinearsi
alle colonne delle nuove migration, così i seeder generano dati reali.

## File creati / modificati

Migration (nuove):
- `database/migrations/2026_07_24_000002_create_departments_table.php`
- `database/migrations/2026_07_24_000003_create_positions_table.php`
- `database/migrations/2026_07_24_000004_create_time_records_table.php`
- `database/migrations/2026_07_24_000005_create_time_entries_table.php`

Seeder (nuovi):
- `database/seeders/DepartmentSeeder.php`
- `database/seeders/PositionSeeder.php`
- `database/seeders/AbsenceRequestSeeder.php`
- `database/seeders/TimeRecordSeeder.php`
- `database/seeders/TimeEntrySeeder.php`

Modificati:
- `database/seeders/EmployeeDatabaseSeeder.php` (ora chiama tutti i seeder)
- `database/factories/TimeEntryFactory.php` (definizione reale)
- `database/factories/TimeRecordFactory.php` (definizione reale)
