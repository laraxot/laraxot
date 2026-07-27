---
title: "Bugfix — inventory_movements created_by_user_id UUID"
type: troubleshooting
tags: [inventory, migration, uuid, foreignIdFor, users]
created: 2026-07-24
updated: 2026-07-27
qmd: "inventory_movements created_by_user_id foreignUuid foreignIdFor users uuid"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./bugfix-warehouses-vehicle-fk-order.md"
  - "./README.md"
  - "../../../../docs/wiki/concepts/migration-foreign-id-for.md"
---

# Bugfix — `created_by_user_id` vs UUID users

## Problem

`php artisan migrate` falliva su `inventory_movements` (MySQL 3780):
`foreignId('created_by_user_id')` = bigint, ma `users.id` = UUID string.

## Fix

Usare `foreignIdFor(XotData::make()->getUserClass(), 'created_by_user_id')->nullable()->index()`
— **senza** `constrained('users')` se User è su altra connection.

Canon: [migration-foreign-id-for.md](../../../../docs/wiki/concepts/migration-foreign-id-for.md).
Correlato ordine FK: [bugfix-warehouses-vehicle-fk-order.md](./bugfix-warehouses-vehicle-fk-order.md).
