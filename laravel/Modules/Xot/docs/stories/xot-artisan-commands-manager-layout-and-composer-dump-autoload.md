---
id: story-xot-artisan-commands-manager-layout-and-composer-dump-autoload
slug: story-xot-artisan-commands-manager-layout-and-composer-dump-autoload
title: "STORY — ArtisanCommandsManager: pulsanti che escono dallo schermo, e pulsante Composer Dump Autoload"
description: "Emerso durante il ripristino del servizio invii: un job in coda falliva con 'Job is incomplete class' dopo il deploy di due nuove classi Exception — sintomo tipico di un autoloader Composer ottimizzato mai rigenerato. Nessun modo per lanciare composer dump-autoload senza SSH. Nell'usare la pagina ArtisanCommandsManager per verificare, notato anche un problema di layout: con 8 azioni nell'header, i pulsanti escono dallo schermo invece di andare a capo."
document_type: story
category: bmad
scope: module:Xot
github_id: module_xot_fila5#97
status: review
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
created_at: '2026-09-03'
updated_at: '2026-09-03'
tags: [bmad, story, xot, admin, ui, composer, autoloader, invii]
related:
  - ../../laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php
  - ../../laravel/Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php
  - ../../laravel/Modules/Xot/app/Providers/Filament/AdminPanelProvider.php
  - ../../laravel/Modules/Quaeris/docs/stories/quaeris-bulk-invite-job-resilience.md
github:
  repository: https://github.com/laraxot/module_xot_fila5
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# STORY — ArtisanCommandsManager: layout e Composer Dump Autoload

## Contesto

Verifica finale del ripristino servizio invii: un job (`BulkUpdateContactTokensJob`)
in `failed_jobs` con `Exception: Job is incomplete class` — sintomo tipico di
Composer con autoloader ottimizzato (classmap statica) mai rigenerato dopo
un deploy che ha aggiunto classi nuove (`LimesurveySessionException`,
`BulkInviteFailedException`, aggiunte oggi). `ExecuteArtisanCommandAction`
esegue solo comandi `php artisan ...`, non può lanciare `composer
dump-autoload` (comando Composer, non artisan). L'amministratore non ha
SSH.

Durante la verifica, aperta `ArtisanCommandsManager` (dopo aver scoperto
che l'account non aveva il ruolo `xot::admin` necessario per accedere al
pannello — vedi Dev Notes): con 8 azioni nell'header, i pulsanti escono
dallo schermo invece di andare a capo — nessun tema Vite registrato per
questo pannello, un `.fi-ac` di Filament non va a capo di default.

## Problema

1. Nessun modo, senza SSH, di rigenerare l'autoloader Composer dopo un
   deploy che aggiunge classi — blocca silenziosamente qualunque job che
   referenzia una classe nuova.
2. `ArtisanCommandsManager` con molte azioni nell'header non è utilizzabile
   comodamente — i pulsanti a destra sono tagliati fuori dallo schermo.

## Solution Overview

1. Nuova azione "Composer Dump Autoload" su `ArtisanCommandsManager.php`,
   che esegue `composer dump-autoload` — non tramite
   `ExecuteArtisanCommandAction` (limitato a `php artisan`), ma con lo
   stesso pattern sicuro (comando fisso, nessuna interpolazione, via
   `Process`), in una nuova Action dedicata o estendendo il meccanismo
   esistente per accettare anche questo singolo comando.
2. Un piccolo file CSS statico (nessun tema Vite necessario, il pannello
   "xot" non ne ha uno) che fa andare a capo i pulsanti dell'header
   invece di farli uscire dallo schermo, registrato via `FilamentAsset::
   register()` nel provider del pannello "xot".

## Acceptance Criteria

- AC1: un'azione "Composer Dump Autoload" compare su
  `ArtisanCommandsManager`, eseguibile solo da chi ha accesso al pannello
  (già filtrato da `canAccessPanel()`/ruolo `xot::admin`).
