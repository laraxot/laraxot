---
title: "Tests migration — WorkOrder"
type: concept
tags: [tests, pest, workorder, gestionale-commesse]
created: 2026-07-24
updated: 2026-07-24
qmd: "WorkOrder tests migration SRC Feature CreateWorkOrder sqlite schema"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
---

# Tests migration — WorkOrder

## Perché

SRC `tests/Feature/WorkOrders/` (~14) copre create/delete/status/assign/effort/PDF.

## Stato

| Voce | Valore |
|---|---|
| DEST | File Feature migrati (namespace `Modules\WorkOrder\…`) |
| Lock | `tests/Feature/.lock` — non editare Feature senza unlock |
| TestCase | `prepareSharedFixcitySqliteForTesting()` + connections `sqlite`,`xot` |
| Schema test | Path-migrate wave su connection `sqlite` (file condiviso) richiesto prima dei Feature |
| Smoke | `CreateWorkOrderTest` **passed** dopo migrate sqlite |
| Playbook | [migrate-gestionale-tests.md](../../../../laravel/docs/migrate-gestionale-tests.md) |

## Aperti

- PDF tests → soft-dep DomPDF (D7) — aspettarsi skip/fail finché require
- UI Filament panel alias `workorder` vs SRC `admin`
