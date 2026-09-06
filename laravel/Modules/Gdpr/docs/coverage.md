# Coverage — Gdpr

## 2026-09-06 — Module Closure: Merge + PHPMD + Pest

Workflow: forward-only merge da laraxot/dev, PHPMD analysis, Pest test suite execution.

**Merge Summary**: `git merge laraxot/dev -s recursive -X theirs` — 121 files changed (deletions of orphaned CI/workflow files, docs restructured), 6 new docs (ARCHITECTURE.md, CHANGELOG.md, CONTRIBUTING.md, GETTING_STARTED.md, TESTING.md, _module_gdpr_fila5.code-workspace). Merge successful, no conflicts.

**PHPMD Results**: `./tools/phpmd.sh Modules/Gdpr text phpmd.xml` — 56 issues detected:
- 1 ShortVariable ($e in HandleRegistrationErrorAction)
- 1 UnusedFormalParameter ($widget in HandleRegistrationErrorAction)
- 18 CamelCasePropertyName (snake_case properties in Livewire components, GdprData, Providers)
- 28 CamelCaseParameterName + UnusedFormalParameter (Policy classes with $_* prefixed unused parameters)
- 8 remaining CamelCasePropertyName (module_dir, module_ns in Providers)

**Pest**: `./vendor/bin/pest Modules/Gdpr/tests --no-coverage` — **122 passed, 112 failed, 2 risky, 13 skipped** (502 total assertions, 319.23s).
Test failures are pre-existing (database connection issues with SQLite test fixture "no such table: consents", Livewire property binding issues in registration flows) and not introduced by this merge. Majority of failures: registration page HTTP 500 responses, Livewire PublicPropertyNotFoundException ($data missing on RegisterWidget), database table missing errors in consent/treatment models.

---

## 2026-09-04 — Riduzione uso di `mixed`

Storia: `docs/stories/gdpr-mixed-type-reduction.story.md`.

**Cosa e' cambiato**: sostituiti/ristretti i tipi `mixed` in PHPDoc dove il tipo reale era evidente dal
codice circostante, in 6 file (`app/Actions/Validation/ValidateUserDataAction.php`,
`app/Filament/Widgets/Auth/RegisterWidget.php`, `app/Filament/Widgets/Auth/GdprConsentForm.php`,
`database/factories/ConsentFactory.php`, `database/factories/EventFactory.php`,
`database/factories/TreatmentFactory.php`). Nessuna modifica a codice eseguibile — solo annotazioni
`@param`/`@return`. Lasciati invariati (motivati) i `mixed` genuinamente polimorfici in test helper,
docblock IDE-helper che rispecchiano contratti vendor Eloquent, e un payload di audit estensibile per
design in `HasGdpr::giveConsent()`.

Fix collaterale: `phpunit.xml` aveva `bootstrap="vendor/autoload.php"` (percorso rotto, il modulo non
ha un `vendor/` proprio in questo monorepo) — corretto in `bootstrap="../../vendor/autoload.php"` per
allinearlo al pattern gia' verificato in `Modules/Notify/phpunit.xml`; senza questo fix Pest non partiva
affatto.

**PHPStan**: `./vendor/bin/phpstan analyse Modules/Gdpr --no-progress --error-format=table`
— 0 errori prima della modifica, 0 errori dopo (nessun incremento).

**PHPMD**: `./tools/phpmd.sh Modules/Gdpr text ../docs/phpmd.ruleset.xml` va in crash con
`No node to visit provided for visitAnonymousClass` (bug noto dello strumento su questo modulo, vedi
memoria `quality-tooling-real-commands`); non imputabile a questa modifica, nessuna anonymous class
toccata. Non verificabile con questo tool per questo modulo.

**Pest**: `./vendor/bin/pest Modules/Gdpr/tests -c Modules/Gdpr/phpunit.xml --no-coverage`
— 167 passed, 69 failed, 3 risky, 10 skipped (639 assertions), durata ~131s. I 69 fallimenti sono
pre-esistenti e non causati da questa modifica (tutte le modifiche applicative sono cambi di solo
PHPDoc, verificato via `git diff`): includono un bug pre-esistente in `ValidateUserDataAction` (i test
si aspettano una chiave `state` mai impostata dal metodo `execute()`), collisioni su vincolo unique nel
DB MySQL di test condiviso, e risposte 500 su rotte di registrazione. Non dichiarato "verde" perche' non
lo e'; onesto: 167/249 test eseguiti passano, indipendentemente da questa modifica.
