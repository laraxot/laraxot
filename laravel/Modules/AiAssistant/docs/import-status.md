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

## Checklist

- [x] Layout sotto `app/`
- [x] `composer.json` PSR-4 → `app/`
- [x] Provider XotBase + Models BaseModel (+ Route/Event)
- [x] QueueableAction
- [x] Factories/seeders/migrations parity (4/4)
- [x] PHPStan L10 `Modules/AiAssistant/app` = 0
- [ ] Enable solo post-gate + ordine utente
- [ ] Pest smoke chat/operator

## Vietato

- `rm -rf` / delete perché «esiste AI»
- Rimuovere chiave `AiAssistant` da `modules_statuses.json`
