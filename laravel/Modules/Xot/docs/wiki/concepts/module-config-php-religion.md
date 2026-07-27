---
title: "Modulo — config/config.php obbligatorio"
type: concept
module: Xot
tags: [module, config, nwidart, panel, filament, registerConfig]
created: 2026-07-27
updated: 2026-07-27
qmd: "module config config.php mandatory registerConfig PanelMixin navigation icon name"
related:
  - ../../../../../docs/wiki/concepts/module-config-php-religion.md
  - ../../../../../docs/wiki/concepts/nwidart-module-skeleton-contract.md
  - ../templates/module-config-php.stub.php
  - ../../panel-mixin-extension-pattern.md
---

# config/config.php — religione modulo

## Perché

Ogni modulo espone metadati panel/navigation **senza hardcode** in Filament provider: label, icona, sort vivono in un solo file versionato.

## Obbligo

| Path | Obbligatorio |
|------|--------------|
| `Modules/<Modulo>/config/config.php` | Sì, per ogni modulo con `module.json` |
| `Modules/docs/` | No (cartella documentazione, non modulo nwidart) |

## Schema minimo

```php
<?php

declare(strict_types=1);

return [
    'name' => 'User',
    'description' => 'Gestione utenti e autorizzazioni',
    'icon' => 'heroicon-o-users',
    'navigation' => [
        'enabled' => true,
        'sort' => 100,
    ],
];
```

| Chiave | Obbligatoria | Uso |
|--------|--------------|-----|
| `name` | Sì | Label panel / navigazione |
| `icon` | Sì | Heroicon Filament |
| `navigation.enabled` | Consigliata | Panel attivo |
| `navigation.sort` | Consigliata | Ordine menu |
| `description` | Consigliata | Docs / tooling |

## Come viene caricata

1. `XotBaseServiceProvider::registerConfig()` — glob `config/*.php` → `Config::set('{snake}.{filename}', require)`
2. Accesso: `config('user.name')`, `config('user.config')` se file è `config.php`
3. `PanelMixin::getModuleConfig()` — require diretto `getPath().'/config/config.php'`

## Vietato

- Modulo senza `config/config.php`
- `env()` in `config/config.php` modulo (solo `.env` root + `config/local/{tenant}/`)
- Duplicare name/icon in `AdminPanelProvider` hardcoded

## Audit

```bash
bash bashscripts/tools/audit-module-config-php.sh
bash bashscripts/tools/audit-module-config-php.sh User
bash bashscripts/tools/guard-nwidart-module-skeleton.sh
```

## Temi

I temi (`laravel/Themes/<Tema>/`) usano `theme.json` per metadati nwidart-tema. La regola `config/config.php` vale per **moduli** `Modules/`. Documentazione temi: [Themes module-config reference](../../../Themes/docs/shared-components/module-config-php-religion.md).

## Nuovo modulo

1. Copiare [module-config-php.stub.php](../templates/module-config-php.stub.php)
2. Creare `app/Providers/Filament/AdminPanelProvider.php` + voce in `module.json` (e `composer.json` extra.laravel.providers)
3. Creare `app/Filament/Pages/Dashboard.php` → `extends XotBaseDashboard` (classe vuota)
4. Audit prima di merge

Vedi [module-filament-panel-triad.md](./module-filament-panel-triad.md) · [module-admin-panel-provider-mandatory.md](./module-admin-panel-provider-mandatory.md).
