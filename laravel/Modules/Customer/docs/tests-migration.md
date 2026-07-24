---
title: "Tests migration — Customer"
type: concept
tags: [tests, pest, customer, gestionale-commesse]
created: 2026-07-24
updated: 2026-07-24
qmd: "Customer tests migration SRC Feature consolidated CustomerActionsTest"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
---

# Tests migration — Customer

## Perché

I test SRC in `gestionale_commesse/tests/Feature/Customers/` proteggono Actions CRUD cliente/sede/contatto.

## Stato

| Voce | Valore |
|---|---|
| SRC | 12 Feature |
| DEST | Consolati in `tests/Feature/CustomerActionsTest.php` (+ Search/CompanyLookup/Resource) — **lock Feature** da altro agente |
| Bootstrap | `TestCase` + `prepareSharedFixcitySqliteForTesting()` + `DatabaseTransactions` |
| Pest | `CustomerActionsTest` **11 passed** (evidenza 2026-07-24) |
| Playbook | [migrate-gestionale-tests.md](../../../../laravel/docs/migrate-gestionale-tests.md) |

## Note

Non reintrodurre file singoli Create/Update senza coordinare il lock `tests/Feature.lock`.
