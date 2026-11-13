---
title: "[STORY] PHPStan Comment: risoluzione dei commentator partecipanti"
type: story
status: done
priority: high
created: 2026-10-08
updated: 2026-10-08
module: Comment
tags: [phpstan, comment, collection, generics, duplicate, swarm]
---

# PHPStan Comment: risoluzione dei commentator partecipanti

Fase BMAD: Dev + Docs.

## Richiesta

Ordine permanente: azzerare gli errori PHPStan guardando lo scopo del codice. Perimetro: 4 errori (`argument.type`, `return.type`) in due file.

## Analisi

**Scopo.** `Comment::participatingCommentators()` risponde a "chi ha gia' partecipato alla conversazione su questo commentable, escluso l'autore del commento corrente" (derivata da `spatie/laravel-comments`). Raggruppa i commentator per tipo (`commentator_type`: alias morph o FQCN), carica i modelli di ogni tipo e tiene solo quelli che implementano `CanComment`.

**Due copie identiche.** `Actions/Resolve/ResolveParticipatingCommentatorsAction` e `Support/CommentParticipatingCommentatorsResolver` erano la stessa funzione, riga per riga. Chiamanti verificati con `rg`:

- l'Action e' usata da `Comment::participatingCommentators()` (canonica, coerente con `docs/wiki/concepts/no-app-support-queueable-actions.md`);
- il Resolver ha come unico chiamante `CommentModelSupport`, che a sua volta non ha chiamanti. La doc dichiara `app/Support/` rimosso il 2026-07-13, ma i file sono nel working tree e tracciati in git.

**Causa dell'errore (una sola, vista due volte per copia).** La closure di `flatMap` aveva `Collection $related` senza generics, cioe' `Collection<array-key, mixed>`. `TValue` e' invariante: `Collection<int, array{...}>` non e' assegnabile, PHPStan scarta il callback, non inferisce `TFlatMapValue` e il secondo errore (`return.type`) e' la conseguenza. In piu' la chiave di `groupBy` e' `int|string`, non `string`.

## Modifiche

- `ResolveParticipatingCommentatorsAction`: il corpo della closure e' diventato `resolveByType()`, privato, con `@param Collection<int, array{commentator_id: int|string|null, commentator_type: string}>` e `@return Collection<int, CanComment>`; `flatMap($this->resolveByType(...))`. Rimossi i due `@var` inline (`$participants`, `$resolved`) e `$participants`.
- Al posto di `@var class-string<Model>` + `@var Model $model`: `is_subclass_of($resolvedClass, Model::class)` (controllo reale: un `commentator_type` che punta a una classe non Model ora restituisce una collezione vuota invece di un fatal su `new`/`getKeyName()`).
- `filter()->map(fn => CanComment)` sostituito da `->toBase()->whereInstanceOf(CanComment::class)` (stesso risultato, tipo corretto senza forzature).
- `(string) $type`: PHP trasforma in int le chiavi numeriche, il cast riporta l'alias morph alla sua forma.
- `CommentParticipatingCommentatorsResolver::resolve()` ora delega all'Action: una sola implementazione.

## Verifica

- `php -l` sui due file: nessun errore.
- `phpstan analyse` sui due file (con gli altri 5 dello swarm `small-modules`): `[OK] No errors`.
- Probe fuori repo su sqlite in memoria che invoca `resolveByType()` (reflection): alias morph, FQCN, Model che non implementa `CanComment`, alias sconosciuto, classe inesistente, classe non Model, chiave int, reset delle chiavi dopo `flatMap`: tutto atteso.
- Pest: non eseguito. Il MySQL di test (10.100.200.53:3306) va in timeout da questo host (probe `/dev/tcp`, rc=124); nessun test esistente copre `participatingCommentators`.

## Decisioni

- Delega invece di cancellazione: `app/Support/` e' fuori perimetro e contiene altri file con la stessa storia. Cancellare tutto il livello e' una scelta da prendere una volta sola.

## Aperto

- `app/Support/*` (15 file, `CommentModelSupport` senza chiamanti) risulta rimosso dalla doc ma e' tornato nel working tree e in git: story dedicata per eliminarlo.
- Nessun test copre `ResolveParticipatingCommentatorsAction`; va scritto su un host che raggiunge il DB di test.
- Terza copia, fuori perimetro: `Models/Concerns/HasComments::participatingCommentators()` (quella che le notifiche usano davvero, via `NotifyApprovedCommentAction`) ripete la risoluzione tipo -> modelli `CanComment` con la stessa forma di `resolveByType()` e gli stessi `@var` inline. Semantica diversa (tutti i commentator, senza escludere l'autore): candidata a riusare un'unica risoluzione per tipo.
