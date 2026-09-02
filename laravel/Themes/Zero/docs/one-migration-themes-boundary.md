---
title: "Temi — nessuna migrazione owner"
type: concept
status: canonical
created: 2026-09-01
updated: 2026-09-01
tags: [themes, migrations, boundaries]
qmd: "themes no database migrations one migration per model"
related:
  - ../../../Modules/Xot/docs/wiki/concepts/one-migration-per-model.md
  - ../../../../docs/wiki/memories/one-migration-per-model-bump-timestamp.md
---

# Temi e migrazioni

I temi (`Themes/One`, `Zero`, `Three`) **non** possiedono modelli Eloquent né
`database/migrations/`. La regola «1 modello = 1 migrazione» vive nei moduli under
`laravel/Modules/`.

UI/tema: asset e view; schema DB: solo modulo owner della tabella.
