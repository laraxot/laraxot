---
title: "Composer manifest — Document"
type: concept
tags: [document, composer, psr-4]
created: 2026-07-23
updated: 2026-07-23
qmd: "Document composer.json psr-4 namespace providers"
---

# Composer manifest — Document

- Package: `laraxot/module_document_fila5`
- Namespace PSR-4: `Modules\\Document\\` → `app/`
- Providers (extra.laravel.providers, da module.json):
  - `Modules\\Document\\Providers\\DocumentServiceProvider`
  - `Modules\\Document\\Providers\\RouteServiceProvider`
  - `Modules\\Document\\Providers\\EventServiceProvider`
- Repositories path: `../Xot`, `../Tenant`, `../UI`

Verifica namespace (singolo backslash):

```bash
python3 -c "import json; d=json.load(open('composer.json')); print(d['autoload'])"
```
