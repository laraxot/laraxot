---
title: module config/config.php convention
type: concept
module: Xot
tags: [module, config, filament, panel, mandatory]
updated: 2026-07-27
related:
  - ../panel-mixin-extension-pattern.md
  - ../../../../../docs/wiki/concepts/module-config-config-php.md
---

# `config/config.php` — obbligatorio per ogni modulo

## Perché

Ogni modulo Nwidart deve esporre metadati Filament/panel e configurazione modulo in un unico file:

`Modules/{Module}/config/config.php`

Caricato da `XotBaseServiceProvider::registerConfig()` come `config('{alias}.config')` e letto da `PanelMixin::getModuleConfig()` per label, icona e sort.

## Campi minimi (contratto)

```php
<?php

declare(strict_types=1);

return [
    'name' => 'WorkOrder',           // obbligatorio — PanelMixin::getNavigationLabel()
    'description' => '…',            // consigliato — docs e discoverability
    'icon' => 'heroicon-o-…',        // obbligatorio — PanelMixin::getNavigationIcon()
    'navigation' => [
        'enabled' => true,
        'sort' => 110,               // ordine pannelli (module.json priority come guida)
    ],
];
```

Chiavi dominio aggiuntive (es. Geo `api_keys`, TimberBilling `invoice_kinds`) **dopo** il blocco metadati — stesso file, non file separati obbligatori.

## Regole

| ✅ | ❌ |
|----|-----|
| Un file `config/config.php` per modulo | Modulo senza `config/config.php` |
| `name` + `icon` sempre presenti | Solo chiavi tecniche senza metadati panel |
| `declare(strict_types=1)` | Config senza strict types |
| Estendere il file esistente | Secondo `config.php` duplicato altrove |

## Verifica

```bash
for m in laravel/Modules/*/; do
  f="${m}config/config.php"
  [ -f "$f" ] && grep -q "'name'" "$f" && grep -q "'icon'" "$f" || echo "FAIL: $f"
done
```

## Storico (2026-07-27)

Audit completo eseguito: 21 moduli reali (con `module.json`) su 42 directory sotto `Modules/`
erano privi di `config/config.php` (`AiAssistant, Billing, Bom, Catalog, Compliance, Customer,
Document, Email, EnergyBroker, Fiscal, HR, Intervention, Inventory, Platform, Production,
PublicProcurement, Quotation, Signature, Vehicle, WhatsApp, WorkOrder`). Creati tutti e 21 con
`name`/`description` derivati da `module.json` (`jq`), `icon` generico, `navigation.sort` =
`module.json.priority`. Esclusi dall'audit: `Blog`, `Comment`, `TestModule` (nessun `module.json`
— non moduli nwidart attivi in questa fase) e `Modules/docs` (cartella di documentazione, non un
modulo). Verificato con lo script sopra: 0 FAIL. Verificato a runtime:
`config('{alias}.config.name')`/`.icon` risolvono correttamente per i nuovi file.

## Riferimenti

- `Modules/Xot/app/Mixins/PanelMixin.php`
- `Modules/Xot/app/Providers/XotBaseServiceProvider.php` → `registerConfig()`
- [Tenant/session-learnings-modules-config.md](../../../Tenant/docs/session-learnings-modules-config.md) — checklist nuovo modulo (JSON + config.php + Dashboard)
- [module-dashboard-page-mandatory.md](./module-dashboard-page-mandatory.md) — `app/Filament/Pages/Dashboard.php`
