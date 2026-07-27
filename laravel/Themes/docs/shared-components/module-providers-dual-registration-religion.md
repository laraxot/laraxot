---
title: "Moduli Laraxot — provider in composer.json E module.json (doppia registrazione)"
type: concept
tags: [theme, module, filament, provider, composer, module-json, cross-reference]
updated: 2026-07-27
related:
  - ../../Modules/Xot/docs/wiki/concepts/module-providers-dual-registration-mandatory.md
  - ../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md
  - ./module-admin-panel-provider-religion.md
  - ./module-dashboard-page-religion.md
  - ./module-config-php-religion.md
  - ./runtime-config-religion-hub.md
---

# Doppia registrazione provider (moduli)

## Perché un tema deve conoscerlo

I temi non hanno `module.json`/`composer.json` con provider Filament, ma quando un operatore
non vede un panel (`/billing/admin` assente) spesso il modulo ha Resources scritte ma
**provider non allineati** tra i due manifest.

## Regola

Ogni **modulo** elenca **gli stessi minimo 2 provider** in:

| File | Chiave |
|------|--------|
| `module.json` | `providers[]` |
| `composer.json` | `extra.laravel.providers[]` |

```json
[
  "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
  "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
]
```

Due meccanismi di discovery indipendenti — asimmetria = bug silenzioso (TimberBilling 2026-07-27).

## Checklist agente (modulo con Filament)

1. `config/config.php`
2. `{M}ServiceProvider` + `AdminPanelProvider` in **entrambi** i manifest
3. `app/Providers/Filament/AdminPanelProvider.php` (file fisico)
4. `app/Filament/Pages/Dashboard.php`

SSoT trinità: [module-filament-panel-triad.md](../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md)

## Audit

```bash
bash bashscripts/tools/audit-module-providers-dual-registration.sh
```

Canon: [module-providers-dual-registration-mandatory.md](../../Modules/Xot/docs/wiki/concepts/module-providers-dual-registration-mandatory.md)
