---
title: "Composer manifest — WhatsApp"
type: concept
tags: [whatsapp, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "WhatsApp composer.json psr-4 namespace providers"
---

# Composer manifest — WhatsApp

- Package: `laraxot/module_whatsapp_fila5`
- Namespace PSR-4: `Modules\\WhatsApp\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\WhatsApp\\Providers\\WhatsAppServiceProvider`
  - `Modules\\WhatsApp\\Providers\\RouteServiceProvider`
  - `Modules\\WhatsApp\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
