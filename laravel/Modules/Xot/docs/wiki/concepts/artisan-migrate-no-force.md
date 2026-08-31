---
title: Xot Artisan migrate — dati sacri
type: concept
tags: [xot, artisan, migrate, data-sacred]
created: 2026-08-31
updated: 2026-08-31
qmd: artisan action migrate no force data sacred
related:
  - ../../../../docs/wiki/rules/data-sacred-no-destructive-db.md
  - ../ide-helper-models-governance.md
  - ../../../../docs/stories/1.15.data-sacred-no-migrate-force.story.md
---

# Artisan migrate in Xot — senza `--force`

`ArtisanAction` / `ArtisanService` / `MigrationCommandHandler` invocano solo:

```php
Artisan::call('migrate');
Artisan::call('module:migrate', ['module' => $name]);
```

Mai `--force`, mai `fresh`/`wipe`. I dati sono sacri.

Story: [1.15](../../../../docs/stories/1.15.data-sacred-no-migrate-force.story.md).
