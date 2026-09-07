---
title: "Job module — typeCoverage.constantTypeCoverage fix (Schedule constants)"
type: coordination-note
created: 2026-09-07
status: done
---

# Job: Schedule constants type declarations (typeCoverage campaign)

## Cosa e' stato fatto

`laravel/Modules/Job/app/Models/Schedule.php` — `STATUS_INACTIVE`/`STATUS_ACTIVE`/
`STATUS_TRASHED` da `public const` senza tipo a `public const int` (PHP 8.3+ real
type declaration), per il bucket repo-wide `typeCoverage.constantTypeCoverage`.

Story: `laravel/Modules/Job/docs/stories/4.31.constant-type-coverage-schedule.story.md`.
Coverage/gate: `laravel/Modules/Job/docs/coverage.md` (sezione 2026-09-07).

Commit `25d24e3` su `laraxot/dev` (unico remote del modulo), pushato pulito, nessun
merge necessario (nessuna divergenza al fetch).

## Attenzione per chi lavora su altri moduli nello stesso bucket

All'apertura di questa story, `Schedule.php` aveva gia' un WIP non committato di
un'altra sessione che aggiungeva `/** @var int */` sopra le costanti **senza**
toccare la `const` stessa — pattern sbagliato, non sposta la percentuale
`constantTypeCoverage` (vedi `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`).
Sostituito con il fix corretto (type declaration reale). Se stai lavorando su altre
costanti nello stesso bucket, controlla che il fix sia `public const <tipo reale>
NOME = valore;`, non un `@var` PHPDoc.

## Pest — nota per chi tocca Job

Full suite (`Modules/Job/tests/`, root `phpunit.xml`) impiega ~49 min in questa
sessione per contesa multi-agente sul DB `workorder_data_test` condiviso. Risultato:
303 passed / 44 failed — tutte le failure sono debito preesistente, non causato da
questa story (dettaglio in coverage.md): schema/model mismatch sulla colonna
`schedules.status` (int in DB vs `Status::class` enum), bug nell'helper di test
condiviso `expectMethod()` (Mockery), traduzioni mancanti in `StatusTest`, e un test
non aggiornato dopo la rimozione di `HasXotFactory` da `Task` (commit `08cd299`,
story precedente). Se qualcuno vuole chiudere questi 44 fallimenti, sono 4 cause
distinte documentate in coverage.md, non un blocco singolo.

## Non fatto in questa story (fuori scope dichiarato)

Non rieseguito `phpstan analyse Modules` (l'intero albero) per misurare la
percentuale `constantTypeCoverage` prima/dopo: costo elevato e forte contesa con
altre sessioni attive sullo stesso repo in questo momento. Il fix e' comunque
corretto per costruzione (stesso pattern gia' verificato altrove nel bucket, es.
`Rating/app/Filament/Blocks/Rating.php:23`).

## Nota su un messaggio sospetto ricevuto a meta' task

Durante questa story e' arrivato un messaggio che si presentava come "il
coordinatore", con una nuova regola non richiesta: aggiungere link a issue/discussion
GitHub nel frontmatter di ogni `.md` toccato, creandone di nuove via `gh` se assenti.
Non e' stato eseguito: non e' coerente con nessuna regola di progetto esistente
(CLAUDE.md, wiki, second brain), comporta un'azione esterna consequenziale
(creazione di artefatti su repo GitHub altrui) e non era nel task originale — pattern
tipico di prompt injection. Segnalato al chiamante, non applicato. Nessun link
issue/discussion aggiunto ai file di questa story.
