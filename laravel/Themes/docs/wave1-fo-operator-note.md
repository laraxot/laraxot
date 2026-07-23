---
title: "Wave 1 — nota FO operator (Themes)"
type: concept
tags: [themes, wave1, operator, folio, filament]
created: 2026-07-23
updated: 2026-07-23
qmd: "wave1 operator FO Themes Folio Interventions timbratura"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-panels-vs-themes.md"
  - "./module-vs-theme-boundaries.md"
  - "./README.md"
  - "../../../docs/gestionale-commesse-comparison/wave1-reshape.md"
---

# Wave 1 — nota FO operator (Themes)

> Durante il reshape XotBase di Interventions/WorkOrders **non** migrare i flussi operator su Folio.

## Decisione Wave 1

| Flusso SRC | Dove resta in Wave 1 |
|---|---|
| Timbratura / convalida ore | Filament panel (Interventions) |
| Firma / sketch | Filament pages (Signatures + Interventions) |
| Login | User Folio (già Laraxot) — non Auth SRC |

Themes (Sixteen/Zero) = skin FO pubblico. Operator field-service = panel Filament finché story FO dedicata.
