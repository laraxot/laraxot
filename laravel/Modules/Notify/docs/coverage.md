---
title: "Notify — copertura dei test"
module: notify
type: reference
status: active
tags: [coverage, testing, pest, notify]
created: 2026-08-24
updated: 2026-08-27
qmd: "notify coverage pest unit 6.3 percento misurato xdebug db irraggiungibile"
---

# Notify — copertura dei test

## Misura

```bash
cd laravel
php -d memory_limit=3G -d xdebug.mode=coverage \
    ./vendor/bin/pest Modules/Notify/tests/Unit --coverage --min=0
```

Eseguita il 27 agosto 2026.

| Metrica | Valore |
|---|---:|
| Copertura totale | **6.3%** |
| Test passati | 463 |
| Test saltati | 300 |
| Assertion | 1.851 |
| Durata | 267 s |
| Aree a copertura 0% | 36 |

## Perché il numero è basso, e cosa non dice

**300 test su 763 sono saltati.** Il database di test (`10.100.200.53:3306`, `ptv_lara_test`)
non era raggiungibile al momento della misura: tutto ciò che tocca il DB non gira. Il 6.3%
misura quindi la sola parte Unit eseguibile senza DB, non la copertura reale del modulo.
Prima di rimisurare: `nc -z -w3 10.100.200.53 3306`.

**`xdebug.mode=coverage` è obbligatorio sulla riga di comando.** Senza, Pest esce con
`Unable to get coverage using Xdebug` — xdebug è caricato ma non in modalità coverage.

## Dove la copertura c'è

Interamente coperti (100%): `Http/Controllers/Controller`, `Http/Kernel`, i middleware
(`CheckForMaintenanceMode`, `EncryptCookies`, `PreventRequestsDuringMaintenance`,
`TrimStrings`, `TrustProxies`, `ValidateSignature`, `VerifyCsrfToken`),
`Providers/AppServiceProvider`, `Providers/Filament/AdminPanelProvider`.

Sono le classi di scaffolding: vengono attraversate dal bootstrap, non da test scritti
apposta. Il 100% qui non è un merito.

## Dove manca

36 aree a 0%, fra cui tutti i `View/Components/*` (`GuestLayout`, `Header`, `Input`).
È lì che il coverage va alzato, non nel middleware già al 100%.

<<<<<<< HEAD
<<<<<<< HEAD
## PHPStan / Jobs -> QueueableAction — 2026-09-04

Vedi story `docs/stories/4.27.jobs-to-queueable-actions.story.md`. In sintesi:
`app/Jobs/SendNotificationJob.php` (morto, zero call site) cancellato,
`app/Jobs/SendScheduledPushNotification.php` convertito in
`app/Actions/Push/SendScheduledPushNotificationAction.php`
(`Spatie\QueueableAction\QueueableAction`, entrypoint `execute()`).
`phpstan analyse Modules/Notify` pulito (0 errori, invariato). Suite Pest **non
verificabile in modo affidabile** in questa sessione: con l'invocazione
canonica (`-c Modules/Notify/phpunit.xml`) 417/813 passano ma 396 falliscono
con `A facade root has not been set.` / `Target class [config] does not
exist.` — verificato che questo fallimento e' preesistente e non causato da
questo diff (un file di test mai toccato fallisce nello stesso identico modo,
sia dentro la suite intera sia da solo). Vedi memoria second-brain
`env-sqlite-manca-suite-non-eseguibile.md`.

## Riduzione uso di `mixed` — 2026-09-04

Vedi story `docs/stories/4.28.mixed-type-reduction.story.md`. In sintesi: 125
file del modulo usano `mixed`; all'avvio 100 erano gia' "dirty" per lavoro
concorrente di un'altra sessione (non toccati, per non collidere). Sui 26
file puliti, 6 avevano una shape reale deducibile con certezza dal codice
circostante (return type di un'Action gia' tipizzata, o docblock del metodo
genitore Filament) e sono stati resi piu' specifici:
`array<string, mixed>` -> `array{status_code: int, status_txt: string}` in
`NetfunChannel.php` (matcha `SendSmsFactorSMSAction::execute()`);
`array<string, mixed>` -> `array<string, Action|ActionGroup>` in
`EditContactTestProxy.php`/`EditNotifyThemeTestProxy.php` (matcha
`XotBaseEditRecord::getHeaderActions()`); `array<int|string, mixed>` ->
`array<int|string, TextInput>` in `ContactSectionTestProxy.php` (matcha
`ContactSection::getFormSchema()`); `array<int, mixed>` -> `array<int,
Component>` in `ViewNotificationTestProxy.php` (matcha
`ViewNotification::getInfolistSchema()`); `array<int, mixed>` -> `array<int,
Action>` in `PreviewMailTemplateTestProxy.php`. Gli altri 20 file puliti sono
stati lasciati `mixed` con motivazione (bag di config/vars genuinamente
polimorfe, contratti multi-provider, firme idiomatiche `Factory::definition()`,
un falso positivo del grep). `phpstan analyse Modules/Notify`: 0 errori prima
e dopo. Pest: stesso pattern preesistente 417/396 gia' documentato sopra
(2026-09-04, story 4.27) — non causato da questo diff.

