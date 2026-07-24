---
title: "AiAssistant — knowledge base (NON CANCELLARE)"
type: overview
tags: [aiassistant, replica, do-not-delete, gestionale-commesse, docs]
created: 2026-07-23
updated: 2026-07-24
qmd: "AiAssistant docs README purpose neuron proposal do not delete"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/10"
related:
  - "./import-status.md"
  - "./visibility.md"
  - "./laraxot-reshape-fix.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# AiAssistant

> **NON CANCELLARE / NON MERGE in `Modules/AI`.** Cancellato 3× da agenti — religione: [replica-singular-no-merge-delete.md](../../../../docs/wiki/rules/replica-singular-no-merge-delete.md).

## Perché esiste

Assistente AI **field-service** (thread, proposal tool, STT/TTS, monitor): BL Neuron/proposal del SRC gestionale. Coesiste col modulo piattaforma `AI`.

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | `ai_*` (user cols `string(36)`) |
| Panel | `aiassistant/admin` + API `api/aiassistant/*` |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [visibility.md](./visibility.md) | Perché resta visibile |
| [laraxot-reshape-fix.md](./laraxot-reshape-fix.md) | Fix reshape |
| [gestionale-commesse-port.md](./gestionale-commesse-port.md) | Port notes |

## Hub / BMAD

- [agent-errors-lessons.md](../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md) (E1)
- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- Themes: [aiassistant-vs-themes.md](../../../../laravel/Themes/docs/aiassistant-vs-themes.md)
- [#9](https://github.com/laraxot/base_workorder_fila5/issues/9) · [#7](https://github.com/laraxot/base_workorder_fila5/issues/7)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
