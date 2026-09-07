---
title: "PHPStan Job: 4 -> 0 errori, JOB-4.32"
type: coordination-note
created: 2026-09-07
status: done
---

# PHPStan Modules/Job: 4 -> 0 (JOB-4.32)

## Esito

**Fatto**, non parziale. `./vendor/bin/phpstan analyse Modules/Job --no-progress
--memory-limit=-1` (cache pulita, `clear-result-cache` prima): **4 -> 0 errori**,
verificato 2 volte.

Commit `ae2ffaa` su `laraxot/dev` (remote `laraxot`, unico remote del modulo),
pushato pulito (nessuna divergenza al fetch, nessun merge necessario).
Story: `laravel/Modules/Job/docs/stories/4.32.phpstan-scheduleform-getformschema-instance-fix.story.md`.

## Contesto: modulo fortemente conteso

All'apertura, `Modules/Job` aveva un'estrazione Schemas/Tables live e molto
attiva da un'altra sessione — quasi tutte le Resource del modulo modificate,
timestamp entro pochi minuti dall'avvio di questo task (verificato con `git
status` + `stat` sui file, mtime piu' recente a ~7 minuti dall'inizio). Per non
duplicare/collidere, questo task si e' limitato **solo** ai 4 errori PHPStan
reali su file non toccati da quella WIP (verificato libero via
`bash bashscripts/lock/check.sh <path>` prima di ogni edit, e confermato via
`git status`/`git diff` che nessuno dei 3 file toccati faceva parte del set
dirty preesistente).

## I 4 errori e il fix

1-2. `app/Filament/Resources/ScheduleResource/Tables/SchedulesTable.php:48` —
`static::trans('buttons.history')`: `XotBaseResourceTable` non espone
`trans()` (solo `XotBaseResource` lo fa, via `TransTrait`). Fix: importata
`ScheduleResource` e sostituito con `ScheduleResource::trans(...)`, pattern
gia' in uso in `Modules/Comment/.../CommentsTable.php`.

3-4. `tests/Unit/JobScheduleFormCoverageTest.php` e
`tests/Unit/ScheduleFormCoverage100Test.php` — chiamavano
`ScheduleForm::getFormSchema()` staticamente. Una sessione concorrente (story
Xot 18.19, RISOLTO 2026-09-07) ha convertito
`XotBaseResourceForm::getFormSchema()` da statico a metodo di istanza in tutto
il repo. Fix: `(new ScheduleForm())->getFormSchema()`, stesso idioma gia'
usato in Lang/Media/Tenant/Notify.

Dettaglio completo, quality gate e note su pint/pest: vedi la story JOB-4.32.

## File toccati (solo questi, commit mirato)

- `app/Filament/Resources/ScheduleResource/Tables/SchedulesTable.php`
- `tests/Unit/JobScheduleFormCoverageTest.php`
- `tests/Unit/ScheduleFormCoverage100Test.php`
- `docs/stories/4.32.phpstan-scheduleform-getformschema-instance-fix.story.md` (nuovo)

Nessun `git add -A`: il resto del modulo (quasi tutte le Resource, `docs/coverage.md`,
`docs/stories/4.31...md`, `tests/Unit/JobExecuteCoverage50Test.php`, i due `.bak`
cancellati, `phpmd.ruleset.xml` untracked) e' rimasto intatto, e' WIP di un'altra
sessione — non aggiunto, non committato, non toccato.

## Quality gate

- **PHPStan**: 4 -> 0, cache pulita, verificato 2 volte.
- **PHPMD** (`Modules/Job/phpmd.ruleset.xml`, gia' presente ma untracked —
  non creato da questo task): nessuna violazione nuova sulle righe toccate.
- **Pint** (`--dirty`, file espliciti): fix meccanici sicuri sui 3 file
  (`fully_qualified_strict_types`). **Effetto collaterale noto**: `pint --dirty`
  ignora gli argomenti di path e ha toccato di striscio anche file dirty di
  altre sessioni in `Modules/UI`, `Modules/User`, `Modules/Media` — verificato
  diff per diff, solo fixer meccanici e sicuri (`no_unused_imports` dopo
  rimozione di codice morto gia' fatta da chi possiede quella WIP, yoda-style,
  nomi di classe accorciati via `use` gia' presente), nessuna logica toccata,
  non revertito (gia' un gotcha noto, vedi memoria
  `feedback_pint_dirty_scope_multi_agent.md`).
- **Pest**: nessun nuovo fallimento introdotto dai 3 file. I 2 test di questa
  story falliscono oltre il punto rotto da PHPStan sullo stesso bug
  preesistente e gia' documentato (`expectMethod()` in
  `JobExecuteCoverage50Test.php:70` ritorna `Mockery\CompositeExpectation`
  invece di `Mockery\Expectation`) — fuori scope, non causato da questo task.
- **Coverage numerica aggregata**: non ottenuta. Il report `--coverage` di
  Pest non viene stampato quando il run include i fallimenti preesistenti
  sopra descritti (verificato: un singolo file interamente verde stampa
  correttamente il report, es. 12.1% scoped a `Modules/Job` per
  `JobBusinessCoverageTest.php`; lo stesso comando su tutto `tests/Unit`
  con quei fallimenti non stampa nulla, con o senza `--min=0`). Blocco
  ambientale gia' segnalato nelle story precedenti JOB-4.30/4.31, non causato
  da questo task.
- **`docs/coverage.md` non toccato**: risultava gia' in editing attivo dalla
  stessa sessione concorrente (il suo diff attuale rimuove l'intera sezione
  "JOB-4.31" aggiunta dalla story precedente, stato intermedio non
  attribuibile con certezza). Scriverci sopra avrebbe rischiato di committare
  contenuto incompleto altrui insieme al mio. Dati di coverage raccolti
  comunque, riportati nella story JOB-4.32 invece che in `coverage.md`.

## Memoria aggiunta

`bashscripts/ai/wiki/memories/getformschema-static-to-instance-and-table-trans-gotchas.md`
— generalizza i 2 pattern (getFormSchema static->instance rompe i test scritti
prima della conversione; `Tables/*Table.php` non ha `trans()`, va chiamato sulla
Resource proprietaria) per chi incontra lo stesso errore in altri moduli durante
lo stesso swarm di estrazione Schemas/Tables.
