---
title: "Import status — AiAssistant (replica, DO NOT DELETE)"
type: concept
tags: [aiassistant, replica, import, do-not-delete]
created: 2026-07-23
updated: 2026-07-23
qmd: "AiAssistant import status replica restore do not delete"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./visibility.md"
  - "../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
---

# Import status — AiAssistant

> **Fase:** replica. **Non** merge in `AI`. **Non** cancellare.  
> 2026-07-23: cartella ripristinata da SRC dopo **3° delete** agente — albero di nuovo DDD (`Application/`/`Infrastructure/`); reshape `app/` da rifare.

## Checklist

- [ ] Layout sotto `app/` (dopo restore SRC è di nuovo DDD)
- [ ] `composer.json` Laraxot PSR-4 → `app/`
- [ ] Provider XotBase + Models BaseModel
- [ ] QueueableAction / XotBaseResource
- [ ] Factories/seeders/migrations parity
- [ ] Pest + PHPStan L10
- [ ] Enable solo post-gate + ordine utente

## Vietato

- `rm -rf` / delete perché «esiste AI»
- Rimuovere chiave `AiAssistant` da `modules_statuses.json`
