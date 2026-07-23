---
title: "Ripristino struttura Notify - 2026-04-21"
type: concept
tags: [notify, restore, 2026, 21.deprecated]
created: 2026-07-14
updated: 2026-07-14
qmd: "notify-restore-2026-04-21.deprecated ripristino struttura notify - 2026-04-21"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./agents.md"
  - "./bmad-method.md"
  - "./index.md"
  - "./log.md"
  - "./notify-conflict-check-.md"
  - "./notify-conflict-check-1.md"
  - "./notify-conflict-check.md"
  - "./notify-restore-.md"
---

# Ripristino struttura Notify - 2026-04-21

## Problema

`laravel/Modules/Notify/laravel` conteneva una Laravel app completa annidata dentro il modulo Notify. La directory includeva `artisan`, `config`, `routes`, `storage`, `Themes`, `Modules` e copie di altri moduli. Questo contaminava scansioni, QMD, grep e validazioni statiche.

Inoltre `laravel/Modules/Notify/composer.json` descriveva erroneamente un modulo User/FixCity invece di Notify.

## Intervento

- Rimossa la directory annidata `laravel/Modules/Notify/laravel`.
- Ripristinato `laravel/Modules/Notify/composer.json` usando il composer corretto del modulo Notify.
- L'autoload ora punta a `Modules\\Notify\\` con path `app/`.

## Validazioni

- La directory annidata `laravel/Modules/Notify/laravel` non esiste piu' nel filesystem.
- `composer.json` e' JSON valido.
- PSR-4 contiene:
  - `Modules\\Notify\\` -> `app/`
  - `Modules\\Notify\\Database\\Factories\\` -> `database/factories/`
  - `Modules\\Notify\\Database\\Seeders\\` -> `database/seeders/`
- Non restano riferimenti `fixcity/user-module` o `Modules\\User\\` nel composer del modulo.

## Nota

Il modulo Notify diretto contiene ancora molti file non tracciati gia' presenti prima dell'intervento. Questo ripristino ha rimosso solo la Laravel app annidata errata e corretto il composer del modulo.
