---
title: "AiAssistant vs Themes — confini FO"
type: concept
tags: [aiassistant, themes, replica, folio, filament, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "AiAssistant Themes boundaries replica FO Filament enabled matrix"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./module-vs-theme-boundaries.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "./gestionale-panels-vs-themes.md"
  - "./gc-modules-runtime-matrix.md"
  - "./gestionale-docs-index.md"
  - "../../Modules/AiAssistant/docs/visibility.md"
  - "../../Modules/AiAssistant/docs/README.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
---

# AiAssistant vs Themes

## Religione fase replica

`AiAssistant` è un **modulo replica** (BL chat/proposal/Neuron). I Themes vestono il FO pubblico — **non** assorbono né cancellano il modulo.

| Layer | Dove | Stato |
|---|---|---|
| Replica assistant | `Modules/AiAssistant` | `modules_statuses=true` · panel `/aiassistant/admin` |
| Piattaforma AI generica | `Modules/AI` | coesiste — **non** merge |
| Vestito FO | `Themes/*` | nessuna BL chat |

Visibilità UI: [visibility.md](../../Modules/AiAssistant/docs/visibility.md).  
Enable: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md).  
Fase: [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).  
Story [2.4](../../../docs/stories/2.4.module-theme-docs-hygiene.story.md).