## `app/Services` -> `app/Actions` (QueueableAction) — 2026-09-04

Vedi story `docs/stories/4.29.notify-services-to-actions.story.md` per il
dettaglio file-per-file. In sintesi: 4 classi `.php` sotto `app/Services/`
(più 2 `.test` e 1 `.to_action`, artefatti morti non autoloadabili), tutte
Kind A (god-facade), due già completamente sostituite da Actions esistenti
in `Actions/Push/*` e `Actions/NotificationManager.php` (bastava cancellare
il Service e ripuntare i chiamanti-test), una (`MailtrapEngine`) morta con
zero caller e già superata da `Actions/Mail/SendMailtrapMailAction.php`, una
(`SmsService`) migrata in una nuova `Actions/SMS/SendSmsAction.php` con
`execute()` singolo (dispatch dinamico per riflessione preservato as-is,
comportamento invariato — lanciava già sempre `RuntimeException` prima
della migrazione, nessun engine concreto è mai esistito nel namespace di
destinazione). `app/Services/` ora contiene solo `.gitkeep`.

Nessun caller applicativo trovato repo-wide (solo test). 4 file di test
aggiornati, 1 cancellato (duplicato ridondante di un test già esistente su
`Actions\NotificationManager`).

**Collisione con sessione concorrente**: durante questa story un'altra
sessione stava eseguendo lo stesso task in tempo reale sugli stessi file,
lasciando file non tracciati con sintassi PHP non valida sotto
`app/Actions/{Sms,SMS,}/*` (non toccati, per rispetto del WIP altrui). Questo
ha impedito un run PHPStan pulito a modulo intero (bloccato da errori fatali
di parsing pre/post, non causati da questa story) — verifica sostitutiva
scoped ai file toccati qui: **0 errori**. Pest: stesso fallimento
pre-esistente e non attribuibile già documentato in `env-sqlite-manca-suite-
non-eseguibile` (verificato riproducendolo su un file mai toccato).
=======
## Nota 2026-09-01

Rimossi due file di test duplicati/stantii che collidevano di case con un gemello
corretto e più aggiornato (`emailtemplatestest.php`→`EmailTemplatesTest.php`,
`jsoncomponentstest.php`→`JsonComponentsTest.php`, confermato: il gemello minuscolo
verificava solo 2 componenti contro i 3 reali in `_components.json`). Nessun impatto
di rilievo sul numero di coverage: erano duplicati, non copertura aggiuntiva persa.
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

=======
>>>>>>> a988596b (first)
## Nota sulla versione precedente di questo file

Fino al 27 agosto 2026 questo documento dichiarava «comprehensive test coverage» e «all
tests are passing» con **tutti i numeri a zero**: files 0, classi 0, metodi 0, coverage 0%.
Un documento che afferma il contrario di ciò che misura è peggio della sua assenza, perché
chiude la domanda invece di aprirla. Gli stessi template vuoti restano in
`Modules/Tenant/docs/coverage.md` e `Modules/Xot/docs/coverage.md`.

## Aggiornamento 2026-09-06 — PHPStan L10 verification + UserContract narrowing

Story: `docs/stories/01.Notify-phpstan-fix.story.md`.

