---
title: "Timber (ex TimberBilling) — Model / Migration / Seeder / Factory parity"
type: status
tags: [timber, migrations, xotbasemigration, parity, one-migration-per-model]
created: 2026-07-24
updated: 2026-07-27
qmd: "Timber (ex TimberBilling) model migration seeder factory XotBaseMigration 29 parity"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./bugfix-users-cross-db-fk.md"
  - "./wiki/concepts/migration-xotbase-one-per-model.md"
  - "../../../../docs/wiki/rules/009-one-migration-per-model.md"
  - "../../../../docs/wiki/memories/xotbasemigration-only-never-illuminate.md"
---

# Timber (ex TimberBilling) — parità artefatti

## Religione (non negoziabile)

| Regola | Stato modulo |
|--------|----------------|
| `extends XotBaseMigration` only | ✅ |
| `tableCreate` / `tableUpdate` — no `Schema::create('tabella')` | ✅ |
| `$model_class = Model::class` | ✅ |
| 1 model = 1 `create_*` | ✅ **29 = 29** |
| No `add_*` / `drop_*` / batch multi-tabella | ✅ |
| Timestamps solo via `updateTimestamps` in `tableUpdate` | ✅ |
| User FK: `foreignIdFor` senza `constrained('users')` | ✅ |

## Conteggio

| Artefatto | N |
|-----------|---|
| Modelli concreti | **29** (incluso pivot `TimberProcessingStepProcessable`) |
| `create_*` XotBase | **29** |
| Factory | 29 |
| Seeder per-modello | 29 (+ orchestratore) |

## Pivot

`timber_processing_step_processable` ha **model dedicato** + migration XotBase.  
Vietato “plain Migration perché non c’è model” — vedi [migration-xotbase-one-per-model.md](./wiki/concepts/migration-xotbase-one-per-model.md).

## Anti-pattern rimossi (2026-07-27)

- `add_purchase_price_unit_*`, `add_article_*`, `drop_unique_label_*` → fusi negli owner
- Batch / `extends Migration` / `Schema::create` hardcoded
- Doc errato che consigliava Migration plain per il pivot
