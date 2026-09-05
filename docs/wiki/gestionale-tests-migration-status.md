---
title: "Tests migration — hub stato moduli GC"
type: concept
tags: [tests, pest, gestionale-commesse, hub, migration]
created: 2026-07-24
updated: 2026-07-24
qmd: "hub gestionale tests migration status Catalog HR Customer WorkOrder Quotation"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../../docs/migrate-gestionale-tests.md"
  - "../../../docs/wiki/skills/bmad-create-story/stories/2.5.migrate-gestionale-tests.story.md"
  - "../../../docs/wiki/skills/bmad-create-story/stories/2.5.gestionale-tests-migration.story.md"
  - "../../../docs/wiki/skills/bmad-create-story/stories/1.13.migrate-real-tests-and-timber-gap.story.md"
  - "../Catalog/docs/tests-migration.md"
  - "../HR/docs/tests-migration.md"
  - "../Customer/docs/tests-migration.md"
  - "../WorkOrder/docs/tests-migration.md"
  - "../Intervention/docs/tests-migration.md"
---

# Tests migration — hub stato moduli GC

> SSoT story: [2.5](../../../docs/wiki/skills/bmad-create-story/stories/2.5.migrate-gestionale-tests.story.md) · Registro: [2.5 alias](../../../docs/wiki/skills/bmad-create-story/stories/2.5.gestionale-tests-migration.story.md) · Playbook: [migrate-gestionale-tests.md](../../docs/migrate-gestionale-tests.md)

## Stato per modulo (2026-07-24)

| Modulo | SRC | DEST / Pest | Docs |
|---|---|---|---|
| Customer | Feature ~12 | Actions **11 passed** | [tests-migration](../Customer/docs/tests-migration.md) |
| WorkOrder | Feature ~14 | Create smoke green | [tests-migration](../WorkOrder/docs/tests-migration.md) |
| Intervention | Feature ~23 | Create + skip Platform | [tests-migration](../Intervention/docs/tests-migration.md) |
| Quotation | Feature ~33 + Unit | migrati; Feature.lock; skip voice/PDF | modulo docs |
| Signature | 3+1 | migrati | — |
| Vehicle | 2 | VehicleCrud smoke | — |
| AiAssistant | 6 | migrati + mock/skip API | — |
| **Catalog** | 2 | **11 passed** | [tests-migration](../Catalog/docs/tests-migration.md) |
| **HR** | 5 | **3 passed / 5 skipped** (D1/D6) | [tests-migration](../HR/docs/tests-migration.md) |
| Billing | ~8 | parziale + skip D17 | — |
| Auth | ~22 | tallies in 1.13 | — |
| Document | 4 | **non** Feature finché D1 | debt D1 |
| Platform | ~21 | tallies 1.13 | — |
| Timber / TimberBilling | 32+ | owner [1.13](../../../docs/wiki/skills/bmad-create-story/stories/1.13.migrate-real-tests-and-timber-gap.story.md) | — |
| Compliance / Inventory / Email / … | vari | smoke o triage | — |
| Wts / Operator / Public / Localization | vari | defer | — |

## Comando smoke Catalog+HR

```bash
cd laravel && ./vendor/bin/pest Modules/Catalog/tests Modules/HR/tests --no-coverage
# 14 passed, 5 skipped
```

## Religion

No `RefreshDatabase` · no `migrate --force` · TestCase = fixcity + schema prima di transactions.
