---
title: "Tests migration — HR"
type: concept
tags: [tests, pest, hr, gestionale-commesse, d1, d6]
created: 2026-07-24
updated: 2026-07-24
qmd: "HR tests migration EmployeeCrud AbsenceRequest skip media roles"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./import-status.md"
  - "./gestionale-commesse-port.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
  - "../../../../laravel/docs/debt-document-media-conflict.md"
  - "../../../../laravel/docs/debt-pest-field-service-coverage.md"
---

# Tests migration — HR

## Perché

SRC `gestionale_commesse/tests/Feature/HR/` copre employee CRUD, assenza, allegati Document.

## Stato

| Voce | Valore |
|---|---|
| SRC | 5 Feature |
| DEST | 5 Feature in `Modules/HR/tests/Feature/` |
| Bootstrap | fixcity sqlite + stub `users` + migrate path HR **prima** di transactions |
| Pest | **3 passed**, **5 skipped** (2026-07-24) |
| Skip D1 | `AddEmployee*Document*` / attachments → tabella `media` Document |
| Skip D6 | `AbsenceRequestWorkflowTest` → User factory + Spatie `roles` |
| Fix BL | chiavi lang `hr::hr.absence_types.*` in `AbsenceRequestResource` |

## Comando

```bash
cd laravel && ./vendor/bin/pest Modules/HR/tests --no-coverage
```
