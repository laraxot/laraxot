---
title: "Bugfix — Timber (ex TimberBilling) FK users cross-DB (MySQL 1824)"
type: troubleshooting
tags: [timber, migration, users, foreignIdFor, mysql-1824, cross-database]
created: 2026-07-27
updated: 2026-07-27
qmd: "timber_purchase_records created_by_user_id users 1824 cross database foreignIdFor"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./wiki/concepts/migration-xotbase-one-per-model.md"
  - "./models-migrations-seeders-factories.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
  - "../../Inventory/docs/bugfix-warehouses-vehicle-fk-order.md"
---

# Bugfix — Timber → `users` (errore 1824)

## Perché (business)

Le entità Timber (acquisti, listini, note, fatture) tracciano **chi** ha creato/eseguito l’azione (`created_by_user_id`, …). L’utente vive nel modulo User — non nel DB dominio timber.

## Filosofia

| Livello | Intent |
|---------|--------|
| Politica DB | `users` → connection `user` / `workorder_user`; Timber → `mariadb`/`xot` / `workorder_data` |
| Religione FK | `constrained('users')` / `->foreign()->on('users')` **vietato** cross-DB |
| Zen | `foreignIdFor(XotData::make()->getUserClass(), $col)->nullable()->index()`; relazione solo Eloquent |
| KISS | Stesso pattern Inventory/Blog — un solo contratto cross-modulo |

## Sintomo

```
SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'users'
alter table `timber_purchase_records` add constraint ... references `users` (`id`)
```

## File toccati

| Migration | Fix |
|-----------|-----|
| `2026_07_03_100000_create_timber_tables` | già: `foreignIdFor` senza constrained (+ hasTable) |
| `2026_07_10_100000_create_timber_price_lists_tables` | rimosso `->on('users')` |
| `2026_07_10_100200_create_timber_contact_notes_table` | idem |
| `2026_07_24_000501_create_timber_invoices_table` | idem |

## Canon

- [migration-foreign-id-for.md](../../../../docs/wiki/concepts/migration-foreign-id-for.md)
- [migration-idempotent-batch.md](./wiki/concepts/migration-idempotent-batch.md)
