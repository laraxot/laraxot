---
title: "Audit conformità Laraxot — replica normalize"
type: concept
tags: [gestionale-commesse, audit, laraxot, singular, replica]
created: 2026-07-23
updated: 2026-07-23
qmd: "audit laraxot replica normalize Customer WorkOrder Billing XotBase"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./copy-then-normalize.md"
  - "./module-mapping.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
---

# Audit conformità — post-normalize batch

> Snapshot dopo reshape `app/` + NS singolare + XotBase SP (2026-07-23).

## Verdetto

| Check | Stato |
|---|---|
| Nomi cartella singolari (23) | **OK** — plurali residui assenti |
| Layout senza Domain/Application/Infrastructure root | **OK** (batch) |
| `Modules\{Singolare}\` (no Customers/WorkOrders) | **OK** su cuore verificato |
| `{Name}ServiceProvider` extends XotBase | **OK** |
| Route/Event XotBase presenti | **OK** |
| `modules_statuses` solo chiavi singolari `false` | **OK** |
| XotBaseResource / PHPStan L10 / enable | **TODO** |

## Religione

Replica + singolare — [replica-singular-phase.md](./replica-singular-phase.md).  
Errori da non ripetere — [agent-errors-lessons.md](./agent-errors-lessons.md).
