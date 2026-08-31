---
title: "Confronto composer root Application vs Forecast"
type: raw-note
module: Xot
created: 2026-06-30
updated: 2026-07-15
tags: [composer, nwidart, laravel-modules, application, forecast]
source:
  - /var/www/_bases/<nome repository>/laravel/composer.json
  - /var/www/_bases/<nome repository>/laravel/composer.json
---

# Confronto composer root Application vs Forecast

Application (`<nome repository>/laravel/composer.json`) e' il riferimento storico nwidart:

- `require`: `php`, `laravel/framework`, `nwidart/laravel-modules`
- merge solo `Modules/*/composer.json`
- autoload: `App\\` + `Database\\Seeders\\`

## Debito Application (non replicare in Forecast)

- dipendenze funzionali nel root (`livewire/livewire`, `spatie/laravel-permission`, `tallstackui/tallstackui`, `phpmd/phpmd`, `laravel/tinker`)
- `Modules\\` nell'autoload root
- merge di `Themes/*/composer.json`
- configurazione merge-plugin piu' ampia del necessario

## Stato Forecast (canonico 2026-06-30)

Root allineato e piu' stretto di Application:

- `require` solo tre package skeleton
- autoload solo `App\\` e `Tests\\`
- nessun merge `Themes/*/composer.json`
- temi/seeders: runtime PSR-4 Xot

Vedi [composer-root-skeleton-modular.md](../wiki/concepts/composer-root-skeleton-modular.md).
