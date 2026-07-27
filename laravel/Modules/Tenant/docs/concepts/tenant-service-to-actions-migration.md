---
title: migrazione tenantservice ad actions
type: concept
module: Tenant
tags: [tenant, queueable-action, migration]
updated: "2026-07-21"
related:
  - ../README.md
  - ../../../docs/wiki/rules/no-services-rule.md
---

# TenantService → Actions

## Cosa è successo

`app/Services/TenantService.php` (stub statico) è stato **eliminato**. I caller usano le Actions già presenti in `app/Actions/`.

## Mappa sostituzioni

| Vecchio | Nuovo |
|---------|-------|
| `TenantService::getConfig($name)` | `app(GetTenantConfigArrayAction::class)->execute($name)` |
| `TenantService::saveConfig($name, $data)` | `app(SaveTenantConfigAction::class)->execute($name, $data)` |
| `TenantService::trans($key)` | `app(TranslateTenantKeyAction::class)->execute($key)` |
| `TenantService::allModules()` | `app(GetTenantModulesAction::class)->execute()` |
| `TenantService::config($key)` | `app(ResolveTenantConfigValueAction::class)->execute($key)` |

## Perché

Config e moduli tenant sono use case distinti: un Action = un ingresso `execute()`, composizione via `app()`, niente facade statica.
