---
title: "Theme Composer Boundary"
type: concept
theme: Sixteen
tags: [composer, theme, boundary, nwidart, laravel-modules]
created: 2026-06-30
updated: 2026-06-30
related:
  - ../../../../Modules/Xot/docs/wiki/concepts/composer-root-skeleton-modular.md
  - ../../../../Modules/Xot/docs/wiki/concepts/theme-psr4-autoload-without-merge.md
  - ../../../TwentyOne/docs/wiki/concepts/theme-composer-boundary.md
---

# Theme Composer Boundary

Sixteen e' un tema bridge-only: layout, parity visuale, asset pipeline e shell frontoffice.

## Regola

- Root `laravel/composer.json`: skeleton Laravel + `nwidart/laravel-modules`.
- Moduli: dipendenze funzionali e autoload `Modules\\*\\` via merge-plugin.
- Temi: nessun merge root di `Themes/*/composer.json`.
- Temi: nessun PSR-4 nel root `autoload` o `autoload-dev`.

## Motivo

Il merge root dei temi duplica Filament e Spatie gia' dichiarati in `Modules/Xot`, e rompe la boundary tema/modulo.

Anche l'autoload root dei temi rompe la stessa boundary: il root deve restare skeleton e contenere solo `App\\` e `Tests\\`. Se una classe tema non risolve, va rivista l'ownership tra tema e modulo, non aggiunto mapping nel root.

- [theme-psr4-autoload-without-merge](../../../../Modules/Xot/docs/wiki/concepts/theme-psr4-autoload-without-merge.md)

## Boundary operativa

- Views e asset: `Themes/Sixteen/resources/`
- Namespace Blade pubblico: `pub_theme` registrato da `Modules/Cms`
- Logica dominio: `Modules/*/app/Filament/Widgets/`, mai nel tema
