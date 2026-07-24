---
title: "Import status — Email (replica)"
type: concept
tags: [email, replica, import, wave3, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Email import status wave3 replica enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Email

> Replica Wave 3. **Non** merge in Notify/User. **Non** cancellare.

- [x] Layout `app/` + XotBase SP / Route / Resources principali
- [x] QueueableAction sulle Action con `execute()`
- [x] `modules_statuses=true` (2026-07-24)
- [ ] EventServiceProvider → XotBaseEventServiceProvider (se ancora Illuminate)
- [ ] BaseModel / factories / Pest
- [ ] PHPStan L10 rieseguire post-enable (baseline storica reshape non più SSoT)

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
