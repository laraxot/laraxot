---
title: "Confini Themes vs Modules (fase replica)"
type: concept
tags: [themes, modules, boundaries, replica, singular]
created: 2026-07-23
updated: 2026-07-24
qmd: "Themes Modules boundaries Folio replica singular gestionale panels"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-panels-vs-themes.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "./gc-modules-runtime-matrix.md"
  - "./aiassistant-vs-themes.md"
  - "./wave1-fo-operator-note.md"
  - "./README.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../docs/wiki/rules/module-name-singular-mandatory.md"
---

# Confini Themes vs Modules

> Fase attuale = **replica moduli SRC** con nome **singolare**. Themes ≠ luogo della BL.  
> Enable: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md).

| Layer | Dove | Cosa |
|---|---|---|
| Modules (replica) | `laravel/Modules/{Singolare}` | Models, Actions, Filament BO (`*/admin`) |
| Themes | `laravel/Themes/*` | Folio/Volt FO, CSS/JS, brand |
| App | `laravel/` | merge-plugin **solo** `Modules/*/composer.json` |

## Regole

1. Nessun merge di `Themes/*/composer.json` nel root.
2. FO pubblico → Folio nel tema; BO operator → Filament nei moduli replica ([wave1-fo-operator-note.md](./wave1-fo-operator-note.md)).
3. Naming moduli: [module-name-singular-mandatory.md](../../../docs/wiki/rules/module-name-singular-mandatory.md).
4. **Non** cancellare repliche (`AiAssistant`, `Auth`, `HR`, …) «perché esiste User/AI».
5. AiAssistant: [aiassistant-vs-themes.md](./aiassistant-vs-themes.md).
6. Panels: [gestionale-panels-vs-themes.md](./gestionale-panels-vs-themes.md).
7. Status enable: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md) · [tenant-modules-navigation-discipline.md](./tenant-modules-navigation-discipline.md).
7. Runtime gate: [functional-readiness.md](../../../docs/gestionale-commesse-comparison/functional-readiness.md).
8. SSoT fase: [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).
