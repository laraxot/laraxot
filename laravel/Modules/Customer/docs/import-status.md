---
title: "Import status — Customer"
type: concept
tags: [import, customer, gestionale-commesse, phpstan]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Customer phpstan L10"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
---

# Import status — Customer

- [x] Rename SRC `Customers` → `Customer`
- [x] Layout `app/` + Models (6)
- [x] Namespace `Modules\Customer\`
- [x] XotBase ServiceProvider + Route/Event
- [x] composer PSR-4 → `app/`
- [x] Seeders via `*Factory::new()` (PHPStan)
- [x] Tests: TestCase + PHPUnit Assert (no Pest `expect` PHPStan)
- [x] **PHPStan L10** — 0 errori reali (solo notice ignore `@mixin` unmatched su analisi modulo)
- [ ] Enable `modules_statuses=true` dopo smoke Pest
