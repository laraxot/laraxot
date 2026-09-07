---
title: "PHPStan zero — Comment: standdown, modulo fortemente conteso da swarm Epic4 (story-2.15)"
type: coordination
tags: [phpstan, comment, standdown, epic4, story-2.15, opencode]
created: 2026-09-07T19:11:00Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/Comment
blocking: false
---

# Modules/Comment — PHPStan zero campaign, standdown (nessuna modifica)

## Ricognizione (Fase 0)

`cd laravel/Modules/Comment && git status` mostra 6 file modificati non miei
(`app/Filament/Resources/CommentResource.php`,
`CommentResource/Schemas/CommentForm.php`, `CommentResource/Schemas/CommentInfolist.php`,
`app/Models/CommentNotificationSubscription.php`,
`docs/stories/CMT-002.fix-comment-notification-subscription-base-model.story.md`,
`tests/Feature/Filament/Pages/ListCommentsTest.php`) più 4 file `.lock` untracked e
3 story BMAD untracked (`CMT-001`, `CMT-003`, `phpstan-fix-1788768735` — quest'ultima
vuota).

`bashscripts/lock/check.sh` conferma lock attivo, stesso task/agente/timestamp già
visto oggi su WorkOrder:

- `app/Filament/Resources/CommentResource.php` → **LOCKED**, task `story-2.15`,
  agente `opencode`, `LOCKED at 2026-09-07T16:18:42+02:00`.
- `app/Filament/Resources/CommentResource/Schemas/CommentForm.php` → stesso lock.
- `app/Filament/Resources/CommentResource/Schemas/CommentInfolist.php` → stesso lock.
- `app/Filament/Resources/CommentResource/Tables/CommentsTable.php` → stesso lock.

mtime dei file in progressione continua e recente (non stale):

| File | mtime |
|---|---|
| `CommentNotificationSubscription.php` | 12:30:10 |
| `ListCommentsTest.php` | 16:39:04 |
| `CommentResource/Tables/CommentsTable.php` | 16:41:15 |
| `CommentResource/Schemas/CommentForm.php` | 16:53:34 |
| `CommentResource/Schemas/CommentInfolist.php` | 17:07:27 |
| `CommentResource.php` | 18:37:09 |

Verifica fatta alle 19:10 — ultima scrittura ~33 minuti prima, stesso ordine di
grandezza già considerato "vivo, non stale" nello standdown WorkOrder di oggi
(`docs/chat/2026-09-07-phpstan-workorder-standdown.md`, 17-18 minuti). Comment non
era elencato nella tabella baseline di `docs/stories/2.15.filament-swarm-migrate.story.md`
(verificato: nessun match `grep -i comment`), stesso pattern già osservato per
WorkOrder: lo swarm ha raggiunto moduli non presenti nella baseline iniziale.

## PHPStan reale (Fase 0, comando richiesto dal task)

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Comment --no-progress --memory-limit=-1
```

Risultato: **3 errori**, tutti `method.staticCall`, tutti dentro l'area della stessa
estrazione Schemas/Tables in corso:

| File | Errore |
|---|---|
| `app/Filament/Resources/CommentResource.php` (LOCKED) | riga 23: static call a `CommentInfolist::getInfolistSchema()` (istanza, non statico) |
| `tests/Feature/Filament/Pages/ListCommentsTest.php` (non lockato, ma dipende dalla stessa API in corso di refactor) | riga 59: static call a `CommentForm::getFormSchema()`; riga 60: static call a `CommentInfolist::getInfolistSchema()` |

Zero errori fuori da questi due file, ed entrambi dipendono dalla stessa decisione
(rendere `getFormSchema()`/`getInfolistSchema()` statici oppure cambiare il call-site)
che spetta a chi sta completando l'estrazione `Schemas/` sotto lock attivo.

## Decisione: standdown

Stesso vincolo esplicito del task usato per il precedente standdown WorkOrder di
oggi: modulo fortemente conteso (lock vivo su 3 dei 4 file Schemas/Tables, mtime
recenti in progressione) e il 100% degli errori PHPStan reali ricade nell'area
di quella stessa estrazione. Non esiste un sottoinsieme di errori "non
sovrapposti" su cui lavorare in sicurezza — anche il fix del solo test
(`ListCommentsTest.php`, non lockato) dipenderebbe dalla forma finale
(static vs istanza) che l'agente `opencode` sta ancora decidendo nei file
lockati.

**Nessun file toccato in questa sessione. Nessun commit, nessun push.**

Pattern generalizzato già documentato:
`bashscripts/ai/wiki/memories/phpstan-transient-errors-schema-table-extraction.md`
(se non esiste ancora con questo nome esatto, vedi il pattern equivalente citato
nello standdown WorkOrder).

## Story BMAD esistenti (nessuna nuova creata)

- `Modules/Comment/docs/stories/CMT-001.base-model-inheritance.story.md`,
  `CMT-002.fix-comment-notification-subscription-base-model.story.md` (Status:
  esito verificato 0 errori sul file, vedi sezione "Esito finale verificato"),
  `CMT-003.phpstan-residual-fixes.story.md` (Status: Done, i due errori residui di
  quella story — MorphTo generics + `method.deprecated` su `getTableActions()` —
  risultano già risolti, la loro assenza dall'attuale lista di 3 errori lo conferma)
  — nessuna riaperta o duplicata da questa sessione. La regressione attuale (0 → 3
  errori) è causata dal refactor Epic4/story-2.15 in corso, non da queste story
  precedenti.
- `Modules/Comment/docs/stories/phpstan-fix-1788768735.story.md` — file vuoto,
  non popolato da questa sessione (fuori scope: non è mio da completare, verosimilmente
  scaffold abbandonato di un'altra sessione).

## Nota su claim precedente (stessa sera, altra sessione)

`docs/chat/2026-09-07-claim-job-ui-comment-rating-phpstan-swarm.md` (status
`in-progress`, mai chiuso) rivendicava Comment insieme a Job/UI/Rating con la
stessa identica cifra — "Comment (3, method.staticCall)" — e annotava "lock
FREE al momento del claim". Quel claim precede di molte ore il lock opencode
attuale (`LOCKED at 16:18:42`); il claim non è mai stato marcato completato per
Comment, quindi è ragionevole che quel lavoro non sia mai partito su questo
modulo specifico prima che lo swarm Epic4 prendesse gli stessi file. Non è una
duplicazione di questo standdown: se quella sessione riprende Comment ora,
troverà lo stesso lock attivo qui documentato.

## Per chi riprende Comment dopo lo swarm

1. Aspettare che i 4 `.lock` in `app/Filament/Resources/CommentResource*` siano
   rilasciati (o comunque stale, verificare mtime > 1h senza nuove modifiche).
2. Ri-eseguire `phpstan analyse Modules/Comment --no-progress --memory-limit=-1` da
   zero: i 3 errori qui documentati potrebbero già essere risolti dallo swarm stesso
   una volta allineate le firme statiche/istanza tra `CommentResource.php` e le
   classi `Schemas/`.
3. Se dopo il rilascio del lock i 3 errori persistono, causa radice più probabile:
   `CommentResource.php` chiama `CommentForm::getFormSchema()` /
   `CommentInfolist::getInfolistSchema()` come chiamate statiche (`Class::method()`)
   mentre i metodi sono dichiarati come metodi d'istanza nelle classi `Schemas/` —
   allineare firma (rendere i metodi `public static function` se il pattern
   `XotBaseResource` lo richiede) oppure il call-site (istanziare la classe), non
   entrambi in modo incoerente.
