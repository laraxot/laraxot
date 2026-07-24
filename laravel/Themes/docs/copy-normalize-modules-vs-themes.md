---
title: "Temi vs moduli — fase replica singolare"
type: concept
tags: [themes, gestionale-commesse, folio, replica, singular]
created: 2026-07-23
updated: 2026-07-24
qmd: "Themes replica singular modules AiAssistant Folio panels enabled statuses matrix"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-panels-vs-themes.md"
  - "./gc-modules-runtime-matrix.md"
  - "./gestionale-docs-index.md"
  - "./README.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
  - "../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md"
---

# Temi vs moduli (fase replica)

> I moduli SRC si **replicano** in `laravel/Modules/{Singolare}`. I Themes **non** ricevono la BL di dominio.  
> Enable reale: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md).

## Ruoli

| Layer | Path | Stato 2026-07-24 |
|---|---|---|
| Dominio BO | `laravel/Modules/*` | 23 repliche; quasi tutte **Enabled** (vedi matrice) |
| FO / skin | `laravel/Themes/*` | Folio/layout — **no** BL Auth/AiAssistant |
| Ancora `false` | Compliance, EnergyBroker, PublicProcurement, TimberBilling | Gate + ordine |
| Document | enabled JSON | **RISCHIO** tabella `media` |

## Regole temi

- Nessuna copia Auth SRC nel tema al posto di `Modules/Auth`
- AiAssistant = modulo Filament, non Theme
- Panel Operator → shell Theme + widget dai moduli

Vedi [gestionale-panels-vs-themes.md](./gestionale-panels-vs-themes.md) · Story [2.4](../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md).
