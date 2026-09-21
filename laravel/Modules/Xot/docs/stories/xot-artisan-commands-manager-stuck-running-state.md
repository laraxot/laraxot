---
id: story-xot-artisan-commands-manager-stuck-running-state
slug: story-xot-artisan-commands-manager-stuck-running-state
title: "STORY — ArtisanCommandsManager/PassportDashboard: la UI resta bloccata su 'in esecuzione' anche quando il comando è andato a buon fine"
description: "Dopo aver svuotato mail_templates e cliccato 'Importa Vecchi Template Email/SMS', la tabella in locale risultava correttamente popolata ma la pagina restava bloccata su 'Il comando è in esecuzione... In attesa dell'output...' senza mai mostrare completamento. Stesso difetto, stessa causa, presente su TUTTI i bottoni di ArtisanCommandsManager e di PassportDashboard (Xot e User condividono ExecuteArtisanCommandAction)."
document_type: story
category: bmad
scope: module:Xot
github_id: module_xot_fila5#131
status: done
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
created_at: '2026-09-17'
updated_at: '2026-09-17'
tags: [bmad, story, xot, user, filament, livewire, admin, invii, go-live]
related:
  - ../../laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php
  - ../../laravel/Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php
  - ../../laravel/Modules/Xot/app/Actions/ExecuteComposerDumpAutoloadAction.php
  - ../../laravel/Modules/User/app/Filament/Clusters/Passport/Pages/PassportDashboard.php
  - ./xot-artisan-commands-manager-layout-and-composer-dump-autoload.md
github:
  repository: https://github.com/laraxot/module_xot_fila5
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# STORY — la UI dei comandi Artisan resta bloccata su "in esecuzione" a comando già completato

## Contesto

Dopo il ripristino/estensione di `ArtisanCommandsManager` (vedi
[xot-artisan-commands-manager-layout-and-composer-dump-autoload.md]), l'utente
ha svuotato `mail_templates` e cliccato "Importa Vecchi Template Email/SMS".
Verificando la tabella in locale, la migrazione era andata a buon fine (39
righe migrate) — ma a video la pagina restava bloccata su "Il comando è in
esecuzione. L'output apparirà in tempo reale." e "In attesa dell'output...",
senza mai mostrare la notifica di completamento né il badge "Completato".

## Causa radice

`ExecuteArtisanCommandAction::execute()` e `ExecuteComposerDumpAutoloadAction::execute()`
segnalavano l'avanzamento tramite `Illuminate\Support\Facades\Event::dispatch('artisan-command.*', [...])`
— il facade eventi **server-side** di Laravel. `ArtisanCommandsManager` e
`PassportDashboard` intercettavano quegli stessi nomi tramite
`Livewire\Attributes\On` / `protected $listeners` — il bus eventi **di
Livewire**, che ascolta solo eventi dispacciati da `$this->dispatch()` (lato
componente) o `Livewire.dispatch()` (lato browser/Alpine), mai eventi Laravel
lanciati con `Event::dispatch()`. I due sistemi condividono solo la
convenzione di nomi punteggiati, non il canale: **nessun listener riceveva mai
davvero questi eventi**, confermato leggendo `Modules\Xot\Providers\EventServiceProvider`
(`protected $listen = []`, nessuna classe listener registrata da nessuna
parte del progetto per questi nomi — unica occorrenza della stringa
`artisan-command.` in tutto `Modules/` erano proprio queste 4 classi).

Conseguenza: dato che `execute()` è comunque sincrona e bloccante (un
`while ($process->running())` dentro la stessa richiesta Livewire del click),
`$this->isRunning`/`$this->status`/`$this->output` restavano congelati ai
valori impostati a INIZIO metodo (`true`/`''`/`[]`) per l'intera durata della
richiesta — il ramo di successo non li aggiornava mai. Solo il blocco
`catch` (percorso eccezione) li reimpostava. Il `wire:poll.visible` già
presente in `artisan-commands-manager.blade.php` continuava a ripresentare
correttamente quello stesso stato bloccato, dando l'illusione di un comando
perennemente in corso anche quando l'esecuzione reale (e i suoi effetti sul
database) erano già completi da tempo.

Il difetto era presente **identico su entrambe le pagine** che riusano
`ExecuteArtisanCommandAction` (`ArtisanCommandsManager`: 10 bottoni;
`PassportDashboard`: 4 bottoni `passport_*`) — non specifico del nuovo
bottone di migrazione, semplicemente mai notato prima perché nessuno aveva
osservato con attenzione lo stato finale della pagina dopo un click.

## Soluzione

`execute()` restituisce già un array tipizzato
(`command`/`output`/`status`/`exitCode`) — l'unico segnale davvero
affidabile, dato che il metodo è sincrono. Rimossi i dispatch Laravel morti
da entrambe le Action; le due pagine ora leggono direttamente il valore di
ritorno e impostano `$this->output`/`$this->status`/`$this->isRunning`
subito dopo la chiamata, invece di aspettare un evento che non sarebbe mai
arrivato. Rimossi anche i 5 metodi `#[On(...)]` (ormai morti/ridondanti) e le
voci `artisan-command.*` da `protected $listeners`.

