---
title: "Tests migration — Catalog"
type: concept
tags: [tests, pest, catalog, metel, gestionale-commesse]
created: 2026-07-24
updated: 2026-07-24
qmd: "Catalog tests migration MetelCatalogTest CatalogCrudTest fixcity metel config"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./import-status.md"
  - "./gestionale-commesse-port.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
---

# Tests migration — Catalog

## Perché

SRC `gestionale_commesse/tests/Feature/Catalog/` protegge CRUD lookup/product e pipeline Metel (CSV/fixed/API).

## Stato

| Voce | Valore |
|---|---|
| SRC | `CatalogCrudTest`, `MetelCatalogTest` |
| DEST | stessi file in `tests/Feature/` |
| Bootstrap | `prepareSharedFixcitySqliteForTesting()` + ensure schema **prima** di `DatabaseTransactions` |
| Config | `mergeConfigFrom(config/metel.php, 'metel')` + `fixed_layout`/`csv_map` da SRC |
| Pest | **11 passed** (2026-07-24) |
| Playbook | [migrate-gestionale-tests.md](../../../../laravel/docs/migrate-gestionale-tests.md) |

## Comando

```bash
cd laravel && ./vendor/bin/pest Modules/Catalog/tests --no-coverage
```
