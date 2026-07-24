---
title: "Audit conformità Laraxot — replica normalize"
type: concept
tags: [gestionale-commesse, audit, laraxot, singular, replica, phpstan]
created: 2026-07-23
updated: 2026-07-24
qmd: "audit laraxot replica normalize Customer WorkOrder Billing XotBase phpstan enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./replica-singular-phase.md"
  - "./copy-then-normalize.md"
  - "./module-mapping.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md"
---

# Audit conformità — post-normalize + deep quality

> Snapshot 2026-07-24: reshape `app/` + NS singolare + XotBase SP + PHPStan cuore + enable selettivo.

## Verdetto

| Check | Stato |
|---|---|
| Nomi cartella singolari (23) | **OK** — plurali residui assenti |
| Layout senza Domain/Application/Infrastructure root | **OK** |
| `Modules\{Singolare}\` | **OK** |
| `{Name}ServiceProvider` extends XotBase | **OK** |
| Route/Event XotBase presenti | **OK** |
| PHPStan L10 cuore GC (13 moduli) | **OK** — 0 errori reali |
| `modules_statuses` cuore = `true` | **OK** (vedi tabella fase) |
| Boot `artisan about` / Filament | **OK** (Employee AbsenceRequest Pages presenti) |
| Pest Customer + WorkOrder models/SP | **OK** (6+6) |
| Soft-dep DomPDF/PhpWord | **TODO umano** (`composer require`) |
| Enable Auth/HR/Document/… | **false** — gate non chiuso |
| Merge twin User/AI/Media | **vietato in questa fase** |

## Religione

Replica + singolare — [replica-singular-phase.md](./replica-singular-phase.md).  
Errori da non ripetere — [agent-errors-lessons.md](./agent-errors-lessons.md).  
Story qualità — [2.3.deep-quality-enabled-modules.story.md](../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md).
