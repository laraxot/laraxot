---
title: "Ogni modulo con panel Filament richiede app/Filament/Pages/Dashboard.php"
type: concept
module: Xot
tags: [filament, panel, dashboard, discoverPages, module-convention]
created: 2026-07-27
updated: 2026-07-27
related:
  - ../../../../../docs/wiki/concepts/tenant-config-directory-sacred.md
  - ../../../User/docs/wiki/concepts/spatie-permission-table-names.md
  - ./module-config-config-php.md
---

# `app/Filament/Pages/Dashboard.php` — pagina landing obbligatoria del panel

## Perché (verificato in `Modules\Xot\Providers\Filament\XotBasePanelProvider::panel()`)

Ogni modulo con un proprio `AdminPanelProvider extends XotBasePanelProvider` registra un
panel Filament indipendente su path `{modulo_lower}/admin`:

```php
->id($moduleLow.'::admin')
->path($moduleLow.'/admin')
->discoverPages(
    base_path('Modules/'.$this->module.'/app/Filament/Pages'),
    sprintf('%s\\Filament\\Pages', $moduleNamespace),
)
```

`discoverPages()` registra **solo** le classi Page fisicamente presenti in quella cartella.
Filament risolve la **route indice** del panel (`GET /{modulo}/admin`) sulla prima Page che
estende `Filament\Pages\Dashboard`. Se nessuna classe lo fa:

- l'operatore che apre `{modulo}/admin` ottiene 404 / pagina vuota;
- le Resource (`{modulo}/admin/work-orders`, ecc.) funzionano ancora, mascherando il bug;
- il menu tenant può mostrare il modulo abilitato pur con home panel rotta.

`XotBaseDashboard` centralizza colonne/widget default del progetto — stessa filosofia
`XotBaseResource` / `XotBaseWidget`: mai estendere Filament vendor direttamente.

## Trinità panel modulo (checklist unica)

| Artefatto | Ruolo |
|-----------|--------|
| `config/config.php` | Label, icona, sort navigazione |
| `app/Providers/Filament/AdminPanelProvider.php` | Crea panel + `discover*()` |
| `app/Filament/Pages/Dashboard.php` | Landing `{modulo}/admin` |

Vedi anche [module-admin-panel-provider-mandatory.md](./module-admin-panel-provider-mandatory.md) e [module-config-php-religion.md](./module-config-php-religion.md).

## Pattern minimo obbligatorio

```php
<?php

declare(strict_types=1);

namespace Modules\{Nome}\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBaseDashboard;

class Dashboard extends XotBaseDashboard
{
}
```

- **Mai** estendere `Filament\Pages\Dashboard` direttamente — sempre `XotBaseDashboard`
  (stessa regola di `xotbase-migration-religion.md`/`fundamental-xotbase-rule.md`: non
  estendere mai le classi Filament vendor, sempre il wrapper `XotBase*`).
- Nessuna proprietà/metodo da aggiungere finché non serve una dashboard custom
  (widget specifici, `getColumns()` diverso, ecc.) — il minimo vuoto è sufficiente e
  corretto.

## Quando NON serve (per ora)

Un modulo senza `app/Providers/Filament/AdminPanelProvider.php` non ha un panel proprio
— le sue eventuali Resource vengono scoperte dal panel di un altro modulo (o non hanno
ancora UI Filament). In quel caso `Dashboard.php` non ha effetto runtime immediato, ma va
comunque creato per coerenza forward-looking: quando il modulo riceverà il proprio
`AdminPanelProvider`, la pagina landing è già pronta e non richiede un nuovo giro di
audit.

## Audit

```bash
bash bashscripts/tools/audit-module-dashboard-page.sh
bash bashscripts/tools/audit-module-dashboard-page.sh WorkOrder
```

Oppure one-liner:

```bash
for d in laravel/Modules/*/; do
  m=$(basename "$d")
  [ -f "$d/module.json" ] || continue
  [ -f "$d/app/Filament/Pages/Dashboard.php" ] || echo "MISSING: $m"
done
```

## Incidente (2026-07-27)

23 moduli importati da gestionale_commesse + `Seo`/`TimberBilling` senza `Dashboard.php`.
Creati tutti con pattern minimo `extends XotBaseDashboard`. **38/38** moduli con `module.json` ora conformi.

**Eccezione corretta:** `AI` usava `XotBasePage` + view blade vuota — riallineato a `XotBaseDashboard` (widget custom solo via `getWidgets()`, come Employee).

Solo 8 moduli avevano già `AdminPanelProvider` attivo al momento del fix; gli altri 15 non mostravano errore finché il panel non veniva aperto su `{modulo}/admin`.
