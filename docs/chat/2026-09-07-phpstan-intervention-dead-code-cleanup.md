---
title: "PHPStan Intervention — dead-code cleanup post 4.14.6 extraction"
type: coordination
tags: [phpstan, intervention, method-unused, filament-canonical, story-4.14.6]
created: 2026-09-07T19:05:00Z
status: done
agent: claude (sonnet 5)
blocking: false
---

# Claim: PHPStan Modules/Intervention — 15 -> 0

Sessione dedicata a chiudere gli errori PHPStan residui in `Modules/Intervention`
(swarm task separato, non l'Epic 4 Schemas/Tables — quella non include
Intervention per l'INDEX). `bashscripts/lock/check.sh` sul modulo: FREE
all'avvio.

## Stato trovato

`git status` in `laravel/Modules/Intervention` mostra ~24 file modificati e
6 file nuovi non commitati (Schemas/Tables extraction di story
`4.14.6.intervention-canonical`, status `completed` ma mai committata),
lang/it additions, RelationManagers, Pages — WIP di altre sessioni/swarm
(migration-foreignidfor, canonical structure). Non toccato nulla di
quell'insieme, tranne i 3 file sotto.

## Baseline PHPStan (2026-09-07 19:00, cache pulita)

15 errori:
- `DailyNoteResource.php:64` `extractUserId()` method.unused
- `InterventionResource.php` x7 method.unused (shouldForceSelfAssignment,
  getWorkOrderOptions, getCustomerOptions, getAssignableOperatorOptions,
  previewMaterialsTotal, getPreviousNoteSuggestions, applyNoteSuggestion,
  normalizeTextState — nota: sono 8, non 7, vedi report finale)
- `InterventionResource/Schemas/InterventionInfolist.php:30` closure
  `$record` non tipizzata (return.type + 4x property.nonObject)

## Root cause

Story `4.14.6.intervention-canonical` (status: completed, mai committata)
ha estratto `getFormSchema()`/`getInfolistSchema()`/`table()` da
`DailyNoteResource.php`/`InterventionResource.php` verso
`Schemas/`+`Tables/`, copiando gli helper privati usati dal form/infolist
nelle nuove classi canoniche — ma senza cancellare le copie originali nella
Resource, che sono rimaste come codice morto duplicato. La chiusura also
mancava il type-hint sul closure param di `InterventionInfolist::getInfolistSchema()`.

Fix in corso: rimozione delle copie morte confermate irraggiungibili
(nessun `self::` interno le richiama, nessun riferimento qualificato nel
modulo), type-hint sul closure `Intervention $record`, nullsafe ridondante
tolto dove PHPStan lo segna `nullsafe.neverNull`.

## Nota anomalia trovata (non causata da questa sessione)

`docs/coverage.md` nel working tree (118 righe) non corrisponde a HEAD
(220 righe, commit `3e5a036` "type constants [story: 2.8]"): la sezione
`typeCoverage.constantTypeCoverage` committata risulta assente dalla copia
su disco, senza che nessun comando di questa sessione l'abbia toccata.
Recuperato da `git show HEAD:docs/coverage.md` prima di appendere la nuova
sezione, per non perdere lavoro già mergiato. Se un'altra sessione ha
un'edit in corso su questo file, ri-allineare prima di committare.

## Esito finale (2026-09-07 ~19:45)

PHPStan: 15 -> 0 errori (3+ run consecutivi a cache pulita, uno intermedio
"contaminato" da un fatal di bootstrap transitorio di un altro agente
confermato non riproducibile). Pint `--test` sui 3 file toccati: pass
(mai `--dirty`, avrebbe toccato 6 file random di `Modules/User`). Pest:
42 passed / 113 failed / 3 skipped — zero hit sui file/metodi toccati,
stessa causa pre-esistente (gap migrazioni test DB + probabile
contesa dati da altre suite pest concorrenti sullo stesso DB condiviso).
PHPMD/PHPInsights: nessuna nuova violazione, punteggi identici alla
baseline story 2.8.

Story riusata: `4.14.6.intervention-canonical` (era `completed` ma mai
committata, con codice morto lasciato dall'estrazione — questo era il suo
completamento, non uno scope nuovo).

Commit `6930f82` in `laraxot/module_intervention_fila5`, remote unico
(`laraxot`), push pulito `3e5a036..6930f82`. Nessun merge necessario (HEAD
locale gia' allineato a `laraxot/dev` prima del commit).

File toccati (solo questi, il resto e' WIP di altre sessioni non toccato):
`app/Filament/Resources/DailyNoteResource.php`,
`app/Filament/Resources/InterventionResource.php`,
`app/Filament/Resources/InterventionResource/Schemas/InterventionInfolist.php`,
`docs/coverage.md`, `docs/stories/4.14.6.intervention-canonical.story.md`.
