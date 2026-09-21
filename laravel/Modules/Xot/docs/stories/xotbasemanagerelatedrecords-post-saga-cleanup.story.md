---
title: "XotBaseManageRelatedRecords: pulizia post-saga (classe duplicata, tooling, stash trovati)"
type: story
module: Xot
epic: null
story_id: null
slug: xotbasemanagerelatedrecords-post-saga-cleanup
status: done
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php"
  - "laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php"
related:
  - "./xotbasemanagerelatedrecords-convention-over-configuration.story.md"
  - "./18.27.hasxottable-fuori-dai-componenti-filament.story.md"
---

# XotBaseManageRelatedRecords: pulizia post-saga

## Story

Richiesta esplicita dell'utente (2026-09-11, sera): "le cose da risolvere
devi risolverle subito, partendo sempre da creare prima la bmad story."
Questa story elenca cio' che l'audit di chiusura della saga
XotBaseManageRelatedRecords aveva segnalato come "fuori scope"/"story
futura" invece di risolvere, e lo risolve ora dove e' sicuro farlo.

## Acceptance criteria

- [x] Seconda classe `XotBaseManageRelatedRecords` (path
      `Resources/XotBaseResource/Pages/`): verificata dead in produzione di
      nuovo (unico riferimento: `tests/Fixtures/Stubs/XotCovManageRelated.php`,
      un solo assert su `getNavigationGroup()` — identico su entrambe le
      classi). Fixture migrata alla classe canonica, duplicato rimosso.
      Verificato: `phpstan analyse Modules/Xot` 0 errori,
      `XotRelationManageStatesCoverageTest` + `XotBaseManageRelatedRecordsRegressionTest`
      8/8 verdi.
- [x] Collisione di trait method `getKeyTransFunc` segnalata da PHPMD:
      **falso positivo**, verificato. Scoping PHPMD al solo file →
      pulito (un solo finding, `LongVariable` su `$relatedResourceSchema`,
      stilistico). Causa: `TransTrait` e `NavigationLabelTrait` usano
      entrambi `TransFuncTrait` (stesso trait, stesso metodo, "diamante"
      valido in PHP — nessun conflitto reale) — PHPMD lo segnala solo
      quando analizza l'intera cartella `Filament/` insieme, non il singolo
      file: limite del suo resolver di trait multi-file, non un bug di
      questa classe. PHPStan (0 errori) e i test runtime confermano.
      Non e' la classe duplicata sopra la causa (verificato: il messaggio
      resta identico anche dopo averla rimossa).
- [x] **Bug reale trovato e corretto, causa diretta del rumore pest**:
      `FileAction.php:285`, `dddx($msg); // 4 debug` — debug dump-and-die
      ATTIVO (non commentato, a differenza del blocco gemello poche righe
      sopra, marcato allo stesso modo ma correttamente disattivato) nel
      ramo "file sorgente asset non trovato" — una condizione attesa, non
      un errore, che pero' interrompeva l'intera richiesta/test con un dump.
      Rimosso; `$url` (gia' costruito) torna comunque. `Helper.php:53`
      (`dddx()` stessa) NON e' un bug: e' la funzione helper di debug
      stessa, dump-on-call e' il suo scopo dichiarato — nessuna modifica li'.
      Verificato: `php -l` pulito, `phpstan analyse` sul file 0 errori.
- [x] Stash trovati durante il lavoro di oggi: root (2, non miei,
      pre-esistenti, 174 e 6 file, includono una cancellazione di
      `ActivityLogger.php` — WIP sostanziale di altre sessioni) e User (1,
      mio, isolato durante un merge — rimuove un side-effect che scrive
      `email`/`user_id` su record `Profile` durante una lettura, sostituito
      con un placeholder). Nessuno applicato ne' droppato: sono scelte di
      logica applicativa, non pulizia meccanica — riportati esplicitamente
      all'utente in chat, restano recuperabili con `git stash list`/`show`
      nei rispettivi repo.

## Dev Notes

Contesto completo, incidenti e verifica: vedi
`xotbasemanagerelatedrecords-convention-over-configuration.story.md`
(saga principale, "done") e second brain
`xotbasemanagerelatedrecords-settima-direzione-chiusura-2026-09-11.md`.

## Testing

`phpstan analyse Modules/Xot` dopo ogni modifica; `XotBaseManageRelatedRecordsRegressionTest`
resta verde (6/6) per tutta la durata di questa story.
