---
title: "config/config.php — icona modulo (SVG custom)"
type: concept
module: Xot
tags: [module, config, icon, svg, filament, blade-ui-icons]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-config-php-religion.md
  - ./module-config-config-php.md
  - ../../../../Themes/docs/shared-components/module-config-icon-svg-religion.md
---

# Icona panel — `resources/svg/icon.svg` + `{alias}-icon`

## Verificato nel codice

`XotBaseServiceProvider::registerBladeIcons()` registra `Modules/{M}/resources/svg/` con prefix = **`module.json` → `alias`** (lowercase):

```php
$factory->add($this->nameLower, ['path' => $svgPath, 'prefix' => $this->nameLower]);
```

Filament legge la chiave grezza da `config/config.php` → `GetModulesNavigationItems` → `->icon($icon)`.

**Test senza namespace (448 file):** il generatore aggiunge blocco `namespace { … }` con stub `expect()` → `PestExpectation` (2026-07-27).

Naming Blade UI Icons: **`{alias}-{filename-senza-estensione}`**.

| File | `alias` module.json | Valore in `config.php` |
|------|---------------------|-------------------------|
| `resources/svg/icon.svg` | `workorder` | `'icon' => 'workorder-icon'` |
| `resources/svg/employee-icon2.svg` | `employee` | `'icon' => 'employee-icon2'` (override consapevole) |

**Nota:** `{alias}-icon` e `{alias}-{alias}-icon` possono entrambi risolvere a seconda del filename; convenzione Laraxot: file canon **`icon.svg`** → config **`{alias}-icon`**.

## Placeholder vietato

`'icon' => 'heroicon-o-square-3-stack-3d'` era stub generico porting gestionale — sostituire con SVG modulo o heroicon semantico.

Preferenza: **`resources/svg/icon.svg`** + `{alias}-icon` (brand coerente, stesso stile Activity/WorkOrder).

## Heroicon vs SVG modulo

| Approccio | Quando |
|-----------|--------|
| `heroicon-o-*` | Accettabile se semanticamente corretto e **non** esiste `icon.svg` |
| `{alias}-icon` | **Obbligatorio** se esiste `resources/svg/icon.svg` |

## Trappola silenziosa: `resources/assets/` deve esistere fisicamente

`registerBladeIcons()` deriva il path SVG così:

```php
$assetsPath = app(GetModulePathByGeneratorAction::class)->execute($this->name, 'assets');
// => Modules/{M}/resources/assets
$svgPath = $assetsPath.'/../svg';
// => Modules/{M}/resources/assets/../svg
if (! File::exists($svgPath)) { return; } // silenzioso, nessun errore/log
```

Su Linux, risolvere `a/../b` richiede che `a` esista fisicamente come directory
traversabile — se `Modules/{M}/resources/assets/` **non esiste** (anche se `resources/svg/`
esiste), `File::exists($svgPath)` ritorna `false` e la registrazione dell'intero prefix
per quel modulo viene **saltata senza errore, senza log, senza eccezione**. Il risultato:
`config/config.php` dice `'icon' => '{alias}-icon'`, il file `resources/svg/icon.svg`
esiste, ma Filament mostra un'icona rotta/vuota — nessun segnale di debug ovvio, va
scoperto testando `app(\BladeUI\Icons\Factory::class)->svg('{alias}-icon')` a runtime.

**Fix**: ogni modulo con `resources/svg/` deve avere anche `resources/assets/` (anche solo
con un `.gitkeep`, non serve contenuto):

```bash
mkdir -p Modules/{Modulo}/resources/assets && touch Modules/{Modulo}/resources/assets/.gitkeep
```

**Incidente reale (2026-07-27)**: 11 dei 21 moduli con icona SVG appena assegnata
(Bom, Compliance, Email, EnergyBroker, Fiscal, Inventory, Platform, Production,
PublicProcurement, WhatsApp, AiAssistant) avevano `resources/svg/icon.svg` corretto ma
NON `resources/assets/` — icona silenziosamente non registrata. Verificato con
`Factory::svg('{alias}-icon')` diretto in tinker (unico modo affidabile, il file esiste
fisicamente quindi grep/audit statico sul filesystem non basta a rilevarlo — serve
verificare anche l'esistenza di `resources/assets/`). Corretto creando la cartella
mancante per tutti e 11; ri-testato, 21/21 OK.

## Audit / sync

```bash
bash bashscripts/tools/audit-module-config-icon-svg.sh
bash bashscripts/tools/sync-module-config-icon-svg.sh          # sostituisce heroicon/fas/placeholder
bash bashscripts/tools/sync-module-config-icon-svg.sh --dry-run WorkOrder
```

Non sovrascrive icone custom (`employee-icon2`, ecc.).

## Template SVG

Minimal (Filament-safe):

```xml
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
     stroke-width="1.5" stroke="currentColor" role="img" aria-label="module-name">
  <path stroke-linecap="round" stroke-linejoin="round" d="..."/>
</svg>
```

Esempi canon: `Modules/WorkOrder/resources/svg/icon.svg`, `Modules/Billing/resources/svg/icon.svg`.
