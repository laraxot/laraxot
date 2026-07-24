---
title: "Import status — Customer"
type: concept
tags: [import, customer, gestionale-commesse, phpstan, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Customer phpstan L10 enabled migrate panel"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Customer

**Perché esiste:** anagrafica clienti / sedi / referenti / rivenditori — radice del grafo field-service.

- [x] Rename SRC `Customers` → `Customer`
- [x] Layout `app/` + Models + XotBase SP
- [x] Namespace `Modules\Customer\`
- [x] composer PSR-4 → `app/`
- [x] Seeders via `*Factory::new()`
- [x] **PHPStan L10** — 0 errori reali
- [x] `modules_statuses=true`
- [x] Migrazioni path applicate (`customers`, `contacts` bigint, …)
- [x] Panel Filament `customer/admin` (AdminPanelProvider in composer + module.json)

SSoT readiness: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).
