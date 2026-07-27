---
title: trinità panel filament per modulo
type: concept
module: Xot
tags: [filament, panel, module, convention, dashboard, provider, config]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-providers-dual-registration-mandatory.md
  - ./module-config-php-religion.md
  - ./module-admin-panel-provider-mandatory.md
  - ./module-dashboard-page-mandatory.md
  - ../../../../Themes/docs/shared-components/runtime-config-religion-hub.md
---

# Trinità panel Filament — tre artefatti inseparabili

Ogni modulo nwidart con UI backoffice espone un panel su `/{modulo}/admin`. Funziona solo se **tutti e tre** gli artefatti sono presenti e collegati.

| # | Artefatto | Ruolo | Registrazione |
|---|-----------|--------|----------------|
| 1 | `config/config.php` | `name`, `icon`, `navigation` per menu/panel | `XotBaseServiceProvider::registerConfig()` |
| 2 | `app/Providers/Filament/AdminPanelProvider.php` | Crea panel + `discover*()` su Resources/Pages/Widgets | **`module.json` + `composer.json`** (stessi 2 provider) |
| 3 | `app/Filament/Pages/Dashboard.php` | Landing `GET /{modulo}/admin` | Scoperta da `discoverPages()` |

## Catena runtime

```
module.json + composer.json providers[]  (minimo 2: ServiceProvider + AdminPanelProvider)
  → AdminPanelProvider::panel()
    → path: {modulo}/admin
    → discoverPages() → Dashboard extends XotBaseDashboard
    → discoverResources() / discoverWidgets()
  → PanelMixin legge config/config.php per label e icona navigazione
```

## Errori silenziosi tipici

| Sintomo | Causa probabile |
|---------|-----------------|
| Nessuna route `/billing/admin` | `AdminPanelProvider` assente o **non** in `module.json` |
| 404 su `/billing/admin`, Resource OK | Manca `Dashboard.php` |
| Panel senza nome/icona | Manca o incompleto `config/config.php` |
| Panel bootato solo in un contesto | `AdminPanelProvider` in un solo manifest | 
| File provider presente ma panel morto | Solo file, **zero** voce in `module.json` (caso Catalog 2026-07-27) |

## Audit (tutti e tre)

```bash
bash bashscripts/tools/audit-module-providers-dual-registration.sh
bash bashscripts/tools/audit-module-config-php.sh
bash bashscripts/tools/audit-module-admin-panel-provider.sh
bash bashscripts/tools/audit-module-dashboard-page.sh
bash bashscripts/tools/guard-nwidart-module-skeleton.sh
```

## Nuovo modulo — ordine

1. `module.json` + `composer.json` con 2 provider allineati + `{Module}ServiceProvider`
2. `config/config.php` (stub `module-config-php.stub.php`)
3. `AdminPanelProvider.php` (stub `module-admin-panel-provider.stub.php`)
4. `Dashboard.php` vuoto `extends XotBaseDashboard`
5. `modules_statuses.json` root + tenant

## Temi

I temi **non** hanno `AdminPanelProvider`. Consumano FO; il BO resta nei moduli.

→ [module-admin-panel-provider-religion.md](../../../../Themes/docs/shared-components/module-admin-panel-provider-religion.md)
