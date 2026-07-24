---
title: "Customer — knowledge base"
type: overview
tags: [customer, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Customer docs README purpose anagrafica panel readiness"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/gestionale-commesse-comparison/glossary-align.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Customer

## Perché esiste

Anagrafica **clienti / sedi / referenti / rivenditori**: radice del grafo field-service. Senza Customer non hanno senso commesse, preventivi, interventi.

Replica SRC `Customers` → cartella **singolare** `Customer` (non merge in altri moduli).

## Cosa NON è

- Non è CRM marketing, non è User/Auth.
- Non gestisce documenti file (→ Document/Media, con debiti noti).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | migrate path OK |
| Panel | `customer/admin` |
| PHPStan L10 | 0 errori reali |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist reshape + readiness |
| [README.md](./README.md) | Questa mappa |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- [replica-singular-phase.md](../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md)
- [glossary-align.md](../../../../docs/gestionale-commesse-comparison/glossary-align.md)
- Story [1.2](../../../../docs/wiki/skills/bmad-create-story/stories/1.2.customer-module.story.md) · [2.3](../../../../docs/wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md)
- Issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md)
