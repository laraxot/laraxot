---
title: Quality roadmap — Billing
type: concept
tags: [billing, quality, perfection, pest, phpstan, filament]
created: 2026-08-31
updated: 2026-08-31
qmd: billing quality roadmap perfection pest xotbase filament
related:
  - ./import-status.md
  - ../../../docs/debt-billing-pages-xotbase.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Billing

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest unit | **OK** (cash flow, margini, riconciliazione fornitori) |
| Pest feature admin | **Fail** — `roles` assente (D17) |
| Filament Pages | **Debito D11** — alcuni `extends` diretto Filament |

---

## Azioni ordinate

1. **D17** — `roles` + permission Spatie su `workorder_data_test`.
2. **D11** — migrare Pages/RelationManagers a `XotBase*` ([debt-billing-pages-xotbase.md](../../../docs/debt-billing-pages-xotbase.md)).
3. **D7** — DomPDF solo se export fattura richiesto in produzione.
4. Policy hardening su permessi mancanti (registrato in import-status).

[import-status.md](./import-status.md)
