---
title: "XotBaseMigration — religione (temi/moduli)"
type: rule
tags: [themes, migrations, xot-base-migration, shared-components]
created: 2026-07-27
updated: 2026-07-27
qmd: "themes XotBaseMigration migration religion create table convention"
related:
  - ./migration-foreign-id-for.md
  - ./migration-patterns.md
  - ./migration-philosophy.md
  - ../../../Modules/Xot/docs/wiki/concepts/xotbase-migration-religion.md
  - ../../../../docs/wiki/concepts/xotbase-migration-religion.md
---

# XotBaseMigration — guida temi e moduli

I temi non migrano DB; questa nota è il ponte per agenti che toccano `Modules/*/database/migrations`.

## Legge

- `XotBaseMigration` + `tableCreate` / `tableUpdate` / `updateTimestamps`
- Filename `{YYYY_MM_DD_HHMMSS}_create_{table_snake}_table.php` — mai `add_*_to_*`
- Mai `extends Migration`, mai `$connection` in migrazione

Esempio modulo: [User — migration-naming-religion-user.md](../../../Modules/User/docs/wiki/concepts/migration-naming-religion-user.md)

Canon: [Xot — xotbase-migration-religion](../../../Modules/Xot/docs/wiki/concepts/xotbase-migration-religion.md)
