---
title: "Import status — Platform (replica)"
type: concept
tags: [platform, replica, import, wave3, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Platform import status wave3 replica enabled"
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

# Import status — Platform

> Replica Wave 3 (settings/company). **Non** merge in Tenant. **Non** cancellare.

- [x] Layout `app/` + XotBase SP / Route / Resources principali
- [x] QueueableAction
- [x] `modules_statuses=true` (2026-07-24)
- [ ] EventServiceProvider → XotBaseEventServiceProvider (se ancora Illuminate)
- [ ] BaseModel / factories / Pest
- [ ] PHPStan L10 rieseguire post-enable

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
