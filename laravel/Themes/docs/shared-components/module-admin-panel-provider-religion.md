---
title: "Moduli Laraxot — app/Providers/Filament/AdminPanelProvider.php"
type: concept
tags: [theme, module, filament, panel, provider, cross-reference]
updated: 2026-07-27
related:
  - ../../../Modules/Xot/docs/wiki/concepts/module-admin-panel-provider-mandatory.md
  - ../../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md
  - ./module-providers-dual-registration-religion.md
  - ./module-dashboard-page-religion.md
  - ./module-config-php-religion.md
  - ./runtime-config-religion-hub.md
---

# app/Providers/Filament/AdminPanelProvider.php (moduli)

## Perché un tema deve conoscerlo

In Wave 1 l'operatore non usa un backoffice nel tema Sixteen: apre i **panel dei moduli**
(`/workorder/admin`, `/billing/admin`, …). Se un modulo ha Resource Filament ma **nessuna
route** `{modulo}/admin`, il problema non è il tema — è quasi sempre `AdminPanelProvider`
assente o non registrato in `module.json`.

## Cosa fa (meccanismo)

`AdminPanelProvider extends XotBasePanelProvider` è l'**unica** classe che, al boot:

| Azione | Effetto |
|--------|---------|
| `->path('{modulo}/admin')` | Route indice del panel BO |
| `->discoverResources()` | CRUD Filament del modulo |
| `->discoverPages()` | Pagine standalone (inclusa `Dashboard`) |
| `->discoverWidgets()` / `->discoverClusters()` | Widget e cluster del modulo |

Nwidart carica il provider **solo** se è in `module.json` → `providers[]`. Nessuna scansione
automatica di `app/Providers/Filament/`.

## Pattern minimo

```php
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'WorkOrder';
}
```

## Doppia registrazione (non opzionale)

Stessi provider in **`module.json`** e **`composer.json`** (`extra.laravel.providers`):

→ [module-providers-dual-registration-religion.md](./module-providers-dual-registration-religion.md)

## Trinità panel (checklist agente)

| # | Artefatto | Senza di esso |
|---|-----------|----------------|
| 1 | `config/config.php` | Panel senza nome/icona in menu |
| 2 | `AdminPanelProvider` registrato | **Nessun panel** `{modulo}/admin` |
| 3 | `Dashboard.php` | 404 sulla home del panel |

SSoT unificato: [module-filament-panel-triad.md](../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md)

I **temi** non hanno `AdminPanelProvider` — solo i moduli.

## Audit

```bash
bash bashscripts/tools/audit-module-admin-panel-provider.sh
```

Canon tecnico: [module-admin-panel-provider-mandatory.md](../../Modules/Xot/docs/wiki/concepts/module-admin-panel-provider-mandatory.md)
