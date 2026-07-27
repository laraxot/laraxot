---
title: "Ogni modulo con UI Filament richiede app/Providers/Filament/AdminPanelProvider.php"
type: concept
module: Xot
tags: [filament, panel, provider, module.json, module-convention]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-dashboard-page-mandatory.md
  - ./module-config-config-php.md
  - ./module-filament-panel-triad.md
  - ./module-providers-dual-registration-mandatory.md
---

# `app/Providers/Filament/AdminPanelProvider.php` — obbligatorio per ogni modulo

## Perché (verificato, non ipotizzato)

`AdminPanelProvider extends Modules\Xot\Providers\Filament\XotBasePanelProvider` è l'unica
classe che chiama `Panel::path("{modulo}/admin")` e i quattro `discover*()` (Resources,
Pages, Widgets, Clusters) per quel modulo. **Senza questa classe registrata, il modulo non
ha nessun panel Filament**: nessuna route `{modulo}/admin`, nessuna Resource/Page/Widget di
quel modulo viene scoperta e mostrata da nessuna parte, indipendentemente da quanti file
esistano fisicamente in `app/Filament/*`.

La classe da sola **non basta**: deve essere elencata in `providers` dentro `module.json`
del modulo — è quell'elenco che nwidart usa per registrare i service provider al boot
(`Illuminate\Support\ServiceProvider::register()`/`boot()`), non una scansione automatica
della cartella `app/Providers/Filament/`.

## Pattern minimo obbligatorio

```php
<?php

declare(strict_types=1);

namespace Modules\{Nome}\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = '{Nome}';
}
```

`module.json`:
```json
{
    "providers": [
        "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
        "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
    ]
}
```

- **Mai** estendere `Filament\PanelProvider` direttamente — sempre `XotBasePanelProvider`
  (stessa regola "mai estendere le classi Filament vendor" di
  `fundamental-xotbase-rule.md`).
- Attenzione all'escaping dei backslash quando si genera il JSON in modo programmatico:
  un namespace PHP con N segmenti richiede N-1 sequenze `\\` (doppio backslash) nel JSON
  grezzo — non quadruplo. Verificare sempre con `python3 -c "import json;
  print(json.load(open('module.json'))['providers'])"` (deve mostrare singolo backslash
  di separazione) prima di fidarsi di uno script di generazione.

## Audit

```bash
bash bashscripts/tools/audit-module-admin-panel-provider.sh
bash bashscripts/tools/guard-nwidart-module-skeleton.sh
```

Stub: [module-admin-panel-provider.stub.php](../templates/module-admin-panel-provider.stub.php)

Trinità con `config/config.php` e `Dashboard.php`: [module-filament-panel-triad.md](./module-filament-panel-triad.md)

## Verifica runtime (dopo ogni fix)

```bash
php artisan tinker --execute="
echo class_exists('Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider') ? 'class OK' : 'MISSING';
echo \Filament\Facades\Filament::getPanel('{nome_lower}::admin')->getPath();
"
```

## `composer.json` — OBBLIGATORIO quanto `module.json` (correzione esplicita dell'utente)

Una versione precedente di questa nota diceva che l'entry in `composer.json →
extra.laravel.providers` fosse "vestigiale, non richiesta" perché a runtime, in
*questo* setup monorepo, la registrazione passa solo da `module.json` →
`bootstrap/cache/modules.php`, non da `bootstrap/cache/packages.php` (Laravel Package
Discovery). Quella osservazione tecnica **era corretta ma la conclusione era sbagliata**:
l'utente ha richiesto esplicitamente che **entrambi** i file, `composer.json` e
`module.json`, elenchino sempre almeno i due provider (`{Nome}ServiceProvider` e
`Providers\Filament\AdminPanelProvider`) — non solo quello che il bootstrap attuale legge
davvero. Motivo: `composer.json` è ciò che rende il modulo un pacchetto Composer
autoconsistente (installabile/dichiarabile fuori da questo specifico monorepo, dove
Laravel Package Discovery leggerebbe *proprio* quella chiave), e la coerenza tra i due
file è essa stessa la convenzione, indipendentemente da quale meccanismo la legga in un
dato ambiente. **Non ottimizzare via l'entry "perché ridondante qui": tenerla è la
regola.**

```json
{
  "extra": {
    "laravel": {
      "providers": [
        "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
        "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
      ]
    }
  }
}
```

Verificato (2026-07-27): tutti e 38 i moduli reali hanno ora entrambi i provider in
entrambi i file.

## Incidente (2026-07-27)

15 moduli su 40 (batch porting gestionale_commesse + `Seo`/`TimberBilling`) erano privi sia
del file sia della voce in `module.json` — nessun panel, coerente con l'assenza di
`Dashboard.php` documentata in
[module-dashboard-page-mandatory.md](./module-dashboard-page-mandatory.md) trovata nello
stesso passaggio. Un sedicesimo caso (`Catalog`) aveva **il file presente ma non registrato**
in `module.json`: il panel non bootava comunque, nonostante Resources/Dashboard/Widgets
fossero già scritti — la lezione è che l'esistenza del file da sola non garantisce nulla,
va sempre verificata anche la registrazione. **Stato 2026-07-27:** 38/38 moduli conformi (audit script OK).
