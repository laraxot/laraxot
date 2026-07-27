---
title: "User — teams owner_id in create, no add_*"
type: concept
module: User
tags: [user, teams, migrations, xot-base-migration, owner_id]
created: 2026-07-27
updated: 2026-07-27
qmd: "user teams owner_id create_teams_table no add_owner_id migration"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
related:
  - ../../../../Xot/docs/wiki/concepts/xotbase-migration-religion.md
  - ../../../../../docs/wiki/concepts/xotbase-migration-religion.md
---

# Teams — `owner_id` nella create

## Anti-pattern (storico)

`2025_05_16_221811_add_owner_id_to_teams_table.php` — viola naming e one-migration-per-model.

## Canon

File: `database/migrations/2025_01_22_115959_create_teams_table.php`

- `extends XotBaseMigration`
- `owner_id` in `tableCreate` + guard `hasColumn` in `tableUpdate`
- `updateTimestamps($table, true)`
- Nessun `$connection` — connection dal model `Team`

## Riferimenti

- [xotbase-migration-religion](../../../../Xot/docs/wiki/concepts/xotbase-migration-religion.md)