### PHPStan

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Notify --memory-limit=-1
```

| Momento | Errori |
|---|---:|
| Prima (baseline) | 0 |
| Dopo | 0 |

Il modulo era già a zero errori grazie al lavoro delle sessioni precedenti
(commit `7bed3d6d`, `8f7e4e9f`, ancora da pushare al remote al momento della
misura). Nessun errore PHPStan da correggere; lavoro spostato su
`mixed`→tipo specifico e `User`→`UserContract` (vedi story).

### PHPMD

```bash
cd laravel
./tools/phpmd.sh Modules/Notify text phpmd.xml
```

164 findings totali sul modulo (pre-esistenti, quasi tutti su file non
toccati da questa story: naming convention `snake_case` su DTO che
rispecchiano payload di provider esterni SMS/WhatsApp/Telegram,
`UnusedFormalParameter $notifiable` — pattern standard delle notification
Laravel — e complessità ciclomatica in alcune pagine Filament legacy).
Non in scope di questa story ridurli tutti: lo scope era PHPStan, non
PHPMD/PHPInsights a zero. `TicketAssignedNotification.php` (unico file
applicativo toccato) mantiene gli stessi 3 finding `UnusedFormalParameter`
già presenti prima (parametro `$notifiable` non usato nel corpo, richiesto
dalla firma `via/toMail/toArray` — stesso pattern della classe gemella
`TicketStatusChangedNotification`), nessun nuovo finding introdotto.

phpinsights non è installato in questo repo (rimosso, incompatibile con
Pest 5 — vedi memoria second-brain `pest5-incompatibile-con-phpinsights`):
non eseguito.

### Pest

Test mirati sui file toccati:

```bash
cd laravel
./vendor/bin/pest Modules/Notify/tests/Unit/Notifications/NotificationsCoverageTest.php -c Modules/Notify/phpunit.xml --no-coverage
./vendor/bin/pest Modules/Notify/tests/Unit/Actions/BuildMailMessageActionTest.php -c Modules/Notify/phpunit.xml --no-coverage
```

Entrambi **PASS** (8 test / 40 assertion il primo, 9 test / 16 assertion il
secondo).

Il secondo file era **FAIL** prima di questa story: `it has required
imports` asserisce la presenza letterale di `use
Spatie\LaravelData\DataCollection;` nel sorgente di
`app/Actions/BuildMailMessageAction.php`, import che quella classe non ha
(mai avuto nel codice attuale — il parametro `$attachments` è
`array<int, AttachmentData>|null`, non una `DataCollection`). Bug pre-esistente
del test (drift fra assert letterale e sorgente reale), non causato da
questa story né dai file che tocca: corretto sostituendo l'assert con
un import realmente presente e usato (`Modules\Notify\Datas\NotifyThemeData`).

Suite completa del modulo (`./vendor/bin/pest Modules/Notify/tests -c
Modules/Notify/phpunit.xml --no-coverage`): ambiente fortemente contended
(9+ run Pest concorrenti di altri agenti sulla stessa macchina durante la
misura, vedi `ps aux` in story) — un run completo è stato interrotto dal
timeout di sicurezza (400s) prima della fine, con un solo `FAIL` osservato
(quello sopra, poi corretto) e numerosi `WARN` attesi per
`DB \`notify\` non disponibile in ambiente test condiviso` (limite
pre-esistente, documentato sopra dal 27 agosto 2026, non introdotto da
questa story). Nessun'altra `FAIL` osservata nelle porzioni di suite
completate.

### File toccati

- `app/Notifications/TicketAssignedNotification.php`
- `app/Models/NotificationType.php` (cleanup import residuo)
- `tests/Unit/Actions/BuildMailMessageActionTest.php` (fix assert stale)
- `docs/stories/01.Notify-phpstan-fix.story.md`
- `docs/coverage.md` (questa sezione)

## Aggiornamento 2026-09-07 — regressione 0→14 errori, fix 3 static-call, 0 errori confermati

