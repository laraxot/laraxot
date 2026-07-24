---
title: "Laravel Themes Documentation"
type: index
tags: [themes, docs, gestionale-commesse]
created: 2026-06-05
updated: 2026-07-24
qmd: "Themes docs hub Sixteen gestionale panels index"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-docs-index.md"
  - "./gc-modules-runtime-matrix.md"
  - "./module-vs-theme-boundaries.md"
  - "../Sixteen/docs/README.md"
---

# Laravel Themes Documentation

Hub dei temi. Per il porting gestionale partire da **[gestionale-docs-index.md](./gestionale-docs-index.md)** e dalla **[matrice runtime](./gc-modules-runtime-matrix.md)**.

## Current Themes

| Theme | Role | Status | Docs |
| --- | --- | --- | --- |
| `Sixteen` | FO attivo (Design Comuni / BI) | active | [README](../Sixteen/docs/README.md) |
| `TwentyOne` | FO alternativo Vite/Tailwind | available | [README](../TwentyOne/docs/README.md) |
| `Zero` / altri | sperimentali / nicchie | available | rispettivi `docs/README.md` |

## Theme Rules

- Home docs tema: `laravel/Themes/<Theme>/docs/README.md`
- Vietato `lang/lang/` o `_docs/`: [directory-structure-rules.md](./directory-structure-rules.md)
- Indici root corti: routing, non duplicare
- Governance: [docs/project/docs-governance.md](../../../docs/project/docs-governance.md) (se presente)

## Gestionale / replica (SSoT temi)

| Doc | Scopo |
|---|---|
| **[gestionale-docs-index.md](./gestionale-docs-index.md)** | Indice unico |
| [module-vs-theme-boundaries.md](./module-vs-theme-boundaries.md) | Confini |
| [wave1-fo-operator-note.md](./wave1-fo-operator-note.md) | Operator = Filament |
| [new-modules-panel-mapping.md](./new-modules-panel-mapping.md) | URL `*/admin` |
| [copy-normalize-modules-vs-themes.md](./copy-normalize-modules-vs-themes.md) | Copy ≠ Themes |
| Hub prodotto | [functional-readiness.md](../../../docs/gestionale-commesse-comparison/functional-readiness.md) |

## Fast Paths

- [Sixteen](../Sixteen/docs/README.md) · [TwentyOne](../TwentyOne/docs/README.md)
- [Modules docs](../../Modules/docs/README.md)
- [llm-wiki.md](./llm-wiki.md) · [AGENTS.md](../../../AGENTS.md)

## Note legacy (UPPERCASE)

File come `ON-DEMAND-PATTERN.md` / `DOCUMENTATION_INDEX.md` restano stub legacy: preferire wiki root e questo hub. Non crearne di nuovi in UPPERCASE.
