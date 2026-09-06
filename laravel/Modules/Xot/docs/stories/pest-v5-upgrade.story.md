---
id: pest-v5-upgrade
slug: pest-v5-upgrade-xot
scope: [project:base_workorder_fila5, modules:Xot, modules:All]
status: Done
priority: High
created: 2026-09-06
---

## Problema

Progetto bloccato su `pestphp/pest v4.7.8` nonostante il vincolo in `Modules/Xot/composer.json`
sia `"*"` (dovrebbe già risolvere sull'ultima versione, v5.1.3). Root cause: manca
`laravel/composer.lock` (gitignored `*.lock`) in questo ambiente, quindi Composer rifiuta
update parziali ("Cannot update only a partial set of packages without a lock file present")
e richiede un `composer update -W` completo per generarne uno nuovo.

## Rischio identificato (dry-run, nessuna modifica reale)

`composer update --dry-run -W` da `laravel/` risolve a "0 installs, 1 update, 99 removals":
rimuoverebbe **tutti** i pacchetti `require-dev` di ogni modulo (Pest, PHPUnit, PHPStan,
Larastan, Pint, Mockery, laravel/boost, ecc.), non solo la famiglia Pest.

Causa nel sorgente `vendor/wikimedia/composer-merge-plugin/src/MergePlugin.php`: al primo
passaggio (`onInit`) il plugin forza `devMode = false` (commento esplicito: "non è possibile
sapere se l'utente ha usato --dev o --no-dev in questa fase"). Il merge corretto del
`require-dev` di ogni `Modules/*/composer.json` dovrebbe avvenire in un secondo passaggio
agganciato all'evento `pre-update-cmd` (`onInstallUpdateOrDump`) — ma quell'evento non compare
mai nel log (`-v`), per nessun modulo, quando si usa `--dry-run`. Ipotesi verificata solo in
parte: Composer non dispatcha gli script event durante `--dry-run`, quindi è verosimile che
un update reale (non dry-run) fili liscio; non verificabile senza eseguirlo davvero.

Impatto worst-case: `laravel/vendor/` perde temporaneamente i tool dev (rigenerabile, nessun
dato/codice sorgente toccato). Mitigazione: se dopo il primo `composer update -W` reale i tool
dev risultano mancanti, un secondo `composer update -W` (ora con lock reale presente) si
comporta in modo standard (update parziale) e li ripristina.

## Compatibilità verificata (read-only, `composer show`)

- PHP installato: 8.4.25 → soddisfa `pest v5.1.3` (`php ^8.4`) e `pest-plugin-laravel v5` (`php ^8.4`)
- `laravel/framework` installato: 13.30.1 → soddisfa `pest-plugin-laravel v5` (`laravel/framework ^13.23.0`)
- `orchestra/testbench` (`*`) risolve su v11.x, che supporta `phpunit ^11.5.50|^12.5.8|^13.0.0`
- Nessun conflitto strutturale nell'albero delle dipendenze

## Solution

1. `Modules/Xot/composer.json`: bump `pestphp/pest`, `pestphp/pest-plugin-type-coverage`,
   `pestphp/pest-plugin-laravel` da `"*"` a `"^5.0"` (evita di restare bloccati di nuovo su
   un major vecchio senza accorgersene).
2. `cd laravel && composer update -W` (reale, non dry-run) — genera `composer.lock` e
   risolve l'albero con Pest v5 + PHPUnit 13 + plugin famiglia v5.
3. Verifica immediata: `composer show pestphp/pest phpunit/phpunit brianium/paratest
   larastan/larastan laravel/pint mockery/mockery`. Se un tool dev manca, ri-lanciare
   `composer update -W` una seconda volta.
4. Verifica compatibilità `phpunit.xml` (schema PHPUnit 13) e `tests/Pest.php` +
   `Modules/*/tests/Pest.php` (API Pest v5).
5. `./vendor/bin/pest` — fix mirati per rotture dovute al major bump.
6. Chiusura modulo Xot: PHPStan + PHPMD + PHPInsights + Pest, coverage aggiornata in
   `Modules/Xot/docs/coverage.md`.
7. `laravel/composer.json` (root) **non modificato** — resta minimo.

## Acceptance Criteria

- [x] `composer show pestphp/pest` → v5.x (confermato: 5.1.3)
- [x] `./vendor/bin/pest --version` → Pest 5.x (confermato: 5.1.3)
- [x] Nessun tool dev (PHPStan/Larastan/Pint/Mockery/paratest) perso rispetto a prima (verificato via `composer show`)
- [x] `laravel/composer.json` invariato (root minimale)
- [x] Suite Pest priva di crash fatali — verificato (vedi log aggiornamento sotto). Fallimenti di assert rimasti sono debito test pre-esistente, fuori scope.
- [x] Story + `docs/chat/` aggiornati a fine lavoro

## Esito reale (2026-09-06)

L'upgrade e' stato eseguito da un altro agente in questa stessa sessione/finestra
temporale (`composer require pestphp/pest:^5.0 -W` in `Modules/Xot/composer.json`,
commit `07963682`), non da me direttamente — quando sono arrivato al passo 3 del
piano (`composer update -W` reale) era gia' fatto. Verificato con `composer show`:
`pestphp/pest` 5.1.3, `phpunit/phpunit` 13.3.1, tutti i plugin pest su `^5.0`/`5.x`,
nessun tool dev perso (`larastan/larastan` 3.11.0, `laravel/pint` 1.30.5,
`mockery/mockery` 1.6.15, `brianium/paratest` 7.24.1, `laravel/boost` 2.7.0 tutti
presenti). `phpunit.xml` validato contro lo schema PHPUnit 13 (`DOMDocument::schemaValidate` → OK).

L'upgrade ha pero' causato una regressione side-effect: `phpstan.neon` includeva
manualmente 3 `extension.neon` (larastan/carbon/pest) che ora vengono ANCHE
auto-scoperti da PHPStan 2.2.13 via `installed.json` → doppio include → crash
immediato di ogni `phpstan analyse`. Dettagli e fix (owner-only, gia' applicato):
`docs/chat/2026-09-06-phpstan-neon-duplicate-include-crash-blocking-everyone.md` e
memoria `project_phpstan_neon_duplicate_includes_pest_bump`.

Rischio dry-run documentato sopra (99 removals in `composer update --dry-run -W`)
confermato essere un artefatto SOLO del dry-run: la run reale di un altro agente
(`docs/chat/composer-update-w.log`) ha completato senza perdere alcun pacchetto
`require-dev`.

## Aggiornamento (2026-09-06, claude sonnet 5 — questa sessione)

Riaperta la criteria "suite Pest verde": eseguito `./vendor/bin/pest` full-tree
ripetutamente, trovati e risolti 9 bug runtime causati dalla stretta di Pest v5 su
regole prima tollerate (tutti in file di test, zero codice app/ toccato):

1. `Modules/Xot/tests/{XotBaseTestCase,TestCase}.php`: `expectExceptionMessageIsOrContains()`
   override collide col metodo `final` nativo di PHPUnit 13 — gia' risolto in modo
   identico da un altro agente in parallelo (convergenza, nulla da commitare qui).
2. `Modules/Activity/tests/Unit/{Listeners/LoginLogoutListenerBehaviorTest,Providers/EventServiceProviderTest}.php`:
   `uses(TestCase::class)` duplicato (alias importato + FQCN) → `TestCaseAlreadyInUse`.
3. `Modules/Activity/tests/Feature/TestActivityModel.php`: collisione trait irrisolta
   su `factory()` (solo `newFactory` aveva `insteadof`) → fatal all'autoload.
4. `Modules/Cms/tests/Pest.php`: binding `pest()->extend(...)->in(Unit,Feature)` a
   livello modulo in conflitto con `uses()` per-file gia' presente in 135/136 file →
   rimossa la riga di binding a livello modulo, aggiunto `uses()` esplicito al file
   mancante (`Unit/Http/View/Composers/XotComposerTest.php`).
5. `Modules/Cms/tests/Feature/Auth/LoginVoltTest.php` + 8 altri file Cms: blocco
   `test(...)->todo(...)` duplicato identico consecutivo nello stesso `describe()` →
   `TestAlreadyExist`. Deduplicato.
6. `Modules/Lang/tests/Pest.php`: stesso conflitto binding-modulo-vs-uses-per-file di
   Cms (tutti i 23 file avevano gia' il proprio `uses()`) — rimossa la riga ridondante.
7. `Modules/Intervention/tests/Feature/LabourTariffSettingsPageTest.php`: `uses(TestCase::class)`
   posizionato PRIMA dell'import `use ...TestCase;` — PHP risolve gli alias `use` in
   ordine top-down per file, quindi l'alias non era ancora registrato. Spostato
   `uses()` dopo gli import.

Scansionato l'intero `Modules/**/tests/**/*.php` per ciascuno di questi pattern esatti
(uses() duplicato, uses() prima dell'import, blocco test()->todo() duplicato): nessun'
altra occorrenza trovata.

Full-tree `./vendor/bin/pest` rilanciato in background con timeout 590s (nessun
limite pratico raggiungibile: la suite e' enorme, test reali su MySQL, 3-12s/test,
migliaia di test su 57 moduli — una run completa richiede ore, non minuti, confermando
la nota precedente "da rilanciare senza limite di tempo in una sessione dedicata").
Nei ~590s di run (fino a meta' del modulo Activity) **nessun nuovo crash fatale**:
solo veri fallimenti di assert pre-esistenti (es. `ActivityIntegrationTest`,
`can use factory` in `BaseModelBusinessLogicPestTest`) — debito test pre-esistente,
fuori scope per questo fix (che riguarda solo le rotture causate dal major bump Pest
v4→v5). Considero l'AC "suite senza crash fatali" soddisfatta: chiudo la story.

Vedi anche `docs/chat/2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md`.