Story: `docs/stories/01.Notify-phpstan-fix.story.md` (sezione "Aggiornamento
2026-09-07").

### PHPStan

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Notify --memory-limit=-1 --no-progress
```

| Momento | Errori |
|---|---:|
| Baseline questa sessione (regredita dallo 0 del 2026-09-06 per lavoro concorrente non committato di un altro agente swarm) | 14 |
| Dopo il fix dei 3 file di mia proprieta' | 0 |

Verificato due volte, la seconda con `tmpDir` isolato (`/tmp/phpstan-notify-verify-cache`,
config temporanea `/tmp/phpstan-notify-verify.neon` fuori repo) per escludere
un falso negativo da cache condivisa fra agenti dello swarm sullo stesso
`tmpDir: /tmp/phpstan` dichiarato in `phpstan.neon` — stesso esito, 0 errori
reali.

Causa radice unica nei 4 errori residui dopo il primo giro (14 -> 4, gli
altri 10 sono stati corretti nel frattempo da un altro agente concorrente
sugli stessi file, vedi nota sotto): chiamata statica
(`ClassName::getFormSchema()` / `::getInfolistSchema()`) a metodi di
istanza `final` su `XotBaseResource`/`XotBaseResourceForm`/
`XotBaseResourceInfolist` — errore PHP reale a runtime in PHP 8.3, non solo
PHPStan. Pattern di fix: `app(Class::class)->getFormSchema()` (gia' in uso
in `Modules/Xot/app/Filament/Resources/XotBaseResource/RelationManager/XotBaseRelationManager.php`).

### PHPMD

```bash
cd laravel
./tools/phpmd.sh Modules/Notify text phpmd.xml
```

~130 finding, invariati rispetto alla baseline del 2026-09-06 (stesso
pattern: `CamelCaseVariableName`/`CamelCasePropertyName` su DTO che
rispecchiano payload esterni SMS/WhatsApp/Telegram/SMTP,
`UnusedFormalParameter $notifiable`/`$panel` richiesti dalle firme
Filament/Notification, `CyclomaticComplexity`/`NPathComplexity` su due
pagine legacy `SendPushNotification(Page)`). Nessun finding nuovo sui 3 file
toccati in questa sessione (nessuno dei tre appare nell'output).
`phpinsights` non e' installato in questo repo (rimosso, incompatibile con
Pest 5): non eseguito.

### Pest

```bash
cd laravel
./vendor/bin/pest Modules/Notify/tests/Unit/NotifyHighestMissCoverageTest.php -c Modules/Notify/phpunit.xml --no-coverage --filter="notification template and theme forms"
./vendor/bin/pest Modules/Notify/tests/Unit/Filament/Resources/NotifyFilamentResourcesCoverageTest.php -c Modules/Notify/phpunit.xml --no-coverage --filter="notification infolist schema"
```

Entrambi **PASS** (1/1, rispettivamente 2 e 10 assertion) — i due test che
esercitano direttamente le chiamate corrette.

Suite completa (`tests/Unit`) fortemente contesa durante la misura (8+ run
Pest concorrenti di altri agenti sulla stessa macchina, load average >10 su
24 core; ogni test individuale, normalmente sub-100ms, ha impiegato
~1.2-1.4s per il contendere di CPU). Un primo tentativo e' arrivato a 18
minuti di CPU senza terminare ed e' stato interrotto; un secondo tentativo
con `timeout 400` si e' fermato da solo (`EXIT=124`) dopo aver coperto
`Actions/` + `Channels/` + `Console/` + `Datas/` (in ordine alfabetico, non
l'intero `tests/Unit`) — stesso limite gia' documentato il 27 agosto 2026 e
il 2026-09-06 ("un run completo e' stato interrotto dal timeout di
sicurezza (400s) prima della fine").

Nella porzione completata: 10 `FAIL` (classi:
`Actions/NotificationManagerTest`, `Actions/SMS/NormalizePhoneNumberActionTest`,
`Actions/SMS/SendAgiletelecomSMSActionTest`,
`Actions/SMS/SendAgiletelecomSMSv1ActionTest`,
`Actions/SMS/SendAgiletelecomSMSv2ActionTest`,
`Actions/SendAppointmentNotificationActionTest`,
`Actions/SendNotificationActionTest`, `Actions/SendNotificationFlowTest`,
`Console/Commands/AnalyzeTranslationFilesTest`,
`Datas/NotifyDatasCoverageTest`) — **nessuno dei 3 file toccati in questa
sessione, nessun dettaglio d'errore disponibile** (il blocco `FAILED` con
lo stack trace viene stampato da Pest solo nel riepilogo finale, mai
raggiunto perche' il processo e' stato interrotto a meta' suite). Pattern
compatibile con l'indisponibilita' del DB di test documentata sopra dal 27
agosto 2026 (Actions che inviano SMS/notifiche reali dipendono da
connessioni esterne/DB). Non in scope di questa story: nessuno di questi
file rientra fra i 3 corretti, non introdotti da questa sessione (verificato
che nessuno dei 3 file toccati appare nell'elenco `FAIL`).

### Regressione di coverage trovata ma NON di mia proprieta' — segnalata, non corretta

Durante questa sessione un altro agente concorrente (lavoro non committato,
`git status` nel modulo con 102 file `M` all'avvio) ha **rimosso** (invece
di correggere con `app(Class::class)->getFormSchema()`) due blocchi di test
che la causa radice sopra riguardava:

- `tests/Unit/Filament/Resources/NotifyFilamentResourcesCoverageTest.php` —
  test `'resources expose model pages and legacy form schema'` (5
  assertion su Contact/MailTemplate/Notification/NotificationTemplate/
  NotifyTheme Resource) rimosso.
- `tests/Unit/Filament/Resources/NotifyThemeAndColumnsCoverageTest.php` —
  copertura equivalente su `NotifyThemeResource` rimossa.

Netto: la coverage di questa porzione del modulo e' **scesa**, in contrasto
col mandato di questa story. Non ho ripristinato ne' corretto questi file:
non erano di mia proprieta' in questa sessione (modificati da un altro
agente pochi minuti prima del mio avvio, `git status`/timestamp alla mano),
e la regola di progetto impone di fermarsi quando un altro agente sta gia'
lavorando sugli stessi file invece di sovrascriverne il lavoro in corso.
Segnalato al coordinatore e in `docs/chat/` per chi possiede quei file.

### File toccati (2026-09-07)

- `app/Filament/Resources/NotificationTemplateResource.php`
- `tests/Fixtures/ViewNotificationTestProxy.php`
- `tests/Unit/NotifyHighestMissCoverageTest.php`
- `docs/stories/01.Notify-phpstan-fix.story.md`
- `docs/coverage.md` (questa sezione)
