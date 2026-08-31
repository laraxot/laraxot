---
title: "Confronto composer root Application vs Forecast"
type: raw-note
module: Xot
created: 2026-06-30
tags: [composer, nwidart, laravel-modules, application, forecast]
source:
  - /var/www/_bases/<nome repository>/laravel/composer.json
  - /var/www/_bases/<nome repository>/laravel/composer.json
---

# Confronto composer root Application vs Forecast

## Osservazione Application

Application (`<nome repository>/laravel/composer.json`) e' il riferimento storico nwidart:

- `require`: `php`, `laravel/framework`, `nwidart/laravel-modules`
- merge solo `Modules/*/composer.json`
- autoload: `App\\` + `Database\\Seeders\\`

## Debito Application (non replicare in Forecast)

- `spatie/laravel-responsecache` nel root — gia' owner in `Modules/Xot`
- `phpmd/phpmd` in `require-dev` root — usare `.phar` standalone
- `Database\\Seeders\\` in autoload root — in Forecast via `RegisterRuntimePsr4NamespacesAction`

## Stato Forecast (canonico 2026-06-30)

Root allineato e piu' stretto di Application:

- `require` solo tre package skeleton
- autoload solo `App\\` e `Tests\\`
- nessun merge `Themes/*/composer.json`
- temi/seeders: runtime PSR-4 Xot

## Regola dedotta

Il root deve essere lo skeleton Laravel. I moduli sono package Composer autonomi caricati da `nwidart/laravel-modules` e composti dal merge plugin. Quindi il root non deve possedere ne' autoloadare il codice dei moduli.

## Impatto su PHPStan

Il root `autoload.psr-4.Modules\\ = Modules/` amplia la scansione Composer a tutto l'albero dei moduli e aumenta ambiguita' PSR-4, classi duplicate e provider stale. La correzione e' togliere l'autoload root dei moduli e lasciare che ogni modulo esponga il proprio namespace dal proprio `composer.json`.
