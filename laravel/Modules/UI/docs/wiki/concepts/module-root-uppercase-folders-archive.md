---
title: "Archivio Config.bak — modulo UI"
type: concept
module: UI
status: active
tags: [module-structure, config, archive]
updated: "2026-06-30"
related:
  - ../../../../../../docs/project/module-root-structure-analysis.md
---

# Archivio `Config.bak/` — UI

## Situazione

`Config.bak/` duplicava i file già in `config/`:

- `config.php`
- `laravel-localization.php`
- `laravellocalization.php`

## Regola

Solo `config/` (minuscolo) alla root.

## Azione

`Config` → `Config.bak` (2026-06-30). Merge non necessario: contenuti allineati.
