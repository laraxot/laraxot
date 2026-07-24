---
title: "Import status — WorkOrder"
type: concept
tags: [import, workorder, gestionale-commesse, phpstan, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status WorkOrder phpstan panel migrate"
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

# Import status — WorkOrder

**Perché esiste:** commessa operativa (stato, attività, assignment) — cuore del gestionale.

- [x] Rename SRC `WorkOrders` → `WorkOrder`
- [x] Layout `app/` + XotBase SP + Resources
- [x] **PHPStan L10** — 0 errori reali
- [x] `modules_statuses=true`
- [x] Migrazioni path applicate (`work_orders` …); `primary_contact_id` **senza** FK DB (compat schema contacts)
- [x] `AdminPanelProvider` + rotte `workorder/admin/work-orders`
- [ ] Opzionale: `barryvdh/laravel-dompdf` / `phpoffice/phpword` in root
- [ ] Pest smoke CRUD

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).
