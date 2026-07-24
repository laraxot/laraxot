---
title: "Import status — WorkOrder"
type: concept
tags: [import, workorder, gestionale-commesse, phpstan]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status WorkOrder phpstan L10 DocumentExportBridge"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
---

# Import status — WorkOrder

- [x] Rename SRC `WorkOrders` → `WorkOrder`
- [x] Layout `app/` + XotBase SP
- [x] RelationManagers: `form()` → `getFormSchema()` (XotBase final)
- [x] Seeders `*Factory::new()`
- [x] PDF via `DocumentExportBridge` (soft-dep `barryvdh/laravel-dompdf`)
- [x] DOCX: throw finché `composer require phpoffice/phpword`
- [x] **PHPStan L10** — 0 errori reali (solo notice `@mixin` unmatched)
- [ ] Enable statuses post Pest smoke
- [ ] Opzionale: `composer require barryvdh/laravel-dompdf phpoffice/phpword` in root
