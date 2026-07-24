---
title: "WorkOrder — knowledge base"
type: overview
tags: [workorder, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "WorkOrder docs README purpose commessa panel quality"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/glossary-align.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# WorkOrder

## Perché esiste

**Commessa** operativa: stato, attività, assignment. È il cuore del gestionale — collega Customer → Intervention / Quotation / Billing.

Lingua: *Commessa* = WorkOrder ([glossary](../../../../docs/gestionale-commesse-comparison/glossary-align.md)).

## Cosa NON è

- Non è intervento sul campo (→ Intervention).
- Non è preventivo (→ Quotation).
- Themes FO non ospitano questa BL in Wave 1 (panel Filament).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | `work_orders*` OK (`primary_contact_id` senza FK DB) |
| Panel | `workorder/admin/work-orders` |
| PHPStan L10 | 0 errori reali |
| Pest | 6/6 smoke classi; **manca** test Actions/Policy |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist import/reshape |
| [README.md](./README.md) | Scopo + qualità |

## Qualità (sintesi)

Actions/Policy/migrazioni verificate senza bug strutturali noti. Aperto: Feature test di dominio (stati, assign, export PDF).

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- Stories [1.3](../../../../docs/wiki/skills/bmad-create-story/stories/1.3.workorder-module.story.md) · [1.8](../../../../docs/wiki/skills/bmad-create-story/stories/1.8.wave1-reshape-xotbase.story.md) · [2.3](../../../../docs/wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md)
