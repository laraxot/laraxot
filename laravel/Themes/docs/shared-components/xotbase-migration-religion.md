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
- Filename `*_create_{table}_table.php`
- Mai `extends Migration`, mai `add_*_to_*` separati, mai `$connection` in migrazione

Canon: [Xot — xotbase-migration-religion](../../../Modules/Xot/docs/wiki/concepts/xotbase-migration-religion.md)
