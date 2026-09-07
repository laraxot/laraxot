---
title: "PHPStan Modules/HR — 1 errore reale corretto (working tree), commit codice deferito"
type: coordination
tags: [phpstan, hr, method.unused, collision, story-phpstan-hr-fix, story-4.14.3]
created: 2026-09-07T19:15:00+02:00
agent: claude (sonnet 5)
status: parziale (fix applicato+verificato, non committato per entanglement con WIP altrui)
---

# PHPStan Modules/HR — esito

## Errori

`./vendor/bin/phpstan analyse Modules/HR --no-progress --memory-limit=-1` (cache
pulita): **1 → 0**.

L'unico errore reale era `method.unused` su
`AbsenceRequestResource::getAbsenceTypeOptions()`, orfano dopo che
un'estrazione canonical-structure in corso e non committata (story
`Modules/HR/docs/stories/4.14.3.hr-canonical.story.md`,
`AbsenceRequestResource`/`EmployeeResource` → `Schemas/`/`Tables/`) l'aveva
duplicato in `AbsenceRequestResource/Schemas/AbsenceRequestForm.php` senza
rimuovere l'originale. Verificato con grep sull'intero modulo: nessun altro
chiamante. `translateAbsenceType()`/`normalizeAbsenceType()`/
`ABSENCE_TYPE_ALIASES` sullo stesso Resource restano vivi (usati da
`Pages/ListAbsenceRequests.php` e da un test via `ReflectionMethod`) — non
toccati.

## Collisione trovata (importante per chi possiede la story 4.14.3)

Al claim, `docs/chat/hr-typecoverage-fix.md` (story 2.8, tipizzazione
costanti) risultava gia' chiusa e pushata (`8e5c91e`). La story 4.14.3 in
corso pero' e' stata costruita su una base **precedente** a quel commit:
il suo diff contro `HEAD` mostra un apparente "ripristino" di
`private const ABSENCE_TYPE_ALIASES`/`ACCOUNT_FEATURE_ADMIN`/`..._OPERATOR`
alla forma non tipizzata (con un docblock duplicato reintrodotto), e aveva
cancellato (uncommitted) l'intera sezione "story 2.8" da
`Modules/HR/docs/coverage.md`. Non e' una regressione fatta apposta — e'
l'effetto di essere partiti da un checkout stale. Non e' visibile da uno
scan scoped al modulo (`typeCoverage.constantTypeCoverage` e' una regola
project-wide, no-op su subpath). Ho **ripristinato** la sezione doc
cancellata (lavoro gia' completato altrove, rischio di perderlo altrimenti)
ma **non** ho toccato le costanti stesse: fuori scope, file di proprieta'
della migrazione in corso.

## Fix applicato

Rimosso il metodo morto da `AbsenceRequestResource.php` + pulizia con
`./vendor/bin/pint` scoped al singolo file (non `--dirty`) che ha tolto 14
`use` ormai inutilizzati lasciati dalla stessa estrazione.

## Quality gate (tutto sul modulo intero)

- PHPStan: 0 errori (era 1).
- PHPMD (`Modules/HR/phpmd.ruleset.xml`): solo reperti pre-esistenti, nessuno
  su `AbsenceRequestResource.php` dopo il fix.
- PHPInsights (workaround noto per `ForbiddenSecurityIssues`/`composer.lock`
  su scope singolo modulo): Code 91.8, Complexity 93.9, Architecture 82.4,
  Style 84.3 — tutti ≥ 80.
- Pest `--testsuite=HR --no-coverage`: 6 failed / 2 passed, identico alla
  baseline post-story-2.8 (nessuna regressione). Fallimenti pre-esistenti,
  non di competenza HR: `Database connection [hr] not configured`,
  `Base table or view not found: roles` (migrazioni Spatie Permission
  assenti in `workorder_data_test`).
- Coverage: dettaglio in `Modules/HR/docs/coverage.md`. Non ottenibile un
  numero unico affidabile sull'intero modulo (bloccato dagli stessi 6 test
  pre-esistenti); sul test verde che esercita la classe, 48.5% linee
  coperte — non puo' essere sceso (il metodo rimosso aveva 0 chiamate di
  test sia prima che dopo).

## Perche' non ho fatto commit del codice

Il diff di `AbsenceRequestResource.php` e' inseparabile dall'estrazione
4.14.3 in corso (stesso file, hunk contigui: il metodo morto che rimuovo
esiste solo perche' loro lo hanno gia' duplicato altrove). Committarlo
comporterebbe o pubblicare sotto un commit "fix phpstan" una feature
multi-file altrui non interamente auditata da me, o produrre — se isolato
con `git add -p` — un commit intermedio rotto. Il fix resta applicato e
verificato nel working tree condiviso (stesso pattern gia' seguito da
story 2.8 su questo stesso modulo): chiunque possieda/finalizzi la story
4.14.3 lo portera' avanti automaticamente al proprio commit.

Ho committato e pushato (fast-forward, nessun conflitto) **solo**:
- `Modules/HR/docs/coverage.md`
- `Modules/HR/docs/stories/phpstan-HR-fix.md`

Commit `90ef8d2` su `laraxot/module_hr_fila5` dev, hash pre-push `8e5c91e`.

Nota anche registrata in
`bashscripts/ai/wiki/memories/phpstan-method-unused-after-extraction-entangled-commit.md`
(commit `1ba01234` su `bashscripts_fila5` dev, pushato).

## File NON toccati (di proposito)

- `app/Filament/Resources/EmployeeResource.php` (0 errori phpstan, parte
  della stessa migrazione 4.14.3)
- `app/Filament/Resources/{AbsenceRequestResource,EmployeeResource}/{Schemas,Tables}/*`
- `app/Filament/Resources/{AbsenceRequestResource/Pages/ViewAbsenceRequest.php,EmployeeResource/Pages/ViewEmployee.php}`
  (WIP diversa, gia' segnalata da 2 sessioni precedenti come "proprietario
  non identificato, non toccare")
- `docs/stories/4.14.3.hr-canonical.story.md`, `phpmd.ruleset.xml`
  (untracked, di proprieta' della migrazione in corso)
- `README.md.lock` (deleted, causa/proprietario non identificati)

## Per chi riprende la story 4.14.3

Prima di fare `git add -A && commit`: rivedere che `ABSENCE_TYPE_ALIASES`/
`ACCOUNT_FEATURE_ADMIN`/`ACCOUNT_FEATURE_OPERATOR` in
`AbsenceRequestResource.php` tornino tipizzati (`private const array`/
`private const string`, senza `@var` duplicato) prima di committare, o la
tipizzazione della story 2.8 verra' persa di nuovo.
