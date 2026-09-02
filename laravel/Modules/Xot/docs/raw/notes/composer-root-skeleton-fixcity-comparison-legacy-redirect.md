---
title: "Composer Root Skeleton Fixcity Comparison"
type: concept
status: deprecated
module: "Xot"
created: 2026-07-14
created_at: '2026-07-14'
updated: 2026-07-14
qmd: "deprecated composer-root-skeleton-fixcity-comparison"
related:
  - "./composer-root-skeleton-fixcity-comparison.md"
---
# Composer Root Skeleton Fixcity Comparison

> Deprecated: non aggiungere date nel filename; usare `created/updated` nel front matter.

## Osservazione FixCity

FixCity (`base_fixcity_fila5/laravel/composer.json`) e' il riferimento storico nwidart:

- `require`: `php`, `laravel/framework`, `nwidart/laravel-modules`
- merge solo `Modules/*/composer.json`
- autoload: `App\\` + `Database\\Seeders\\`

## Debito FixCity (non replicare in Predict)

- `spatie/laravel-responsecache` nel root — gia' owner in `Modules/Xot`
- `phpmd/phpmd` in `require-dev` root — usare `.phar` standalone
- `Database\\Seeders\\` in autoload root — in Predict via `RegisterRuntimePsr4NamespacesAction`

## Stato Predict (canonico 2026-06-30)

Root allineato e piu' stretto di FixCity:

- `require` solo tre package skeleton
- autoload solo `App\\` e `Tests\\`
- nessun merge `Themes/*/composer.json`
- temi/seeders: runtime PSR-4 Xot

FixCity (`base_fixcity_fila5/laravel/composer.json`) e' il riferimento storico nwidart:

- `require`: `php`, `laravel/framework`, `nwidart/laravel-modules`
- merge solo `Modules/*/composer.json`
- autoload: `App\\` + `Database\\Seeders\\`

## Debito FixCity (non replicare in Predict)

- dipendenze funzionali nel root (`livewire/livewire`, `spatie/laravel-permission`, `tallstackui/tallstackui`, `phpmd/phpmd`, `laravel/tinker`);
- `Modules\\` nell'autoload root;
- merge di `Themes/*/composer.json`;
- configurazione merge-plugin piu' ampia del necessario.

## Regola dedotta

Il root deve essere lo skeleton Laravel. I moduli sono package Composer autonomi caricati da `nwidart/laravel-modules` e composti dal merge plugin. Quindi il root non deve possedere ne' autoloadare il codice dei moduli.

## Impatto su PHPStan

Il root `autoload.psr-4.Modules\\ = Modules/` amplia la scansione Composer a tutto l'albero dei moduli e aumenta ambiguita' PSR-4, classi duplicate e provider stale. La correzione e' togliere l'autoload root dei moduli e lasciare che ogni modulo esponga il proprio namespace dal proprio `composer.json`.
