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
- **`'icon' => 'heroicon-o-square-3-stack-3d'`** — placeholder generico usato durante lo
  scaffolding automatico dei moduli portati da gestionale_commesse. Va sempre sostituito
  con un'icona semanticamente pertinente al dominio del modulo prima che il modulo sia
  considerato completo (vedi sezione successiva).

## Scelta dell'icona: due percorsi validi (verificati nel sorgente)

**Opzione A — Heroicon esistente (default, più semplice):** qualunque nome file presente
in `vendor/blade-ui-kit/blade-heroicons/resources/svg/o-{nome}.svg` (outline) o
`s-{nome}.svg` (solid), referenziato come `heroicon-o-{nome}`/`heroicon-s-{nome}`.
Verificare che il nome esista realmente prima di usarlo:
```bash
ls vendor/blade-ui-kit/blade-heroicons/resources/svg/o-{nome}.svg
```
o a runtime: `php artisan tinker --execute="echo svg('heroicon-o-{nome}')->toHtml();"`
(lancia eccezione se il nome non esiste — non fidarsi del solo "sembra plausibile").

**Opzione B — SVG custom del modulo (per branding/icone specifiche non coperte da Heroicons):**
`Modules/<Modulo>/resources/svg/{file}.svg` → auto-registrato da
`XotBaseServiceProvider::registerBladeIcons()` (verificato in
`Modules/Xot/app/Providers/XotBaseServiceProvider.php`) con
`$factory->add($this->nameLower, ['path' => $svgPath, 'prefix' => $this->nameLower])`.
Riferimento nell'icona: `'{nome_modulo_lower}-{file_senza_estensione}'`, dove
`{nome_modulo_lower}` è `Illuminate\Support\Str::lower($module->name)` — **attenzione**:
`Str::lower()` non è `Str::snake()`, quindi un modulo `PublicProcurement` diventa
`publicprocurement` (una parola sola, nessun trattino interno), non `public-procurement`.
Esempio reale già in uso: `Modules/Employee/resources/svg/icon2.svg` →
`'icon' => 'employee-icon2'`.

**Quando scegliere B invece di A**: quando esiste già (o serve) un'icona di dominio/branding
non coperta da Heroicons — **`resources/svg/icon.svg`** + `'icon' => '{alias}-icon'`.
Se `icon.svg` è presente, **B è obbligatorio** (sync/audit lo impongono).

Per placeholder `heroicon-o-square-3-stack-3d` e batch sync: [module-config-icon-svg.md](./module-config-icon-svg.md).

Esempi in repo (2026-07-27): `WorkOrder` → `workorder-icon`, `Billing` → `billing-icon`,
`Employee` → `employee-icon2` (override filename custom).

## Audit icona SVG

```bash
bash bashscripts/tools/audit-module-config-icon-svg.sh
bash bashscripts/tools/sync-module-config-icon-svg.sh
```

## Audit config modulo

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
