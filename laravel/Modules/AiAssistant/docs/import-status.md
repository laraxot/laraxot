---
title: "Import status — AiAssistant"
type: concept
tags: [import, aiassistant, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status AiAssistant panel migrate uuid users phpstan"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./laraxot-reshape-fix.md"
  - "./visibility.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — AiAssistant

**Perché esiste:** assistente AI field-service (thread/proposal/tool) — **replica**, non merge nel modulo `AI`.

- [x] Reshape + XotBaseModel + QueueableAction
- [x] Migrazioni `ai_*`; user cols = `string(36)` no FK
- [x] Enabled + panel `aiassistant/admin` (+ API routes)
- [x] **PHPStan L10** — 0 errori reali (Neuron stub `static` fluent + seeders Factory)
- [ ] Pest smoke chat/operator
- [ ] **Non cancellare** / non merge in AI

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md) · religione [replica-singular-phase.md](../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).
