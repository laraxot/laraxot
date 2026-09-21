---
title: "PHPStan: contratto linkedTo e attributo parent_id nei test"
type: memory
status: active
created: 2026-09-08
updated: 2026-09-08
tags: [phpstan, bmad, second-brain, eloquent, testing]
qmd: "Rating linkedTo MorphTo model_type model_id parent_id getAttribute generici isolamento test"
---

# Contratto linkedTo e accesso a parent_id

Ripresa della [story BMAD 5.79](../../../../docs/stories/5.79.phpstan-542-errori-swarm-xot-user-longtail.story.md),
scope `codex-rating`. Il coordinatore possiede PHPStan e la cache condivisa;
il worker modifica sotto lock soltanto il metodo di relazione, l'accesso nel test
e questa documentazione. Nessuna modifica a schema o migrazioni.

## Evidenza e correzione

`git log -S 'function linkedTo'` nel repository principale identifica il commit
`2c7055584f`: durante il refactor adjacency list viene eliminato anche `linkedTo()`.
Il trait `HasRecursiveRelationships` fornisce le relazioni gerarchiche, ma non
questa relazione polimorfica. La storia del repository Rating, commit `60cc54b`,
conserva il contratto precedente:

```php
/** @return MorphTo<Model, $this> */
public function linkedTo(): MorphTo
{
    return $this->morphTo('model');
}
```

`BaseRatingModelTest` verifica già `model_type` e `model_id`. La modifica concorrente
presente all'avvio usava invece `morphTo(BaseRating::class, 'related_type', 'id')`
con generici `MorphTo<BaseRating, BaseRating>`: cambia le chiavi della relazione e
restringe senza evidenza il modello correlato. Il primo argomento di `morphTo()` è
il nome della relazione, non la classe correlata. La firma nel framework installato
è `MorphTo<Model, $this>`: il chiamante concreto è conservato da `$this`.

Il fix ristabilisce il contratto verificato e separa il PHPDoc di `getLabel()` da
quello di `linkedTo()`. Trait, contratto ricorsivo e campi introdotti da altri agenti
restano integri. Le note concorrenti 5.84/5.85 non costituiscono prova dei tipi o
dell'esecuzione dei test.

`ChildrenRelationManagerParentTest` usa ora `$child->getAttribute('parent_id')`:
legge lo stesso attributo Eloquent e mantiene la stessa `assertSame()` sul padre.
Non impone un tipo alla chiave e non presuppone che tutte le installazioni abbiano
la colonna. Le decisioni di schema 18.47/18.48/18.52 rimangono indipendenti dal fix.

## Gate eseguiti

| Verifica | Esito reale |
|---|---|
| `php -l` sui due file PHP modificati | Passato |
| Smoke CLI sul modello reale, senza bootstrap Laravel | `model_type` / `model_id`, identità del padre corretta, zero connessioni e zero query |
| PHPStan canonico `Modules` | Coordinatore, 15:46:45: exit 0, `errors=0`, `file_errors=0`; verifica finale serializzata a suo carico |
| PHPMD sui due file modificati | Exit 2: solo parametro `$media` inutilizzato preesistente in `registerMediaConversions()` |
| PHPMD intero `Modules/Rating` | Exit 3: segnalazioni preesistenti e parser PDepend incompatibile con i tipi DNF dei due `StatsOverview` |
| PHPInsights intero `Modules/Rating` | Exit 0: code 94,1; complexity 100; architecture 85,7; style 86,4; security 0 |
| Pest e coverage | Non eseguiti: isolamento della connessione IR non provato |

Report locali: `/tmp/codex-rating-phpmd.log`,
`/tmp/codex-rating-phpmd-touched.log`, `/tmp/codex-rating-phpinsights.json`.
Lo smoke usa un `SQLiteConnection` con risolutore PDO che solleva immediatamente
se richiesto: nessun database viene aperto, nessuna query viene eseguita.

## Preflight di sicurezza dei test

L'host è `192.168.1.35`. La cache configurazione presente dichiara
`app.env=testing`, ma la connessione `indennita_responsabilita` non punta a un
database con suffisso `_test`; le connessioni `rating`, `mysql` e `xot` lo hanno.
Il test dei figli usa il modello IR e compie una `create()`: il rollback non prova
l'isolamento del database. Non si lancia quindi la suite e non si cambia la
configurazione condivisa per ottenere un esito verde. Il coverage storico resta
storico; nessuna nuova percentuale è stata misurata.

## Regola riusabile

Prima di aggiungere generici a una relazione, confrontare implementazione del
framework, chiamate esistenti e storia Git. Un refactor delle relazioni gerarchiche
non rende redundantemente ereditata una relazione polimorfica distinta. Per i test
che controllano attributi Eloquent, `getAttribute()` preserva il contratto dinamico
senza inventare proprietà PHPDoc globali o modificare lo schema.
