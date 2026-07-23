---
title: "AiAssistant — perché non compare / perché non cancellare"
type: troubleshooting
tags: [aiassistant, visibility, do-not-delete, replica]
created: 2026-07-23
updated: 2026-07-23
qmd: "AiAssistant visibility statuses false third deletion restore"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md"
---

# Visibilità AiAssistant

| Check | Valore |
|---|---|
| Cartella | deve esistere `laravel/Modules/AiAssistant/` |
| Status | `AiAssistant: false` in `modules_statuses.json` (chiave **obbligatoria**) |
| UI Filament | assente finché `false` — **non** significa «eliminato» |

## Incidenti 2026-07-23

Cancellato **tre volte** da agenti (merge/twin). Libro: [agent-errors-lessons.md](../../../../docs/gestionale-commesse-comparison/agent-errors-lessons.md) (E1, E8).

Restore: rsync da `/var/www/_bases/gestionale_commesse/Modules/AiAssistant`.
