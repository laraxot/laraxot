---
title: "User — profiles id bigint + uuid (fix 1364)"
type: concept
module: User
tags: [user, profile, migration, uuid, bigint, id-contract]
created: 2026-07-27
updated: 2026-07-27
qmd: "user profiles id bigint uuid convertIdFromUuidToBigintIfNeeded field id default value 1364"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
related:
  - ./profile-migration-uuid-contract.md
  - ./migration-naming-religion-user.md
  - ../../../../Xot/docs/wiki/concepts/basemodel-connection-religion.md
---

# Profiles — errore 1364 `id` senza default

## Sintomo

```
Field 'id' doesn't have a default value
insert into profiles (user_id, uuid, ...) — senza id
```

## Causa

- DB legacy: `id` char(36) PK (migrazione `uuid('id')->primary()`)
- Model: `incrementing=true` → Laravel non invia `id`
- **5 file** `create_profiles_table` duplicati, nessuno chiamava `convertIdFromUuidToBigintIfNeeded()`

## Fix (2026-07-27)

Owner **WorkOrder** (`main_module`): `2026_07_27_111500_create_profiles_table.php`

- `tableCreate`: `id()` + `uuid` + colonne dominio
- `convertIdFromUuidToBigintIfNeeded()` per legacy UUID PK
- `tableUpdate`: colonne additive + `updateTimestamps`
- Duplicati User in `_bak/*.merged`

Vedi [WorkOrder profile-schema-ownership](../../../WorkOrder/docs/profile-schema-ownership.md).

## Contratto

| Colonna | Tipo | Uso |
|---------|------|-----|
| `id` | bigint unsigned AI | interno |
| `uuid` | char(36) | API / `byUuid()` |

`BaseProfile::booted()` genera `uuid` se vuoto.

## Verifica

```bash
php artisan migrate
# SHOW COLUMNS: id bigint unsigned auto_increment
```
