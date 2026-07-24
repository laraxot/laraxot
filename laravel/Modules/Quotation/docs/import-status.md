---
title: "Import status — Quotation"
type: concept
tags: [import, quotation, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Quotation migrate panel site survey"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Quotation

**Perché esiste:** preventivi + template + sopralluoghi (pre-vendita → offerta).

- [x] Reshape `app/` + XotBase Resource
- [x] Timestamp migrazioni bump dopo Customer/WorkOrder (`2026_07_23_18100x`)
- [x] `product_id` senza `constrained('products')` (ordine Catalog indipendente)
- [x] `performed_by_user_id` = `string(36)` (users UUID)
- [x] Enabled + panel `quotation/admin`
- [ ] Pest smoke

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).