Corretta anche una notifica di completamento/fallimento che su
`ArtisanCommandsManager` usava chiavi di traduzione mai definite
(`notifications.success`/`.error`, inesistenti in
`lang/{it,en}/artisan-commands-manager.php`) — sostituite con le chiavi
`messages.command_completed(_desc)`/`messages.command_failed(_desc)` già
presenti e pensate proprio per questo scopo.

## Verifica

- `ExecuteArtisanCommandAction::execute('notify:migrate-themes-to-mail-templates')`
  chiamata direttamente via tinker: `status=completed`, `exitCode=0`, 4 righe
  di output reale ("Survey migrati: 39, saltati: 4."). Confermato che è
  proprio questo valore di ritorno — non un evento — a dover pilotare lo
  stato della pagina.
- PHPStan pulito sui 4 file toccati (le due Action, le due pagine); la
  correzione del tipo di `output` da `array<int, string>` a `list<string>`
  nel docblock di ritorno di `ExecuteArtisanCommandAction` era necessaria
  per l'assegnazione diretta alla proprietà Livewire tipizzata `list<string>`.
- `php -l` pulito sui 4 file.
- `Modules/Xot/tests/Unit/Actions/ExecuteArtisanCommandActionTest.php`
  aggiornato: rimosse le asserzioni `Event::assertDispatched(...)` che
  testavano il meccanismo morto appena rimosso; le asserzioni sul contratto
  reale (`status`/`exitCode`/`output`) restano identiche. **Non eseguibile in
  questo ambiente**: l'intera suite Unit di Xot (23/23 test in un file
  scollegato, `XotExecuteCoverage50Test.php`) fallisce per un bug di bootstrap
  DB test preesistente e scollegato (stesso pattern già documentato altrove
  come issue #70 lato Notify: il connettore SQLite riceve come "path" il nome
  del database MySQL di un'altra connessione, `geek_quaeris_backup_server_23_10_2025_test`)
  — non introdotto né toccato da questa story.
- `PassportDashboardNewCredentialsTest.php` rieseguito dopo la modifica
  (l'azione "Nuove credenziali" non usa `executeCommand()`, non impattata):
  stesso esito di sempre, 1 passed, 2 skipped per il gap preesistente
  `profiles.uuid`.

## Dev Notes

- Lock prima di ogni edit: `bash bashscripts/lock/lock.sh <path> <task-id> <agent-id>`.
- Se in futuro serve output realmente "in tempo reale" (riga per riga mentre
  il processo gira, non solo il risultato finale), l'architettura attuale
  (una singola richiesta Livewire sincrona e bloccante) non lo può offrire a
  prescindere da come si dispacciano gli eventi — servirebbe una coda +
  broadcasting reale (Echo/Reverb) o polling che legga uno stato persistito
  altrove (cache/DB) mentre il job gira in background. Fuori scope qui: i
  comandi di questa pagina durano da meno di un secondo a qualche decina di
  secondi, il risultato finale sincrono è sufficiente.

### References

- [Source: laravel/Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php]
- [Source: laravel/Modules/Xot/app/Actions/ExecuteComposerDumpAutoloadAction.php]
- [Source: laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php]
- [Source: laravel/Modules/User/app/Filament/Clusters/Passport/Pages/PassportDashboard.php]
- [Source: laravel/Modules/Xot/resources/views/filament/pages/artisan-commands-manager.blade.php]
- [Source: laravel/Modules/Xot/app/Providers/EventServiceProvider.php]

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- Scoperto 2026-09-17 dall'utente durante il test end-to-end del bottone
  "Importa Vecchi Template Email/SMS" (story
  [xot-artisan-commands-manager-layout-and-composer-dump-autoload.md]): il
  comando risultava completato guardando il database, ma la pagina non
  mostrava mai traccia del completamento.
- Causa radice identificata leggendo il codice reale (non ipotesi): due
  sistemi di eventi distinti (`Illuminate\Support\Facades\Event` lato
  server, bus eventi Livewire lato componente) che condividono solo la
  convenzione di naming, mai il canale. Confermato che nessun listener reale
  esiste da nessuna parte per questi nomi evento.
- Fix applicato simmetricamente su Xot (`ArtisanCommandsManager`, le due
  Action) e User (`PassportDashboard`, stesso pattern, stessi 4 bottoni
  `passport_*`) — stesso difetto, stessa causa, in entrambi i moduli.

### File List

- `laravel/Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php` (modificato)
- `laravel/Modules/Xot/app/Actions/ExecuteComposerDumpAutoloadAction.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Pages/ArtisanCommandsManager.php` (modificato)
- `laravel/Modules/User/app/Filament/Clusters/Passport/Pages/PassportDashboard.php` (modificato)
- `laravel/Modules/Xot/tests/Unit/Actions/ExecuteArtisanCommandActionTest.php` (modificato)

## GitHub (tracciamento)

| Risorsa | Stato | Link |
|---|---|---|
| Issue (modulo Xot) | aperta | https://github.com/laraxot/module_xot_fila5/issues/131 |
| Discussion (modulo Xot) | aperta | https://github.com/laraxot/module_xot_fila5/discussions/132 |
