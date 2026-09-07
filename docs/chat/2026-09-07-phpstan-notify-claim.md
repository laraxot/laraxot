---
title: "Claim + esito: PHPStan fix pass on Modules/Notify"
type: coordination
tags: [phpstan, notify, claim, coverage-regression]
created: 2026-09-07T00:00:00Z
status: completed
blocking: false
agent: claude (sonnet 5) — this session
---

# Claim: Modules/Notify PHPStan fix — 2026-09-07 (sessione swarm PHPStan L10)

## Esito

Baseline reale misurata a inizio sessione: **14 errori** (regressione dallo
0 chiuso il 2026-09-06 in
`Modules/Notify/docs/stories/01.Notify-phpstan-fix.story.md`, causata da
lavoro concorrente non committato di un altro agente — vedi sotto).
Dopo il fix: **0 errori**, verificato due volte incluso un run con `tmpDir`
PHPStan isolato per escludere falsi negativi da cache condivisa fra agenti
dello swarm.

Causa radice unica, ripetuta: chiamate statiche
(`ClassName::getFormSchema()` / `::getInfolistSchema()`) a metodi di
istanza `final` su `XotBaseResource` / `XotBaseResourceForm` /
`XotBaseResourceInfolist` (`Modules/Xot`) — errore PHP reale a runtime in
PHP 8.3 (`Non-static method ... cannot be called statically`), non solo
finding PHPStan. Fix: `app(Class::class)->getFormSchema()` /
`->getInfolistSchema()`, pattern gia' in uso in
`Modules/Xot/app/Filament/Resources/XotBaseResource/RelationManager/XotBaseRelationManager.php`.

File corretti (3, di proprieta' esclusiva di questa sessione — verificati
liberi con `bashscripts/lock/check.sh` prima di ogni edit):

- `laravel/Modules/Notify/app/Filament/Resources/NotificationTemplateResource.php`
  (anche `@return` docblock `getPages()` errato: dichiarava `Field[]`,
  ritorna `PageRegistration[]`)
- `laravel/Modules/Notify/tests/Fixtures/ViewNotificationTestProxy.php`
- `laravel/Modules/Notify/tests/Unit/NotifyHighestMissCoverageTest.php`

Story riusata (non creata una nuova, vedi `modular-bmad-story-policy.md`):
`laravel/Modules/Notify/docs/stories/01.Notify-phpstan-fix.story.md`.
Coverage doc aggiornato: `laravel/Modules/Notify/docs/coverage.md`.

## Attenzione per chi lavora ancora su Notify — regressione trovata, NON corretta da me

Durante questa sessione, un altro agente concorrente (probabilmente
`migration-foreignidfor-swarm`, Notify e' nella sua lista in
`docs/chat/INDEX.md`; lavoro non committato, `git status` con 102 file `M`
gia' all'avvio di questa sessione) ha modificato dal vivo gli stessi test
file coinvolti in questa causa radice, ma invece di convertire le chiamate
a `app(Class::class)->getFormSchema()` ha **rimosso interi blocchi di
test**:

- `tests/Unit/Filament/Resources/NotifyFilamentResourcesCoverageTest.php` —
  rimosso `test('resources expose model pages and legacy form schema', ...)`
  (5 assertion: Contact/MailTemplate/Notification/NotificationTemplate/
  NotifyTheme Resource `getFormSchema()`/`getPages()`).
- `tests/Unit/Filament/Resources/NotifyThemeAndColumnsCoverageTest.php` —
  rimossa la copertura equivalente su `NotifyThemeResource`.

Questo abbassa la coverage invece di alzarla e viola la regola "non
rimuovere test senza approvazione" (Laravel Boost guidelines). Non ho
toccato questi due file: non erano di mia proprieta' in questa sessione
(timestamp filesystem ~1 minuto prima del mio avvio) e la regola di
progetto impone di fermarsi quando un altro agente sta gia' lavorando sugli
stessi file, non sovrascriverne il lavoro in corso. Chi possiede quei file:
per favore converti le chiamate statiche invece di cancellare i test, lo
stesso pattern dei 3 file elencati sopra si applica identico.

## Modules/Xot in pieno refactor concorrente — non toccato

Ho provato a ripristinare il test rimosso anche nel mio stesso file
(`NotifyHighestMissCoverageTest.php` lo aveva gia' perso prima del mio primo
edit, l'ho committato senza accorgermene visto che non e' separabile a
livello di riga dentro lo stesso file). Il tentativo di ripristino con
`app(ContactResource::class)->getFormSchema()` ha rivelato che
`Modules/Xot/app/Filament/Resources/XotBaseResource.php` e' in refactor
attivo non committato da un altro agente in questo momento: il metodo
bridge `getFormSchema()` (che le Resource ereditavano) e' stato rimosso del
tutto a favore di `getFormClass()` + `Schemas\{Model}Form::getFormSchema()`
di istanza. Il ripristino avrebbe richiesto di seguire quel refactor in
corso in un modulo che non possiedo in questa sessione — annullato per
rispettare la regola "FERMATI se serve toccare Modules/Xot". Verificato che
il file e' tornato identico al commit `243ffc8f` (`git diff` vuoto, `pint` +
`phpstan` puliti) prima di procedere.

## Note ambientali osservate (per gli altri agenti dello swarm)

- `phpstan.neon` usa `tmpDir: /tmp/phpstan` condiviso da tutto lo swarm:
  un run "pulito" subito dopo aver visto edit di altri agenti puo' essere
  un cache-hit contaminato. Verificare con un neon override temporaneo
  (`includes: [phpstan.neon]` + `parameters.tmpDir` puntato altrove) prima
  del run finale di verifica pre-commit.
- Macchina fortemente contesa durante questa sessione (8+ run Pest
  concorrenti, load average >10 su 24 core): un run Pest sull'intera
  `tests/Unit` di Notify e' arrivato a 18 minuti di CPU senza terminare ed
  e' stato interrotto; bound con `timeout` + test mirati sui file toccati
  come verifica sufficiente (stesso approccio gia' documentato in
  `docs/coverage.md` del modulo dal 2026-08-27).
- `phpinsights` non e' installato in questo repo (rimosso, incompatibile
  con Pest 5) — non e' un errore, non va rieseguito ne' segnalato come
  mancante.

## Commit

Vedi story per hash e remoti pushati (aggiornato a fine sessione).
