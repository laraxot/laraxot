---
title: "Script e Automazione"
type: concept
tags: [script, automazione]
created: 2026-07-14
updated: 2026-07-14
qmd: "script-automazione script e automazione"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Script e Automazione

La cartella `bashscripts/` contiene script organizzati per categoria:

- `database/`: Script per gestione database
- `maintenance/`: Script di manutenzione
- `deployment/`: Script di deployment
- `testing/`: Script per testing
- `phpstan/`: Script per analisi statica

## Approfondimenti
- Come utilizzare gli script: [Configurazione e Setup](./configurazione.md)
- Standard per la creazione di nuovi script: [Filosofia di Sviluppo](./filosofia-sviluppo.md)

## Chaos readiness

- Script operativo: `bashscripts/ai/chaos-readiness-check.sh`
- Comando documentato: [chaos-readiness](../commands/chaos-readiness.md)
