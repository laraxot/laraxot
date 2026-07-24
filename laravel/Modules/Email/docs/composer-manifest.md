---
title: "Composer manifest — Email"
type: concept
tags: [email, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "Email composer.json psr-4 namespace providers"
---

# Composer manifest — Email

- Package: `laraxot/module_email_fila5`
- Namespace PSR-4: `Modules\\Email\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\Email\\Providers\\EmailServiceProvider`
  - `Modules\\Email\\Providers\\RouteServiceProvider`
  - `Modules\\Email\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
