---
title: "Composer manifest — HR"
type: concept
tags: [hr, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "HR composer.json psr-4 namespace providers"
---

# Composer manifest — HR

- Package: `laraxot/module_hr_fila5`
- Namespace PSR-4: `Modules\\HR\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\HR\\Providers\\HrServiceProvider`
  - `Modules\\HR\\Providers\\RouteServiceProvider`
  - `Modules\\HR\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
