---
title: "Composer manifest — Platform"
type: concept
tags: [platform, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "Platform composer.json psr-4 namespace providers"
---

# Composer manifest — Platform

- Package: `laraxot/module_platform_fila5`
- Namespace PSR-4: `Modules\\Platform\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\Platform\\Providers\\PlatformServiceProvider`
  - `Modules\\Platform\\Providers\\RouteServiceProvider`
  - `Modules\\Platform\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
