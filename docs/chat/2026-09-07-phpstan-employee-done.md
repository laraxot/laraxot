---
title: "PHPStan Modules/Employee → 0 errori (fix root-cause, non baseline)"
type: chat-handoff
tags: [phpstan, employee, quality-gate, filament, xotbaseresource]
created: 2026-09-07
status: completato
---

# PHPStan Employee — 2026-09-07 (claude sonnet 5)

## Esito: completato

Modulo trovato **fortemente conteso** all'avvio (vedi sezione "Pre-check"), ma con
esattamente **1 errore PHPStan reale**, isolabile senza toccare i file WIP di un altro
agente. Fix applicato, verificato, committato e pushato.

## Pre-check (punto 1 del mandato)

- `git status` su `laravel/Modules/Employee`: 8 file già modificati + 2 untracked da
  un altro agente, in corso di estrazione Schemas/Tables su `AbsenceRequestResource`/
  `WorkHourResource` (rimozione override `getFormSchema()` inline morto).
- `docs/chat/INDEX.md`: modulo elencato nello swarm `migration-foreignidfor-swarm`
  ("in corso"), e nel tracker `2026-09-06-phpstan-parallel-execution-tracker.md` come
  "Employee | 2 | Minimal | Nearly trivial".
- Git log: 9 commit "fix: PHPStan Employee..." nelle ultime ~36h, incluso un commit
  immediatamente precedente (`c76077f`, 2026-09-07 08:30) che aggiunge un solo file
  `.story.md` **vuoto** (0 byte) — chiaro segnale di churn automatizzato a bassa
  qualità da un'altra sessione.
- Decisione: procedere ma **senza `git add -A`**, limitando le modifiche al solo file
  necessario per l'errore PHPStan reale, lasciando intatto il WIP altrui.

## Errori

- Iniziali: 1 — `method.staticCall` in `WorkHourResource.php:29`
  (`Static call to instance method ...\Schemas\WorkHourInfolist::getInfolistSchema()`).
- Finali: 0.

## Causa radice e fix

`WorkHourResource::getInfolistSchema()` era un override inline morto (il parent
`XotBaseResource::infolist()` è `final` e delega sempre alla classe dedicata
`Schemas/WorkHourInfolist`, mai a questo metodo) che in più tentava di chiamare
**staticamente** un metodo di **istanza**. Rimosso l'override e gli `use` diventati
orfani (`WorkHourForm`, `WorkHourInfolist`, `Override`) — stesso pattern che l'altro
agente stava già applicando nello stesso file per `getFormSchema()` pochi minuti prima.
Nessun `@phpstan-ignore`, nessuna baseline, nessun tocco a `phpstan.neon`, nessun
`mixed`.

## File toccati

- `laravel/Modules/Employee/app/Filament/Resources/WorkHourResource.php`
- `laravel/Modules/Employee/docs/coverage.md` (aggiornato)
- `laravel/Modules/Employee/docs/stories/phpstan-Employee-fix.md` (story esistente
  aggiornata, status Pending → Done — non creata una nuova story duplicata)
- `bashscripts/ai/wiki/memories/xotbaseresource-inline-delegate-static-call-bug.md`
  (nuovo, pattern generalizzabile)

## Quality gate

- PHPStan `Modules/Employee`: **0 errori** (confermato anche post-commit).
- PHPMD: 26 issue pre-esistenti (complessità/naming/parametri inutilizzati), nessuna
  nel file toccato da questa story — non modificate, fuori scope per un fix a causa
  radice singola.
- PHPInsights: fallisce con `ComposerNotFound` (`composer.lock not found`) — bug
  tooling noto quando l'analisi è scoped a un singolo modulo, non causato da questa
  modifica. Non "aggiustato a caso" per restare nello scope.
- Pest (`Modules/Employee/tests --coverage`, `XDEBUG_MODE=coverage`): **10 passed / 6
  failed** su 16 totali. **Regressione** rispetto al baseline 2026-09-06 (15/16
  passed, in `docs/coverage.md`), ma **non causata da questa modifica**: i 5
  fallimenti aggiuntivi sono `BindingResolutionException` su
  `Spatie\EventSourcing\StoredEvents\EventSubscriber` e
  `LogicException: bootIfNotBooted ... while it is being booted` sui model
  `AbsenceRequest`/`WorkHour`/`BaseModel` — nessuno dei test falliti referenzia
  `WorkHourResource`/`WorkHourInfolist` (verificato con grep mirato). Sintomo coerente
  con la rifattorizzazione live delle classi base Xot da parte di altri agenti in
  questo momento (vedi memoria `project_xot_bootstrap_break_xotbaseresourceform_2026_09_07`).
  Non generabile una % di coverage fresca per via di questo bootstrap rotto — non è
  stato tentato un fix, fuori scope e fuori dai file di competenza di questa story.

## Git

- Commit `f86476c` su `dev`, solo i 3 file di cui sopra (verificato `git status` prima
  di `git add`, nessun file WIP altrui incluso).
- `git fetch laraxot dev` → nessuna divergenza (0 dietro, 1 avanti) → push diretto,
  nessun merge necessario.
- `git push -u laraxot dev` → **riuscito**, fast-forward `c76077f..f86476c`.

## Non toccato (di proposito)

- `AbsenceRequestResource.php`, `AbsenceRequestResource/Pages/ViewAbsenceRequest.php`,
  `AbsenceRequestResource/Schemas/AbsenceRequestForm.php`,
  `WorkHourResource/Schemas/WorkHourForm.php`,
  `WorkHourResource/Schemas/WorkHourInfolist.php` — WIP di un altro agente, ancora
  uncommitted a fine sessione, lasciato intatto.
- `docs/stories/phpstan-fix-1788768735.story.md` — file vuoto untracked di un altro
  agente, non toccato.
- Regressione Pest (Spatie EventSourcing / bootIfNotBooted) — documentata, non
  risolta: causa esterna al modulo, rischio di collisione con lock non posseduti su
  classi base Xot.