- AC2: l'azione esegue `composer dump-autoload` per davvero (verificabile:
  un job precedentemente fallito con "Job is incomplete class" per una
  classe realmente esistente su disco, dopo l'azione, viene rieseguito
  con successo).
- AC3: nessun comando arbitrario eseguibile — un solo comando fisso,
  nessuna interpolazione di input utente in una stringa di shell.
- AC4: con 8+ azioni nell'header, tutte restano visibili e cliccabili
  direttamente (vanno a capo su più righe, non esce nulla dallo schermo).
- AC5: PHPStan pulito sui file toccati.

## Tasks/Subtasks

- [x] Task 1: nuova `ExecuteComposerDumpAutoloadAction` (comando fisso,
      nessun input utente, stesso pattern di `ExecuteArtisanCommandAction`)
- [x] Task 2: nuovo pulsante header "Composer Dump Autoload" su
      `ArtisanCommandsManager.php`, con `requiresConfirmation()`
- [x] Task 3: CSS statico (`public_html/assets/xot/header-actions-wrap.css`)
      per il wrap dei pulsanti, registrato in `AdminPanelProvider.php`
      (pannello xot) via `FilamentAsset::register()` — nessun tema Vite
      creato, il file e' servito staticamente
- [ ] Task 4: verifica manuale — rilancio del job fallito per "Job is
      incomplete class" dopo l'azione, deve completare con successo

## Dev Notes

- Scoperto in questa story: l'account admin non aveva il ruolo
  `xot::admin` necessario per accedere a questo pannello —
  `BaseUser::canAccessPanel()` richiede un ruolo con lo stesso nome
  dell'id del pannello (`{modulo}::admin`), `super-admin` da solo non
  basta. Risolto assegnando il ruolo mancante, non oggetto di questa
  story (azione una tantum sull'account, non un cambiamento di codice).
- Lock prima di ogni edit: `bash bashscripts/lock/lock.sh <path> <task-id> <agent-id>`.

### References

- [Source: laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php]
- [Source: laravel/Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php]
  perché non è la via giusta per un comando non-artisan
- [Source: laravel/Modules/User/app/Models/BaseUser.php#L317-L332]
  `canAccessPanel()`, il meccanismo ruolo-per-pannello scoperto oggi
- [Source: laravel/vendor/filament/support/resources/views/components/actions.blade.php#L38-L46]
  `.fi-ac`, il contenitore che non va a capo di default

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- Story creata 2026-09-03 durante il ripristino del servizio invii
  automatici — un job fallito per un problema di autoloader Composer ha
  reso necessario un modo di rigenerarlo senza SSH.
- **Implementazione 2026-09-03**: Task 1-3 completati. PHPStan pulito.
  Scoperto durante l'accesso alla pagina che l'account admin non aveva
  il ruolo `xot::admin` richiesto da `BaseUser::canAccessPanel()` (un
  ruolo per pannello, `super-admin` da solo non basta) — risolto
  dall'utente assegnando il ruolo, non un cambiamento di codice. Task 4
  (verifica manuale sul job realmente fallito) resta da fare.

### File List

- `laravel/Modules/Xot/app/Actions/ExecuteComposerDumpAutoloadAction.php` (nuovo)
- `laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php` (modificato)
- `laravel/Modules/Xot/app/Providers/Filament/AdminPanelProvider.php` (modificato)
- `laravel/Modules/Xot/lang/it/artisan-commands-manager.php` (modificato)
- `laravel/Modules/Xot/lang/en/artisan-commands-manager.php` (modificato)
- `public_html/assets/xot/header-actions-wrap.css` (nuovo)

## GitHub (tracciamento)

| Risorsa | Stato | Link |
|---|---|---|
| Issue (modulo) | aperta | https://github.com/laraxot/module_xot_fila5/issues/97 |
| Issue (root, mirror) | aperta | https://github.com/laraxot/base_quaeris_fila5/issues/183 |
