---
title: "Quick Reference - Tenant Config"
type: concept
tags: [tenant, config]
created: 2026-07-14
updated: 2026-07-14
qmd: "tenant-config quick reference - tenant config"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./chart-widgets.md"
  - "./filament.md"
  - "./queue-jobs.md"
---

# Quick Reference - Tenant Config

## Verifiche base

```php
config('app.url');
app(\Modules\Tenant\Actions\GetTenantNameAction::class)->execute();
config('it.quaeris.manager.morph_map');
\Illuminate\Database\Eloquent\Relations\Relation::morphMap();
```

## Problema tipico

- Il worker usa config vecchia/cached o tenant path inatteso.

## Azioni

1. verificare tenant risolto
2. verificare chiavi config effettivamente caricate
3. riallineare provider/morph map
4. restart queue worker
