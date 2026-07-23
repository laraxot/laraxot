---
title: "AiAssistant — replica gestionale (NON CANCELLARE)"
type: overview
tags: [aiassistant, replica, do-not-delete, gestionale-commesse]
created: 2026-07-23
updated: 2026-07-23
qmd: "AiAssistant replica DO NOT DELETE restored thrice"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "./visibility.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md"
  - "../../../../docs/wiki/rules/replica-singular-no-merge-delete.md"
---

# AiAssistant

> **NON CANCELLARE.** Replica SRC. Coesiste con `Modules/AI`.  
> Cancellato **3 volte** il 2026-07-23 da agenti (regola merge errata). Ripristinato da `gestionale_commesse`.  
> Legge: [replica-singular-no-merge-delete.md](../../../../docs/wiki/rules/replica-singular-no-merge-delete.md).

| Voce | Valore |
|---|---|
| Path | `laravel/Modules/AiAssistant/` |
| Status | `modules_statuses.AiAssistant=false` |
| Fase | replica + reshape (albero SRC ancora DDD dopo restore) |

Dettaglio: [import-status.md](./import-status.md) · [visibility.md](./visibility.md).
