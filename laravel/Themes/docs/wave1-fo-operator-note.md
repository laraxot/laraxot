---
title: "Wave 1 — nota FO operator (Themes)"
type: concept
tags: [themes, wave1, operator, folio, filament, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "wave1 operator FO Themes Folio Interventions timbratura panels ready document media"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-panels-vs-themes.md"
  - "./module-vs-theme-boundaries.md"
  - "./gc-modules-runtime-matrix.md"
  - "./new-modules-panel-mapping.md"
  - "./README.md"
  - "../../../docs/gestionale-commesse-comparison/wave1-reshape.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
---

# Wave 1 — nota FO operator (Themes)

> Themes = skin FO pubblico. Operator field-service = **panel Filament** finché story FO Folio dedicata.

## Runtime 2026-07-24

| Flusso SRC | Dove ora |
|---|---|
| Timbratura / convalida ore | `intervention/admin` (Filament) |
| Firma / sketch | `signature/admin` (Capture* pages) |
| Commesse | `workorder/admin` |
| Preventivi / sopralluoghi | `quotation/admin` |
| Clienti | `customer/admin` |
| Assistente | `aiassistant/admin` |
| Login FO pubblico | User Folio (Laraxot) — Auth SRC coesiste come replica |

Document: `modules_statuses=true` ma **RISCHIO** tabella `media` — non migrate cieco.  
SSoT enable: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md) · [functional-readiness.md](../../../docs/gestionale-commesse-comparison/functional-readiness.md).

## Test

I test operator migrati dal SRC vivono in `Modules/*/tests` (Filament), **non** nei Themes.  
Playbook: [migrate-gestionale-tests.md](../../docs/migrate-gestionale-tests.md).
