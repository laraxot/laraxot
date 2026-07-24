---
title: "Import status — Intervention"
type: concept
tags: [import, intervention, gestionale-commesse, phpstan]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Intervention phpstan L10"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
---

# Import status — Intervention

- [x] Rename SRC `Interventions` → `Intervention`
- [x] Layout `app/` + XotBase SP
- [x] `@property` su model (report WorkOrder)
- [x] Seeders `*Factory::new()`
- [x] **PHPStan L10** — 0 errori reali (solo notice `@mixin` unmatched)
- [ ] Enable statuses post Pest smoke
