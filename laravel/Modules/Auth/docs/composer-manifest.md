---
title: "Composer manifest — Auth"
type: concept
tags: [auth, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "Auth composer.json psr-4 namespace providers"
---

# Composer manifest — Auth

- Package: `laraxot/module_auth_fila5`
- Namespace PSR-4: `Modules\\Auth\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\Auth\\Providers\\AuthServiceProvider`
  - `Modules\\Auth\\Providers\\RouteServiceProvider`
  - `Modules\\Auth\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
