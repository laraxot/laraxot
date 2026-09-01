---
title: Quality roadmap — Customer
type: concept
tags: [customer, quality, perfection, pest, phpstan]
created: 2026-08-31
updated: 2026-08-31
qmd: customer quality roadmap perfection pest phpstan
related:
  - ./import-status.md
  - ./tests-migration.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
  - ../../../docs/gestionale-technical-debt.md
---

# Quality roadmap — Customer

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Boot / panel | **OK** |
| Pest dominio | **OK** (33+ test; suite ampia verde) |
| Pest cross-modulo | **Aperto** — `CompanyLookupProductionFlowTest` |
| Docs | **OK** (`import-status`, `tests-migration`) |

Modulo **quasi perfetto** sul perimetro anagrafica; il gap è solo ambiente test condiviso.

---

## Azioni ordinate

1. **D17** — `roles` + `products` su DB test → sblocca `CompanyLookupProductionFlowTest` (con Quotation/AiAssistant).
2. Mantenere `GatedXotBaseResource` su Customer/Reseller (già fatto).
3. Nessun `RefreshDatabase` nei test (pattern `prepareSharedFixcitySqliteForTesting`).

Dettaglio porting: [import-status.md](./import-status.md).
