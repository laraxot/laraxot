---
<<<<<<< HEAD
<<<<<<< HEAD
title: "notify-restore-2026-04-21.deprecated"
=======
title: "notify-restore-2026-04-21"
>>>>>>> a988596b (first)
type: concept
tags: [deprecated]
created: 2026-07-14
updated: 2026-07-14
<<<<<<< HEAD
qmd: "notify-restore-2026-04-21.deprecated deprecated"
=======
qmd: "notify-restore-2026-04-21 deprecated"
>>>>>>> a988596b (first)
status: deprecated
related:
  - "./agents.md"
  - "./bmad-method.md"
  - "./index.md"
  - "./log.md"
  - "./notify-conflict-check-.md"
  - "./notify-conflict-check-1.md"
  - "./notify-conflict-check.md"
<<<<<<< HEAD
  - "./notify-restore-1.md"
---

> Questo file è stato rinominato in [notify-restore-.deprecated.md](notify-restore-.deprecated.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
=======
created_at: '2026-04-21'
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
- Non restano riferimenti `laraxot/user-module` o `Modules\\User\\` nel composer del modulo.

## Nota

Il modulo Notify diretto contiene ancora molti file non tracciati gia' presenti prima dell'intervento. Questo ripristino ha rimosso solo la Laravel app annidata errata e corretto il composer del modulo.
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
  - "./notify-restore-.md"
---

> Questo file è stato rinominato in [notify-restore.md](notify-restore.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
>>>>>>> a988596b (first)
