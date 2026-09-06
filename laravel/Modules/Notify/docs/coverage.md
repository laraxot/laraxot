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
