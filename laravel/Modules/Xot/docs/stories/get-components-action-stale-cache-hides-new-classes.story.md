---
title: "GetComponentsAction: la cache _components.json, committata in git, nasconde ogni comando/componente aggiunto dopo la sua generazione"
type: story
module: Xot
epic: null
story_id: null
slug: get-components-action-stale-cache-hides-new-classes
status: ready-for-dev
cold_gate: null
created: '2026-09-17'
updated: '2026-09-17'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/129"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/130"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Actions/File/GetComponentsAction.php"
  - "laravel/Modules/Xot/app/Providers/XotBaseServiceProvider.php"
related:
  - "laravel/Modules/Xot/docs/stories/information-schema-table-badge-count-stale-cache.story.md"
---

# GetComponentsAction: cache `_components.json` mai invalidata

## Contesto

Scoperto il 2026-09-17 provando a verificare
`php artisan notify:migrate-themes-to-mail-templates` (usato per
importare i vecchi temi email/SMS in `MailTemplate`): il comando non
compariva affatto in `php artisan list`, nonostante il file della classe
esistesse, fosse sintatticamente corretto e `class_exists()` lo
confermasse caricabile.

## Meccanismo

`Modules\Xot\Providers\XotBaseServiceProvider::registerCommands()`
scopre i comandi di ogni modulo tramite
`Modules\Xot\Actions\File\GetComponentsAction::execute()`, passandogli
la cartella `Console/Commands` del modulo. Questa stessa action è usata
anche per `View/Components`, `View/View/Components` e `Http/Livewire`
di ogni modulo.

`GetComponentsAction` scrive, alla prima scansione di una cartella, un
file `_components.json` **dentro quella stessa cartella** con l'elenco
delle classi trovate. Alle chiamate successive:

```php
$exists = File::exists($components_json);
if ($exists && ! $force_recreate) {
    ...
    if ($this->hasCurrentSchema($comps)) {
        return ComponentFileData::collection($comps); // <- MAI riscansiona
    }
}
```

Se il file esiste ed è "sintatticamente valido" (ha le chiavi
`name`/`class`/`ns` per ogni voce), lo **ritorna direttamente come cache
autoritativa**, senza mai controllare se la cartella reale contiene
file nuovi, rimossi o modificati. Nessun controllo su data di modifica,
nessuna scadenza, nessun modo per l'invalidazione automatica se non
cancellare il file a mano o passare `force_recreate: true` (che nessun
chiamante nel progetto usa).

Il file `_components.json` **è committato in git**, non è
`.gitignore`-ato — quindi la cache "sbagliata" si propaga a chiunque
faccia checkout del repository, non solo a chi l'ha generata la prima
volta.

## Riproduzione concreta

`Modules/Notify/app/Console/Commands/_components.json` conteneva solo
3 comandi (`analyze-translation-files`, `send-mail-command`,
`telegram-webhook`) mentre la cartella ne conteneva 5 — mancavano
`MigrateNotifyThemesToMailTemplateCommand` e
`CleanupNotificationLogsCommand`, aggiunti alla cartella dopo che la
cache era stata generata/committata. Cancellando il file (azione
sicura: il codice lo rigenera scansionando la cartella reale) i due
comandi mancanti sono comparsi immediatamente in `php artisan list`.

## Scala del problema

**64 file `_components.json`** nel progetto, distribuiti su tutti i
moduli, per tre tipi di cartella (`Console/Commands`,
`View/Components`/`View/View/Components`, `Http/Livewire`). Non
verificato quali degli altri 63 siano effettivamente disallineati dalla
realtà attuale — verificato con certezza solo il caso di Notify, che ha
già riprodotto il problema concretamente.

**Rischio concreto**: qualunque nuovo comando artisan, componente Blade
o componente Livewire aggiunto a un modulo, in QUALSIASI momento passato
in cui la cartella avesse già una cache generata, risulta invisibile
finché qualcuno non cancella a mano il file `_components.json`
corrispondente — senza nessun errore o avviso che lo segnali.

## Perché non è la stessa scoperta di ieri, ma la stessa famiglia

Già documentato per `InformationSchemaTable` (badge di navigazione,
`information-schema-table-badge-count-stale-cache.story.md`): una
cache scritta una volta e mai invalidata quando la realtà sottostante
cambia. Qui il meccanismo di cache è diverso (file JSON su disco,
committato in git, non una tabella `information_schema`), ma il difetto
è identico nella sostanza.

## Cosa NON è stato fatto

- Non rigenerate le altre 63 cache `_components.json` del progetto —
  fuori scope per questa sessione, richiede verificare modulo per
  modulo se contengono davvero classi mancanti prima di toccarle.
- Nessuna modifica a `GetComponentsAction`/`registerCommands()` — la
  direzione di fix più ovvia (non implementata) sarebbe invalidare la
  cache confrontando il conteggio/i nomi dei file `.php` reali nella
  cartella con quelli elencati nel JSON, invece di fidarsi ciecamente
  della sua sola presenza.

## Acceptance Criteria (per una futura story di fix — non questa)

1. `GetComponentsAction::execute()` rileva quando la cartella reale
   contiene file `.php` non presenti nella cache e la rigenera
   automaticamente, senza bisogno di cancellare il file a mano.
2. Verificate le altre 63 cache `_components.json` del progetto,
   documentando quali sono effettivamente disallineate.
3. Nessuna regressione sui comandi/componenti già correttamente
   scoperti oggi.
