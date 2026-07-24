---
title: "Gestionale commesse — confronto e conversione"
type: index
tags: [gestionale-commesse, index, replica]
created: 2026-07-23
updated: 2026-07-24
qmd: "gestionale_commesse comparison index replica singular phase functional readiness"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./functional-readiness.md"
  - "./replica-singular-phase.md"
  - "./agent-errors-lessons.md"
  - "./session-lessons-learned.md"
  - "./module-mapping.md"
  - "./copy-then-normalize.md"
  - "./laraxot-compliance-audit.md"
  - "./reshape-completion-report.md"
  - "../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
  - "../sprint-status.yaml"
---

# Gestionale commesse — confronto e conversione

> `/var/www/_bases/gestionale_commesse` → questa base Laraxot.

## Fase attuale (leggere prima)

1. **[functional-readiness.md](./functional-readiness.md)** — **gate runtime** (migrate path + panel Filament)
2. **[replica-singular-phase.md](./replica-singular-phase.md)** — replica SRC + nome singolare (niente merge User/AI)
3. **[agent-errors-lessons.md](./agent-errors-lessons.md)** — errori agente da non ripetere
4. **[module-mapping.md](./module-mapping.md)** — SRC → cartella singolare
5. **[copy-then-normalize.md](./copy-then-normalize.md)** — playbook copia + `app/`
6. **[laraxot-compliance-audit.md](./laraxot-compliance-audit.md)** — audit tree
7. **[2.3 deep quality](../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md)** — PHPStan/enable/docs ([#14](https://github.com/laraxot/base_workorder_fila5/issues/14) · [#15](https://github.com/laraxot/base_workorder_fila5/discussions/15))

> Auth↔User / AiAssistant↔AI = gap futuri, **non** licenza a cancellare repliche.

## Dettaglio

| Doc | Contenuto |
|---|---|
| [functional-readiness.md](./functional-readiness.md) | DB + panel: cosa funziona ora |
| [replica-singular-phase.md](./replica-singular-phase.md) | Religione fase A–B |
| [agent-errors-lessons.md](./agent-errors-lessons.md) | Libro errori |
| [reshape-completion-report.md](./reshape-completion-report.md) | Completamento reshape |
| [module-mapping.md](./module-mapping.md) | SRC → replica |
| Themes [gestionale-docs-index.md](../../laravel/Themes/docs/gestionale-docs-index.md) | Indice docs temi vs moduli |
| Story [2.4](../wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md) | Hygiene docs moduli+temi |
| [roadmap.md](./roadmap.md) | Wave |

## Tracciamento

- Issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#9](https://github.com/laraxot/base_workorder_fila5/issues/9)
- Discussion [#8](https://github.com/laraxot/base_workorder_fila5/discussions/8)
- Sprint [sprint-status.yaml](../sprint-status.yaml)
