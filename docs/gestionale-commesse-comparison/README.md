---
title: "Gestionale commesse — confronto e conversione"
type: index
tags: [gestionale-commesse, index, replica]
created: 2026-07-23
updated: 2026-07-23
qmd: "gestionale_commesse comparison index replica singular phase"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./agent-errors-lessons.md"
  - "./session-lessons-learned.md"
  - "./module-mapping.md"
  - "./copy-then-normalize.md"
  - "./laraxot-compliance-audit.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
---

# Gestionale commesse — confronto e conversione

> `/var/www/_bases/gestionale_commesse` → questa base Laraxot.

## Fase attuale (leggere prima)

1. **[replica-singular-phase.md](./replica-singular-phase.md)** — **replica SRC + nome singolare** (niente merge User/AI ora)
2. **[agent-errors-lessons.md](./agent-errors-lessons.md)** — errori agente da non ripetere (E1–E7)
3. **[module-mapping.md](./module-mapping.md)** — tabella 23 → cartella singolare
4. **[module-name-singular-mandatory.md](../wiki/rules/module-name-singular-mandatory.md)** · [replica-singular-no-merge-delete.md](../wiki/rules/replica-singular-no-merge-delete.md)
5. **[copy-then-normalize.md](./copy-then-normalize.md)** — playbook copia + `app/`
6. **[laraxot-compliance-audit.md](./laraxot-compliance-audit.md)** — audit tree

> Analisi Auth↔User / AiAssistant↔AI restano utili come **gap futuri**, non come licenza a non replicare.

## Dettaglio

| Doc | Contenuto |
|---|---|
| [replica-singular-phase.md](./replica-singular-phase.md) | Religione fase A–B |
| [agent-errors-lessons.md](./agent-errors-lessons.md) | Libro errori (non rifare) |
| [session-lessons-learned.md](./session-lessons-learned.md) | Lesson parallele sessione |
| [module-mapping.md](./module-mapping.md) | SRC → replica singolare |
| [inventory-src.md](./inventory-src.md) | Modelli / grafo |
| [glossary-align.md](./glossary-align.md) | Lingua ubiqua |
| [copy-reshape-playbook.md](./copy-reshape-playbook.md) | Checklist reshape |
| [composer-json-import.md](./composer-json-import.md) | composer import |
| [analysis.md](./analysis.md) | Gap futuri (User/Employee/…) |
| [roadmap.md](./roadmap.md) | Wave (allineamento piattaforma = dopo) |

## Tracciamento

- Issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#9](https://github.com/laraxot/base_workorder_fila5/issues/9) (guardrail errori)
- Discussion [#8](https://github.com/laraxot/base_workorder_fila5/discussions/8)
- Story [1.10](../wiki/skills/bmad-create-story/stories/1.10.agent-errors-replica-singular-guardrails.story.md)
