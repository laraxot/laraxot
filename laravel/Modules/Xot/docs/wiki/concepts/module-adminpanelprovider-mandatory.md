---
title: "Ogni modulo richiede app/Providers/Filament/AdminPanelProvider.php"
type: concept
module: Xot
tags: [filament, panel, provider, discoverResources, module-convention]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-dashboard-page-mandatory.md
  - ./module-config-config-php.md
  - ../../../../app/Providers/Filament/XotBasePanelProvider.php
---

# `app/Providers/Filament/AdminPanelProvider.php` — panel Filament del modulo

## Perché (verificato in `Modules\Xot\Providers\Filament\XotBasePanelProvider::panel()`)

Ogni modulo con `AdminPanelProvider extends XotBasePanelProvider` registra un **panel
Filament indipendente** su `{modulo_lower}/admin`. `XotBasePanelProvider::panel()` fa,
tra l'altro:

```php
->id($moduleLow.'::admin')
->path($moduleLow.'/admin')
->discoverResources(base_path('Modules/'.$this->module.'/app/Filament/Resources'), ...)
->discoverPages(base_path('Modules/'.$this->module.'/app/Filament/Pages'), ...)
->discoverWidgets(base_path('Modules/'.$this->module.'/app/Filament/Widgets'), ...)
->discoverClusters(base_path('Modules/'.$this->module.'/app/Filament/Clusters'), ...)
```

**Senza `AdminPanelProvider`, nessuno di questi `discover*()` viene mai chiamato per
quel modulo.** Non è solo la dashboard a mancare (vedi
[module-dashboard-page-mandatory.md](./module-dashboard-page-mandatory.md)): l'intera
UI Filament del modulo — Resource, Page, Widget, Cluster, comunque scritte e corrette
nel codice — resta **invisibile e irraggiungibile**, perché Filament non sa che
quella cartella esiste. Modelli, migration, Action possono essere perfetti e il
modulo comunque non avere alcuna interfaccia admin funzionante.

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

- **Mai** estendere `Filament\PanelProvider` direttamente — sempre `XotBasePanelProvider`
  (stessa regola generale: non estendere mai le classi Filament vendor, sempre il
  wrapper `XotBase*` — vedi `xotbase-migration-religion.md`,
  `module-dashboard-page-mandatory.md`).
- `$module` deve combaciare esattamente con il nome della cartella in `Modules/`
  (case-sensitive) — usato sia per `discoverResources()`/etc. sia per derivare
  `$moduleNamespace` via `getModuleNamespace()`.
- **Deve** essere registrato in `module.json` → `providers` (altrimenti la classe
  esiste ma non viene mai istanziata da Laravel):
  ```json
  "providers": [
      "Modules\\{Nome}\\Providers\\{Nome}ServiceProvider",
      "Modules\\{Nome}\\Providers\\Filament\\AdminPanelProvider"
  ]
  ```

## Audit

```bash
for d in laravel/Modules/*/; do
  m=$(basename "$d")
  [ -f "$d/module.json" ] || continue
  [ -f "$d/app/Providers/Filament/AdminPanelProvider.php" ] || echo "MISSING: $m"
  grep -q "Providers\\\\\\\\Filament\\\\\\\\AdminPanelProvider" "$d/module.json" 2>/dev/null \
    || echo "NOT REGISTERED IN module.json: $m"
done
```

Verificare anche via bootstrap reale, non solo grep statico (un `class_exists()` senza
autoload dell'app dà falsi negativi):

```bash
php artisan tinker --execute="
\$j = json_decode(file_get_contents('Modules/{Nome}/module.json'), true);
foreach (\$j['providers'] as \$p) { echo \$p.' => '.(class_exists(\$p)?'YES':'NO').PHP_EOL; }
"
```

## Incidente (2026-07-27)

Diversi moduli portati da gestionale_commesse (`TimberBilling`, `Compliance`,
`PublicProcurement`, `Billing`, tra gli altri) non avevano affatto
`AdminPanelProvider.php` — non solo mancava la dashboard, l'intero pannello admin del
modulo non esisteva come route. Creati tutti con il pattern minimo sopra e registrati
in `module.json`. Falso allarme durante la verifica: leggendo il JSON attraverso lo
strumento di editing, i backslash di escape apparivano raddoppiati rispetto al file
reale — **verificare sempre i byte grezzi** (`cat -A file.json`) o la risoluzione
reale a runtime (`class_exists()` con app bootstrappata) prima di concludere che una
stringa di escaping JSON sia malformata.
