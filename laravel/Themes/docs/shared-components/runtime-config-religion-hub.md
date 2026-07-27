---
title: "Runtime config — hub religion (temi)"
type: concept
tags: [theme, config, permission, modules, tenant, shared-components]
updated: 2026-07-27
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
related:
  - ./permission-table-names-religion.md
  - ./tenant-modules-statuses-religion.md
  - ./module-config-php-religion.md
  - ./module-dashboard-page-religion.md
  - ./module-admin-panel-provider-religion.md
  - ./spatie-permission-philosophy.md
  - ../gestionale-docs-index.md
  - ../gc-modules-runtime-matrix.md
  - ../../Modules/Tenant/docs/tenant-module-status-registry.md
  - ../../Modules/User/docs/bugfix-permission-table-names-singular.md
  - ../../Modules/Xot/docs/wiki/concepts/module-config-php-religion.md
---

# Runtime config — cosa imparare lavorando su temi

I temi **non** possiedono queste config, ma il FO/admin che montano **dipende** da esse. Errori runtime spesso sembrano “bug del tema” ma sono disallineamenti altrove.

## Tre registri + trinità panel (non confonderli)

| Registro | Path | Chi legge | Scopo |
|----------|------|-----------|--------|
| Panel metadata | `Modules/{M}/config/config.php` | `PanelMixin`, `registerConfig()` | `name`, `icon`, `navigation` Filament |
| Panel landing | `Modules/{M}/app/Filament/Pages/Dashboard.php` | `XotBasePanelProvider::discoverPages()` | Home `{modulo}/admin` — **obbligatoria** |
| Panel boot | `Modules/{M}/app/Providers/Filament/AdminPanelProvider.php` | `module.json` → nwidart | Registra path + discover Resources/Pages |
| Spatie pivot | `laravel/config/permission.php` → `table_names` | `ModelHasRole::getTable()`, Spatie | Nome tabella pivot RBAC — **immutabile** |
| Tenant moduli | `config/local/workorder/modules_statuses.json` | `GetTenantModulesAction`, FileActivator | Quali moduli compaiono per tenant workorder |

## Regole apprese (2026-07-27)

### 1. `permission.php` — mai toccare `table_names`

Valori fissi (singolare): `model_has_role`, `model_has_permission`, `role_has_permission`.

Errore “table doesn't exist” → schema/migrazione/modello pivot, **non** config.

→ [permission-table-names-religion.md](./permission-table-names-religion.md) · [User bugfix](../../Modules/User/docs/bugfix-permission-table-names-singular.md)

### 2. Migrazioni pivot — zero nomi tabella nel file

`XotBaseMigration` + modello (`ModelHasRole`) → `getTable()` legge config. Vietato `Schema::rename` hardcoded o `tableCreate($fn, 'nome')`.

→ [User — spatie-permission-migration-no-table-name](../../Modules/User/docs/wiki/concepts/spatie-permission-migration-no-table-name.md)

### 3. Trinità panel — `config.php` + `AdminPanelProvider` + `Dashboard.php`

```bash
bash bashscripts/tools/audit-module-config-php.sh
bash bashscripts/tools/audit-module-admin-panel-provider.sh
bash bashscripts/tools/audit-module-dashboard-page.sh
```

→ [module-filament-panel-triad.md](../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md) · [module-config-php-religion.md](./module-config-php-religion.md) · [module-admin-panel-provider-religion.md](./module-admin-panel-provider-religion.md) · [module-dashboard-page-religion.md](./module-dashboard-page-religion.md)

### 4. Tenant workorder — `modules_statuses.json`

Path: `config/local/workorder/` (da hostname `workorder.local`, non `config/workorder/`).

Rigenera: `bash bashscripts/tools/sync-tenant-modules-statuses.sh local/workorder`

→ [tenant-modules-statuses-religion.md](./tenant-modules-statuses-religion.md) · [Tenant registry](../../Modules/Tenant/docs/tenant-module-status-registry.md)

### 5. Profiles User — id bigint + uuid

Una sola `create_profiles_table`; legacy UUID PK → `convertIdFromUuidToBigintIfNeeded()`.

→ [profile-id-bigint-uuid-fix](../../Modules/User/docs/wiki/concepts/profile-id-bigint-uuid-fix.md)

## Verifica rapida (solo lettura)

```bash
php artisan config:show permission.table_names
jq 'keys | length' laravel/config/local/workorder/modules_statuses.json
bash bashscripts/tools/audit-module-config-php.sh
bash bashscripts/tools/audit-module-admin-panel-provider.sh
bash bashscripts/tools/audit-module-dashboard-page.sh
```

## Trigger map

[00-TRIGGER_MAP.md](../../../../docs/wiki/rules/00-TRIGGER_MAP.md) — permission pivot, panel trinità, `modules_statuses`.
