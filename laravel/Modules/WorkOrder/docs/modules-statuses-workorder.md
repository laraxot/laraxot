---
title: modules_statuses workorder tenant
type: concept
module: WorkOrder
tags: [modules_statuses, tenant, workorder, nwidart]
updated: 2026-07-27
related:
  - ../../../../docs/wiki/concepts/tenant-config-directory-sacred.md
  - ../../../Tenant/docs/it/config/modules-statuses.md
  - ../../../Tenant/docs/tenant-module-status-registry.md
  - ../../../Tenant/docs/session-learnings-modules-config.md
  - ../../../../Themes/docs/tenant-modules-navigation-discipline.md
---

# `config/local/workorder/modules_statuses.json`

## Ruolo

Profilo tenant **workorder**: moduli con `module.json` e flag per Nwidart (via `modules.php` tenant) e navigazione Filament (`GetTenantModulesAction`).

Path: `laravel/config/local/workorder/modules_statuses.json`

```php
// config/local/workorder/modules.php
'statuses-file' => base_path('config/local/workorder/modules_statuses.json'),
```

**38 moduli**, tutti `true`. SSoT fisico = presenza di `module.json`.

## Cosa NON va nel file

| Tipo | Esempi |
|------|--------|
| Senza `module.json` | `Blog`, `Comment`, `TestModule` |
| Fantasma JSON | `DbForge`, `FormBuilder` |
| Altro deploy | `Quaeris`, `Ticket`, `Shop` |

`GetTenantModulesAction` filtra in silenzio — menu incompleto senza eccezioni.

## Perché restava stale

1. Bugfix runtime (`profiles`, `permission`) — non audit moduli
2. Copia da altro `base_*_fila5`
3. Root vs tenant vs `modules.php` disallineati
4. Audit solo su `Modules/*/config/config.php`

## Manutenzione

```bash
bash bashscripts/tools/sync-tenant-modules-statuses.sh local/workorder
```

Nuovo modulo → `module.json` + voce in root e tenant JSON + `config/config.php`.

## Verifica

```bash
cd laravel
jq 'keys | length' config/local/workorder/modules_statuses.json   # 38
php artisan tinker --execute="echo count(app(\Modules\Tenant\Actions\Modules\GetTenantModulesAction::class)->execute());"
```
