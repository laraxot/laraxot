---
title: "Ogni modulo: minimo 2 provider, in composer.json E module.json"
type: concept
module: Xot
tags: [filament, provider, composer, module-json, module-convention]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-admin-panel-provider-mandatory.md
  - ./module-dashboard-page-mandatory.md
  - ./module-config-php-religion.md
  - ./module-filament-panel-triad.md
  - ../../../../Themes/docs/shared-components/module-providers-dual-registration-religion.md
---

# Doppia registrazione provider — `composer.json` + `module.json`

## Regola (convenzione Laraxot)

Ogni modulo con `module.json` deve elencare **almeno 2 provider**, **identici** in entrambi i file:

```json
"providers": [
    "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
    "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
]
```

| File | Chiave |
|------|--------|
| `module.json` | `providers` |
| `composer.json` | `extra.laravel.providers` |

**Liste allineate** — stessi FQCN, preferibilmente stesso ordine (ServiceProvider, poi AdminPanelProvider).

## I due provider minimi

| # | Provider | Perché |
|---|----------|--------|
| 1 | `{Nome}ServiceProvider` | Boot modulo: config, views, lang, migrations, bindings |
| 2 | `Providers\Filament\AdminPanelProvider` | Panel BO `/{modulo}/admin` + `discoverResources/Pages/Widgets` |

Senza (1) il modulo non boota. Senza (2) in **entrambi** i manifest, il panel Filament può mancare in un contesto di caricamento.

## Perché due file, non uno solo

| Meccanismo | File | Ruolo oggi |
|------------|------|------------|
| nwidart `ModuleManifest` | `module.json` | **Boot effettivo** in questo monorepo |
| Laravel Package Discovery | `composer.json` | Package Composer autoconsistente; rilevante standalone / path-repo |

**Convenzione esplicita utente:** non ottimizzare via l'entry in `composer.json` perché "qui non la legge nessuno". Entrambi i file vanno sempre allineati.

## Pattern da studiare (WorkOrder)

`module.json`:

```json
"providers": [
    "Modules\\WorkOrder\\Providers\\WorkOrderServiceProvider",
    "Modules\\WorkOrder\\Providers\\Filament\\AdminPanelProvider"
]
```

`composer.json` → `extra.laravel.providers`: **stesso array**.

## Audit

```bash
bash bashscripts/tools/audit-module-providers-dual-registration.sh
bash bashscripts/tools/audit-module-providers-dual-registration.sh WorkOrder
bash bashscripts/tools/audit-module-provider-manifest.sh WorkOrder
bash bashscripts/tools/audit-module-admin-panel-provider.sh
```

Template: [module-providers-dual-registration.stub.json](../templates/module-providers-dual-registration.stub.json)

## Collegamento trinità panel

I 2 provider sono il **lato boot** della trinità Filament. Completano:

- `config/config.php` — metadati panel
- `Dashboard.php` — landing panel

→ [module-filament-panel-triad.md](./module-filament-panel-triad.md)

## Incidente (2026-07-27)

- `TimberBilling/composer.json` — solo `ServiceProvider`, panel solo in `module.json`
- **7 moduli** con liste diverse: Document/Email/HR/Platform/WhatsApp (`Route`+`Event` solo in composer); Geo/User (`Livewire` solo in composer)
- Script audit aveva bug PHP (`{Module}` in stringa) — fix + helper `lib/audit-module-providers-dual-registration.php`

**Stato:** 38/38 moduli, liste identiche, minimo 2 provider.

## Non fare

- Aggiornare solo `module.json`
- `$this->app->register(ChildServiceProvider::class)` nel provider padre — usare manifest ([module-providers-manifest.md](../../../../../docs/wiki/rules/module-providers-manifest.md))
- Inventare provider extra "per sicurezza" — minimo 2; altri solo se il modulo ne ha davvero bisogno
