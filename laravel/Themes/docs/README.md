---
title: "Laravel Themes Documentation"
type: index
tags: [themes, docs, gestionale-commesse]
created: 2026-06-05
updated: 2026-07-27
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

Hub dei temi. Porting gestionale: **[gestionale-docs-index.md](./gestionale-docs-index.md)** · **[tenant-modules-navigation-discipline.md](./tenant-modules-navigation-discipline.md)** (menu admin / JSON tenant).

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
| **[runtime-config-religion-hub.md](./shared-components/runtime-config-religion-hub.md)** | Hub lezioni runtime (permission, panel trinità, statuses) |
| **[module-filament-panel-triad.md](../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md)** | SSoT trinità panel (`config` + provider + dashboard) |
| **[module-admin-panel-provider-religion.md](./shared-components/module-admin-panel-provider-religion.md)** | Boot panel `{modulo}/admin` |
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
