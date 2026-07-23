---
title: "Temi vs moduli — fase replica singolare"
type: concept
tags: [themes, gestionale-commesse, folio, replica, singular]
created: 2026-07-23
updated: 2026-07-23
qmd: "Themes replica singular modules AiAssistant Folio panels"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-panels-vs-themes.md"
  - "./README.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
---

# Temi vs moduli (fase replica)

> I moduli SRC si **replicano** in `laravel/Modules/{Singolare}`. I Themes **non** ricevono la BL di dominio.

## Ruoli

| Layer | Path | Fase replica |
|---|---|---|
| Dominio BO | `laravel/Modules/*` | `Customer`, `WorkOrder`, `AiAssistant`, `Auth`, … (singolare) |
| FO / skin | `laravel/Themes/*` | Folio, layout, Design Comuni |
| Piattaforma | `User`, `AI`, `Media`, … | Coesistono; niente merge forzato ora |

## Regole temi

- Nessuna copia di controllers/views Auth SRC nel tema come sostituto di `Modules/Auth`
- Chat/proposal AiAssistant = Filament/pages nel **modulo**, non nel Theme
- Panel Operator/Customer SRC → shell Theme + widget dai moduli replica

Vedi [gestionale-panels-vs-themes.md](./gestionale-panels-vs-themes.md) · [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).
