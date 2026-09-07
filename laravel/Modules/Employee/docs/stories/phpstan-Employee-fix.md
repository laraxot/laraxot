---
id: phpstan-Employee-fix
slug: phpstan-Employee
scope: [module:Employee, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

# PHPStan Employee — fix errore reale (2026-09-07)

## Contesto

Campagna "PHPStan zero" (`bashscripts/docs/prompts/03-quality-gates.md`). Al momento
di iniziare, il modulo era già fortemente conteso: WIP non committato di un altro
agente in corso su `AbsenceRequestResource.php`/`WorkHourResource.php`
(estrazione Schemas/Tables, rimozione override `getFormSchema()` inline morto —
pattern `pattern_xotbaseresource_schemas_class_wins_over_inline`), più uno story
file vuoto creato pochi minuti prima (`phpstan-fix-1788768735.story.md`, 2026-09-07
10:12) e un commit vuoto immediatamente precedente (`c76077f`, solo uno `.story.md`
da 0 byte). Deciso di non fare `git add -A` cieco e di limitare le modifiche solo ai
file necessari per l'errore PHPStan reale.

## Errori

- **Iniziali**: 1 (`./vendor/bin/phpstan analyse Modules/Employee --no-progress --memory-limit=-1`)
- **Finali**: 0

```
Line 29  app/Filament/Resources/WorkHourResource.php
         Static call to instance method
         Modules\Employee\Filament\Resources\WorkHourResource\Schemas\WorkHourInfolist::getInfolistSchema().
         🪪  method.staticCall
```

## Causa radice

`WorkHourResource::getInfolistSchema()` era un override *inline* (metodo di istanza,
`#[Override]`) che richiamava **staticamente** `WorkHourInfolist::getInfolistSchema()`,
che è invece un metodo di **istanza** (astratto in `XotBaseResourceInfolist`, atteso
essere chiamato tramite `$instance->getInfolistSchema()` da `HasXotInfolist`, dopo che
`XotBaseResource::infolist()` risolve la classe dedicata via `getInfolistClass()` e la
istanzia con `app(static::class)`). Oltre alla chiamata statica non valida, l'override
era comunque **dead code**: `XotBaseResource::infolist()` è `final` e delega sempre alla
classe dedicata `Schemas/WorkHourInfolist`, mai al metodo `getInfolistSchema()` definito
sulla Resource stessa (pattern già noto: `pattern_xotbaseresource_schemas_class_wins_over_inline`).

## Fix

Rimosso l'override morto in `WorkHourResource.php` (stesso pattern già applicato
dall'altro agente, nello stesso file, pochi minuti prima, per `getFormSchema()`) e gli
import diventati inutilizzati (`WorkHourForm`, `WorkHourInfolist`, `Override`).
Nessun `@phpstan-ignore`, nessuna baseline, nessun tocco a `phpstan.neon`, nessun
`mixed` introdotto.

## File toccati

- `laravel/Modules/Employee/app/Filament/Resources/WorkHourResource.php`

## Quality gate

- `./vendor/bin/phpstan analyse Modules/Employee --no-progress --memory-limit=-1` → **0 errori**.
- `./tools/phpmd.sh Modules/Employee text phpmd.xml` → 26 issue pre-esistenti, nessuna nel file
  toccato (complessità cicliomatica in Actions/Widgets non toccati, naming in
  `EmployeeServiceProvider`, parametri inutilizzati in `EmployeeController`). Non modificate:
  fuori scope per un fix PHPStan a causa radice singola.
- `./tools/phpinsights.sh analyse Modules/Employee --min-quality=80 --min-complexity=80 --min-architecture=80 --min-style=80` →
  fallisce con `ComposerNotFound` (`composer.lock not found`) — bug tooling noto quando si
  scopa l'analisi a un singolo modulo (vedi memoria
  `project_phpinsights_composer_lock_scoped_path`), non causato da questo fix.
- `./vendor/bin/pest Modules/Employee/tests --coverage` (via `XDEBUG_MODE=coverage`) →
  **10 passed / 6 failed** su 16 test totali. Regressione rispetto al baseline
  2026-09-06 (`docs/coverage.md`, 15 passed / 1 failed): i 5 fallimenti aggiuntivi sono
  `BindingResolutionException` su `Spatie\EventSourcing\StoredEvents\EventSubscriber`
  (`$storedEventRepository` non risolvibile) e `LogicException: bootIfNotBooted ... while
  it is being booted` sui model `AbsenceRequest`/`WorkHour`/`BaseModel`. Nessuno dei test
  falliti referenzia `WorkHourResource`/`WorkHourInfolist` (verificato con grep); la causa
  è a livello di bootstrap applicativo/EventSourcing condiviso, non del file toccato da
  questa story. Non è stato tentato un fix: fuori scope, coerente con
  `project_xot_bootstrap_break_xotbaseresourceform_2026_09_07` (altri agenti stanno
  rifattorizzando le classi base Xot in questo momento). Riportato in
  `docs/chat/2026-09-07-phpstan-employee-<esito>.md` e in `docs/coverage.md`.

## Acceptance Criteria

- [x] AC1 — `phpstan analyse Modules/Employee` exit code 0.
- [x] AC2 — Nessun file `.neon` toccato (`git diff --name-only` non lo contiene).
- [x] AC3 — Nessun `@phpstan-ignore`/baseline aggiunto.
- [x] AC4 — `declare(strict_types=1);` presente nel file toccato (già presente, non rimosso).
- [x] AC5 — PHPMD/PHPInsights eseguiti e documentati (PHPInsights bloccato da bug tooling noto,
      non causato da questa modifica).
- [ ] AC6 — Pest coverage in salita — **non verificabile in questa sessione**: regressione
      pre-esistente/concorrente sul bootstrap di test blocca il calcolo coverage fresco
      (0 test coverage report generato per via dei 6 fallimenti). Nessuna relazione causale
      con il file toccato da questa story.
- [x] AC7 — Commit + push solo dei file di questa story (nessun `git add -A` su WIP altrui).

## Owned File/Module Scope

`laravel/Modules/Employee/app/Filament/Resources/WorkHourResource.php` (unico file di
codice toccato da questa story). Non toccati i file WIP di un altro agente
(`AbsenceRequestResource*`, `WorkHourForm.php`, `WorkHourInfolist.php` diff-only riga
`use`, `docs/implementation*`) né lo story file vuoto `phpstan-fix-1788768735.story.md`.
