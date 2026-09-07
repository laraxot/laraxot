---
id: phpstan-HR-fix
slug: phpstan-HR
scope: [module:HR, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
agent: claude (sonnet 5)
---

## Problema

`./vendor/bin/phpstan analyse Modules/HR --no-progress --memory-limit=-1`
(cache cleared, `laravel/phpstan.neon` not modified) reported **1 error**:

```
app/Filament/Resources/AbsenceRequestResource.php:102
  Static method AbsenceRequestResource::getAbsenceTypeOptions() is unused.
  🪪 method.unused
```

### Contesto (collisione con lavoro concorrente)

Al momento del claim, `Modules/HR` aveva già un'estrazione canonical-structure
in corso e non committata (story `docs/stories/4.14.3.hr-canonical.story.md`,
`AbsenceRequestResource`/`EmployeeResource` → `Schemas/<Name>Form.php` +
`Tables/<Plural>Table.php`). Quell'estrazione aveva duplicato
`getAbsenceTypeOptions()` in `AbsenceRequestResource/Schemas/AbsenceRequestForm.php`
ma aveva lasciato la copia originale nel Resource, ora morta (nessun altro
chiamante, verificato con grep sull'intero modulo). `translateAbsenceType()` /
`normalizeAbsenceType()` / `ABSENCE_TYPE_ALIASES` sul Resource restano invece
vivi (usati da `Pages/ListAbsenceRequests.php` e da un `ReflectionMethod` nel
test `AbsenceRequestResourceTypeNormalizationTest`) — non toccati.

Lock check (`bashscripts/lock/check.sh laravel/Modules/HR/...`): FREE.
`docs/chat/hr-typecoverage-fix.md` (story 2.8, precedente, chiusa e già
pushata con commit `8e5c91e`) documentava un fix diverso (tipizzazione
costanti) sullo stesso file, già in `HEAD`.

## Soluzione

Rimosso il metodo morto `private static function getAbsenceTypeOptions(): array`
da `AbsenceRequestResource.php` (causa radice: duplicato superato
dall'estrazione in corso, non un sintomo da sopprimere — nessun
`@phpstan-ignore`, nessuna baseline, `phpstan.neon` non toccato).

Eseguito anche `./vendor/bin/pint Modules/HR/app/Filament/Resources/AbsenceRequestResource.php`
(scoped al singolo file, **non** `--dirty`, per non riformattare file di altri
agenti altrove nel repo) che ha rimosso 14 `use` ormai inutilizzati, lasciati
dalla stessa estrazione in corso (che aveva già spostato `getFormSchema()`/
`table()` fuori dal Resource senza pulire gli import).

## Regressione trovata e documentata, non corretta in questo scope

Il diff contro `HEAD` mostra che l'estrazione 4.14.3 in corso è stata
costruita su una base precedente al commit della story 2.8: reintroduce
`private const ABSENCE_TYPE_ALIASES` non tipizzata (con docblock duplicato) e
`private const ACCOUNT_FEATURE_ADMIN`/`..._OPERATOR` non tipizzate, e aveva
cancellato (non committato) l'intera sezione "story 2.8" da `docs/coverage.md`.
Non corretto qui (fuori scope: non assorbire la migrazione in corso di un
altro agente) — la sezione di `coverage.md` è stata ripristinata perché
rappresentava lavoro già completato e committato altrove; la tipizzazione
costanti resta segnalata per chi possiede la story 4.14.3.

## Verifica

- `php -l`: pulito.
- PHPStan `Modules/HR`, cache pulita: **0 errori** (era 1).
- PHPMD (`./tools/phpmd.sh Modules/HR text Modules/HR/phpmd.ruleset.xml`): solo
  reperti pre-esistenti nel modulo, nessuno su `AbsenceRequestResource.php`
  dopo il fix.
- PHPInsights (`./vendor/bin/phpinsights analyse Modules/HR`, config
  effimera in `/tmp` che rimuove solo `ForbiddenSecurityIssues` per il noto
  bug di scope su `composer.lock` quando si analizza un singolo modulo,
  `phpinsights.php` condiviso non toccato): Code 91.8, Complexity 93.9,
  Architecture 82.4, Style 84.3 — tutti ≥ 80, exit code 0.
- Pest `./vendor/bin/pest --testsuite=HR --no-coverage`: 6 failed / 2 passed,
  identico alla baseline post-story-2.8 (nessuna regressione). Fallimenti
  pre-esistenti non di competenza HR: `Database connection [hr] not
  configured`, `Base table or view not found: roles` (connessione
  `mariadb`, migrazioni Spatie Permission assenti in `workorder_data_test`).
- Coverage: vedi `docs/coverage.md` — impossibile un numero unico affidabile
  sull'intero modulo per via dei 6 test pre-esistenti bloccati; sul test
  verde che esercita la classe toccata, 48.5% linee coperte. Non può essere
  sceso: il metodo rimosso aveva 0 chiamanti di test sia prima che dopo.

## Git

**Non committato in questa sessione.** Il diff di `AbsenceRequestResource.php`
contro `HEAD` è inseparabile dall'estrazione 4.14.3 in corso e non committata
(stesso file, hunk sovrapposti — il metodo morto rimosso da questo fix esiste
solo perché 4.14.3 lo ha duplicato altrove). Committarlo comporterebbe
o (a) includere/pubblicare sotto un commit "fix phpstan" una feature
multi-file di un altro agente non interamente auditata in questa sessione,
oppure (b), se isolato via `git add -p`, produrre un commit intermedio rotto
(`getFormSchema()`/`table()` ancora inline e che chiamano
`self::getAbsenceTypeOptions()`, cancellato da questo fix). Il fix di codice
resta applicato e verificato nel working tree condiviso (vedi memoria
"shared filesystem, concurrent sessions") e verrà portato avanti
automaticamente al primo commit di chi possiede la story 4.14.3. Solo la
documentazione di questa sessione (`docs/coverage.md`,
`docs/stories/phpstan-HR-fix.md`) è stata committata direttamente su
`laraxot dev`.

## File toccati

- `app/Filament/Resources/AbsenceRequestResource.php` (rimozione dead code +
  pulizia import — **non committato**, vedi sopra)
- `docs/coverage.md` (committato)
- `docs/stories/phpstan-HR-fix.md` (questo file, committato)
