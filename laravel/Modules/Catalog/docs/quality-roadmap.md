---
title: Quality roadmap — Catalog
type: concept
tags: [catalog, quality, perfection, pest, phpstan]
created: 2026-08-31
updated: 2026-08-31
qmd: catalog quality roadmap perfection pest metel
related:
  - ./import-status.md
  - ./tests-migration.md
  - ./model-factory-seeder-status.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Catalog

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest | **OK** (11 test wave documentati) |
| Factory/seeder parity | Documentato in `model-factory-seeder-status.md` |

Modulo **maturo** per perimetro catalogo; dipendenze Quotation/Intervention richiedono D17 per test cross-modulo.

---

## Azioni ordinate

1. Mantenere `mergeConfigFrom` Metel allineato (HR/Catalog pattern).
2. Supportare D17 per test che leggono `products` da altri moduli.
3. Spot-check Resources Filament dopo enable nuovi pannelli.

[import-status.md](./import-status.md) · [tests-migration.md](./tests-migration.md)
