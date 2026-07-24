---
title: "Import status — Billing"
type: concept
tags: [import, gestionale-commesse, billing, phpstan]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Billing normalize phpstan enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Billing

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Billing\`
- [x] `BillingServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] QueueableAction
- [x] Migrazioni 1-per-modello
- [x] Resources → XotBaseResource (`getFormSchema`)
- [x] **PHPStan L10** — 0 errori reali (2026-07-24)
- [x] `modules_statuses=true`
- [ ] Pages/RelationManagers → XotBase* (wave UX; oggi alcuni `extends` Filament diretto)
- [ ] DomPDF vendor: `composer require barryvdh/laravel-dompdf` (umano)

> Soft-dep PDF: non blocca PHPStan se tipizzato/soft; install solo con ordine.
