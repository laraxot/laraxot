---
title: "Import status — Vehicle"
type: concept
tags: [import, vehicle, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Vehicle panel media spatiefk"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Vehicle

**Perché esiste:** parco mezzi + documenti veicolo.

- [x] Enabled + migrate `vehicles` / `vehicle_documents`
- [x] Panel `vehicle/admin/vehicles`
- [ ] Debito: `vehicle_documents.media_id` → Spatie `media` (Document disabled; tabella omonima diversa)
- [ ] Pest smoke

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).
