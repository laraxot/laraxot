---
title: "PHPStan Comment — standdown, file lockati/in corso da opencode story-2.15"
type: coordination
tags: [phpstan, comment, standdown, epic4, story-2.15, opencode]
created: 2026-09-07T19:20:00Z
updated: 2026-09-07T19:30:00Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/Comment
blocking: false
---

## Aggiornamento 19:30

Riverificato con cache pulita: lo swarm story-2.15 ha nel frattempo risolto i 2 errori
in `ListCommentsTest.php` (righe 59-60) da solo, mentre questo standdown era in scrittura.
Resta **1 solo errore reale**, lo stesso identico problema alla riga 23 di
`CommentResource.php`, file ancora lockato (stesso `LOCKED at 2026-09-07T16:18:42+02:00`,
mtime file fermo alle 18:37:09, task attivo). Standdown confermato per l'unico residuo:
sarebbe un fix di una riga, ma il file e' esplicitamente rivendicato da un altro agente
che sta iterando proprio su questo cluster in questo momento (ha appena chiuso i due errori
gemelli nel test). Non lo tocco.

# Modules/Comment — PHPStan zero campaign, standdown (nessuna modifica di codice)

Assegnazione: portare `phpstan analyse Modules/Comment` a zero insieme a Job/UI/Comment/Rating
(claim combinato in `docs/chat/2026-09-07-claim-job-ui-comment-rating-phpstan-swarm.md`).

## Ricognizione (Fase 0)

`cd laravel && ./vendor/bin/phpstan clear-result-cache && ./vendor/bin/phpstan analyse
Modules/Comment --no-progress --memory-limit=-1` (cache pulita, non fidarsi della cache sotto
edit concorrenti): **3 errori reali**, tutti `method.staticCall`:

| File | Riga | Dettaglio |
|---|---:|---|
| `app/Filament/Resources/CommentResource.php` | 23 | chiamata statica a `CommentInfolist::getInfolistSchema()` (ormai d'istanza) |
| `tests/Feature/Filament/Pages/ListCommentsTest.php` | 59 | chiamata statica a `CommentForm::getFormSchema()` (ormai d'istanza) |
| `tests/Feature/Filament/Pages/ListCommentsTest.php` | 60 | chiamata statica a `CommentInfolist::getInfolistSchema()` (ormai d'istanza) |

## Perché standdown

`bash bashscripts/lock/check.sh` conferma lock attivi con task id esplicito su tutti e 4 i file
core coinvolti nella stessa trasformazione:

- `app/Filament/Resources/CommentResource.php.lock`
- `app/Filament/Resources/CommentResource/Schemas/CommentForm.php.lock`
- `app/Filament/Resources/CommentResource/Schemas/CommentInfolist.php.lock`
- `app/Filament/Resources/CommentResource/Tables/CommentsTable.php.lock`

Tutti: `Task: story-2.15`, `Agent: opencode`, `LOCKED at 2026-09-07T16:18:42+02:00` (stesso
task-id visto oggi anche su WorkOrder, vedi `docs/chat/2026-09-07-phpstan-workorder-standdown.md`
— lo swarm Epic4 filament-canonical-structure si e' esteso anche a Comment).

Il `git diff` conferma che la trasformazione e' a meta':

```
- public static function getFormSchema(): array   // CommentResource.php, RIMOSSO (corretto,
-     return CommentForm::getFormSchema();          //   XotBaseResource::form() è final)
- }
-
  #[Override]
- public static function getInfolistSchema(): array
+ public function getInfolistSchema(): array        // reso d'istanza (corretto)
  {
      return CommentInfolist::getInfolistSchema();   // MA la call interna è rimasta statica
  }
```

Stessa cosa su `CommentForm.php`/`CommentInfolist.php`: la firma `getFormSchema()`/
`getInfolistSchema()` e' gia' stata convertita da `static` a istanza da questa stessa sessione
esterna (opencode), ma i call site non sono ancora stati aggiornati — compreso il call site
interno a `CommentResource.php` riga 23, dentro un file che il lock rivendica esplicitamente.

Mtime dei file: `CommentResource.php` modificato le 18:37:09 (34 minuti prima di questa
verifica, le 19:11), `CommentForm.php`/`CommentInfolist.php` alle 16:53/17:07 — attivita' reale
e recente sullo stesso cluster di file, non un lock abbandonato con contenuto fermo.

Per il vincolo esplicito del runbook (`03-quality-gates.md`, "QUANDO FERMARSI E CHIEDERE":
*"un altro agente sta già lavorando sugli stessi file"*): i 3 errori residui ricadono
esattamente nei file lockati o nel loro accoppiamento diretto (il test che esercita quelle
stesse classi). Non c'e' un sottoinsieme sicuro da correggere senza toccare il lock o
anticipare/duplicare il lavoro in corso di story-2.15.

**Nessun file toccato in `Modules/Comment` in questa sessione. Nessun commit, nessun push.**

## Story BMAD esistenti (nessuna nuova creata)

- `Modules/Comment/docs/stories/CMT-003.phpstan-residual-fixes.story.md` — Done, ma copre due
  errori diversi (MorphTo generics + `getTableActions()` deprecated), gia' chiusi; non riaperta,
  i 3 errori qui documentati sono generati DOPO quella chiusura dalla conversione statico→istanza
  in corso.
- Non creata una nuova story: il lavoro appartiene a story-2.15 (opencode), aggiungerne una
  duplicata violerebbe la regola "non prendere in carico lavoro gia' assegnato" (§2 del runbook).

## Per chi riprende Comment dopo lo swarm

1. Aspettare il rilascio dei 4 `.lock` (o comunque un'assenza di nuove modifiche ai file per
   un tempo ragionevole).
2. Ri-eseguire `phpstan analyse Modules/Comment --no-progress --memory-limit=-1` con cache
   pulita: i 3 errori qui documentati sono quasi certamente gia' risolti da chi sta completando
   story-2.15 (la firma d'istanza e' gia' pronta, manca solo aggiornare 3 call site: 1 riga in
   `CommentResource.php`, 2 righe in `ListCommentsTest.php`).
3. Se ancora presenti, il fix e' meccanico: `CommentInfolist::getInfolistSchema()` →
   `(new CommentInfolist())->getInfolistSchema()` (idem `CommentForm`).

## Report

| Modulo | Errori iniziali | Errori finali | File toccati | Coverage prima → dopo | `mixed` residui | Story ID | Commit |
|---|---|---|---|---|---|---|---|
| Comment | 3 (verificato con cache pulita) | 3 (invariato, standdown) | 0 | n/a (nessuna modifica) | nessuno | n/a (lavoro di story-2.15, non duplicato) | nessuno |
