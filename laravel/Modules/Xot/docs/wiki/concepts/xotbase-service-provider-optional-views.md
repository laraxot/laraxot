---
title: "XotBaseServiceProvider — views opzionali"
type: concept
module: Xot
tags: [xot, service-provider, views, view-cache, modules]
created: 2026-07-27
updated: 2026-07-27
qmd: "xot base service provider optional resources views directory view cache"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
related:
  - ../../../../../../docs/wiki/concepts/view-cache-filament-v5-prerequisites.md
  - ./xotbase-migration-religion.md
---

# Views modulo opzionali

## Problema

Moduli senza Blade (es. Bom, Production) non avevano `resources/views/`.  
`loadViewsFrom()` registrava un path inesistente → `view:cache` falliva con `DirectoryNotFoundException`.

## Fix (XotBaseServiceProvider)

| Metodo | Guard | Effetto |
|--------|-------|---------|
| `registerViews()` | `is_dir(resources/views)` | niente `loadViewsFrom` su path assente |
| `registerBladeComponents()` | `is_dir(resources/views/components)` | niente `anonymousComponentPath` su path assente |

## Politica

- Modulo **con** view FO/Blade → cartella `resources/views/` + namespace `strtolower($module)`
- Modulo **senza** view → nessuna cartella obbligatoria (non serve `.gitkeep` artificiale)
- Modulo **con** componenti Blade anonimi → solo se esiste `resources/views/components/`

## Gate

```bash
cd laravel && php artisan view:cache
```
