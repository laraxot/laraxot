---
title: "Moduli — icona config e SVG (temi)"
type: concept
tags: [theme, module, icon, svg, config, shared-components]
updated: 2026-07-27
related:
  - ../../Modules/Xot/docs/wiki/concepts/module-config-icon-svg.md
  - ./module-config-php-religion.md
---

# Icona modulo in navigazione Filament

I temi non definiscono le icone dei moduli, ma il menu admin (Sixteen FO + hub Xot) mostra l'icona da `Modules/{M}/config/config.php`.

## Regola

Se esiste `resources/svg/icon.svg`:

```php
'icon' => '{alias}-icon',  // alias da module.json, es. workorder-icon
```

Registrazione automatica: `XotBaseServiceProvider::registerBladeIcons()`.

## Placeholder da eliminare

`heroicon-o-square-3-stack-3d` → sostituire con `{alias}-icon` o heroicon semantico.

Audit: `bash bashscripts/tools/audit-module-config-icon-svg.sh`

Canon: [module-config-icon-svg.md](../../Modules/Xot/docs/wiki/concepts/module-config-icon-svg.md)
