---
title: "HR — Model persistence coverage (migration / seeder / factory)"
type: status
tags: [hr, seeders, factories, migrations, coverage]
created: 2026-07-24
updated: 2026-07-24
qmd: "HR model migration seeder factory coverage status"
---

# HR — Model persistence coverage

Ogni modello concreto (`app/Models/*.php`, esclusi astratti/trait/contract/enum)
deve avere migration + seeder + factory. Stato aggiornato al 2026-07-24.

## Copertura

| Modello                | Tabella                  | Migration | Factory | Seeder |
|------------------------|--------------------------|-----------|---------|--------|
| Employee               | employees                | OK        | OK      | OK (nuovo) |
| EmployeeMedicalVisit   | employee_medical_visits  | OK        | OK      | OK (nuovo) |
| EmployeeTraining       | employee_trainings       | OK        | OK      | OK (nuovo) |
| EmployeeDocument       | employee_documents       | OK        | OK      | OK (nuovo) |
| AbsenceRequest         | absence_requests         | OK        | OK      | OK (nuovo) |

Tutti i 5 modelli concreti sono ora completi.

## Seeder creati

- `EmployeeSeeder` — 5 record via `EmployeeFactory`
- `EmployeeMedicalVisitSeeder` — 3 record via `EmployeeMedicalVisitFactory`
- `EmployeeTrainingSeeder` — 3 record via `EmployeeTrainingFactory`
- `EmployeeDocumentSeeder` — 3 record via `EmployeeDocumentFactory`
- `AbsenceRequestSeeder` — 3 record via `AbsenceRequestFactory`
- `HRDatabaseSeeder` — orchestratore (chiama i 5 seeder in ordine di dipendenza)

Namespace: `Modules\HR\Database\Seeders`. Ogni seeder e idempotente
(early-return se la tabella contiene gia record) seguendo il pattern
di `Modules/WorkOrder/database/seeders`.

## Skip motivati

Nessuno skip: tutti i modelli HR sono entita concrete con tabella propria.
Le factory dipendenti (Employee/User/Media) risolvono automaticamente
i parent tramite factory, quindi i seeder non richiedono ordinamento esterno.
