---
title: "Panels SRC vs Themes Sixteen"
type: concept
tags: [sixteen, themes, gestionale-commesse]
created: 2026-07-23
updated: 2026-07-24
qmd: "Sixteen themes panels gestionale_commesse folio operator"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../docs/gestionale-panels-vs-themes.md"
  - "../../docs/gestionale-docs-index.md"
  - "../../docs/wave1-fo-operator-note.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
---

# Panels SRC vs Themes Sixteen

> **Perché:** Sixteen è il FO attivo; il gestionale_commesse non fornisce un tema equivalente. La BL resta nei moduli.

Canon hub: [gestionale-docs-index.md](../../docs/gestionale-docs-index.md) · dettagli panel: [gestionale-panels-vs-themes.md](../../docs/gestionale-panels-vs-themes.md) · enable: [gc-modules-runtime-matrix.md](../../docs/gc-modules-runtime-matrix.md).

Wave 1: operator field-service = Filament `*/admin`, **non** Folio Sixteen. Vedi [wave1-fo-operator-note.md](../../docs/wave1-fo-operator-note.md).

I test operator migrati dal SRC vivono in `Modules/{Intervention,Signature,…}/tests` (Filament/panel), non in Themes — playbook [migrate-gestionale-tests.md](../../../docs/migrate-gestionale-tests.md).

Runtime: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).
