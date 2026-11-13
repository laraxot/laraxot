<<<<<<< HEAD
---
title: "Risoluzione conflitto composer.json (Xot)"
type: note
status: unverified
created: 2026-09-24
updated: 2026-10-20
module: Xot
tags: [xot, docs]
qmd: "Risoluzione conflitto composer.json (Xot)"
---
# Risoluzione conflitto composer.json (Xot)

## Intent
- Garantire coerenza delle dipendenze e corretta configurazione dell’autoload per il modulo Xot.

## Cosa
- Rimozione dei marker di conflitto in `composer.json`.

- Rimozione dei marker di conflitto (``, `=======`, `aurmich/dev`) in `composer.json`.
- Eliminazione dell’inserimento errato del branch alias `aurmich/dev` nelle sezioni `require-comment` e `require-dev-comment`, che non fanno parte della configurazione delle dipendenze.
- Conservazione delle sezioni `require` e `require-dev` pulite, mantenendo solo le dipendenze ufficiali documentate nel modulo Xot.

- Rimozione dei marker di conflitto in `composer.json`.
- Rimozione dei marker di conflitto in `composer.json`.
- Rimozione dei marker di conflitto (``, `=======`, `aurmich/dev`) in `composer.json`.
- Eliminazione dell’inserimento errato del branch alias `aurmich/dev` nelle sezioni `require-comment` e `require-dev-comment`, che non fanno parte della configurazione delle dipendenze.
- Conservazione delle sezioni `require` e `require-dev` pulite, mantenendo solo le dipendenze ufficiali documentate nel modulo Xot.
- Conservazione della versione di `filament/filament`: `"^3.3"`.

## Collegamento alla doc root
Vedi `/docs/xot_conflict_links.md` per la mappatura dei file documentati localmente e i riferimenti incrociati.
=======
# Risoluzione conflitti Composer (Xot)

## Scopo
- Garantire coerenza delle dipendenze del modulo Xot e dell’ecosistema Filament/Livewire.

## Decisioni
- Allineamento di `filament/filament` a `^5.0`.
- Allineamento di `livewire/livewire` a `^4.0` per compatibilità con Filament v5.
- Rimozione di `pestphp/pest-plugin-livewire` se il progetto resta su PHP `^8.2`, perché le versioni 4.x richiedono PHP `^8.3` e Livewire `^4.0.1`.

## Collegamenti
- [Gestione dipendenze Composer](../../../../docs/composer.md)
>>>>>>> 77b1e2748 (first)
