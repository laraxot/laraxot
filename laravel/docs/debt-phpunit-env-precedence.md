---
title: "Debito D17 — phpunit.xml sqlite :memory: scavalca .env.testing (mysql)"
type: how-to
tags: [technical-debt, pest, phpunit, env, sqlite, mysql, connection]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt D17 phpunit env precedence sqlite memory mysql xot connection vehicle test"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-phpunit-modules-registration.md"
  - "./debt-pest-field-service-coverage.md"
  - "./gestionale-commesse-technical-debt-resolution-guide.md"
  - "../Modules/Vehicle/tests/Feature/VehicleCrudTest.php"
---

# Debito D17 — `phpunit.xml` forza sqlite `:memory:`, vince su `.env.testing` (mysql)

## Perché (distinto da D13)

[D13](./debt-phpunit-modules-registration.md) assume che `vendor/bin/pest Modules/X/tests` **passi in locale** e riguarda solo la registrazione in CI/testsuite. Questo debito è più a monte: **anche l'esecuzione locale fallisce** per qualunque modello con connessione di default `xot` (la maggioranza dei moduli `XotBaseModel`), quindi D13 non può essere chiuso finché D17 non lo è — ordine di dipendenza: **D17 blocca D13 e D6**.

## Come verificato (2026-07-24)

Portati 2 test Feature reali per `Vehicle` da `/var/www/_bases/gestionale_commesse/tests/Feature/Vehicles/` (namespace/model/Action verificati esistenti e corretti in questo repo). Eseguiti:

```bash
cd laravel && ./vendor/bin/pest Modules/Vehicle/tests/Feature --no-coverage
```

Errore: `SQLSTATE[HY000]: General error: 1 no such table: vehicles (Connection: xot, Database: :memory:)`.

Causa isolata passo per passo:

1. `laravel/phpunit.xml` → `<env name="DB_CONNECTION" value="sqlite"/>`, `<env name="DB_DATABASE" value=":memory:"/>` (variabili di **processo**, impostate prima del boot app).
2. `Modules/Xot/tests/CreatesApplication.php` carica poi `.env.testing` (`DB_CONNECTION=mysql`, `DB_DATABASE=workorder_data_test`, verificato con `grep DB_ laravel/.env.testing`).
3. Dotenv di Laravel **non sovrascrive** variabili di processo già impostate → le `<env>` di phpunit.xml vincono per le chiavi in comune.
4. Risultato: qualunque `XotBaseModel` con `$connection` di default (`'xot'`, non sovrascritto) risolve su SQLite `:memory:` vuoto invece che sul MySQL reale.
5. Verificato che `workorder_data_test` **esiste ed è raggiungibile** (`mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES" workorder_data_test` → tabelle presenti, es. `appointments`, `clients`) ma **non ha ancora nessuna tabella dei moduli gestionale_commesse** (`SHOW TABLES LIKE '%vehicle%'` → vuoto) — quindi due problemi in serie, non uno solo.

## Come risolvere

**Passo 1 — precedenza env.** Due opzioni:

- **(A, consigliata)** Rimuovere `DB_CONNECTION`/`DB_DATABASE` da `<php>` in `phpunit.xml`, lasciando `.env.testing` come unica fonte di verità. Prima di farlo: `grep -rn "sqlite\|:memory:" laravel/Modules/*/tests` per capire se qualche test *dipende* dal fallback sqlite in-memory (test puramente unitari, senza schema) e andrebbe aggiustato a parte.
- **(B)** Profilo `.env.testing.mysql` dedicato + `--env=testing.mysql` esplicito quando si eseguono Feature test contro dati reali. Più macchinoso, tenere come piano B.

**Passo 2 — schema mancante.** Dopo il passo 1, migrare i moduli gestionale_commesse nel DB di test, uno alla volta (stessa disciplina "mai `migrate` globale" di [debt-legacy-migrations-triage.md](./debt-legacy-migrations-triage.md)):

```bash
cd laravel
php artisan migrate --path=Modules/Vehicle/database/migrations --env=testing --no-interaction
# ripetere per ogni modulo GC enabled, stesso ordine di dipendenza già documentato per l'ambiente dev
```

## Come verificare la fix

```bash
mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES LIKE '%vehicle%'" workorder_data_test
# deve mostrare vehicles, vehicle_documents

cd laravel && ./vendor/bin/pest Modules/Vehicle/tests/Feature --no-coverage
# deve passare: VehicleCrudTest + AddVehicleDocumentActionTest
```

## Stato dei file già scritti

`Modules/Vehicle/tests/Feature/VehicleCrudTest.php` e `AddVehicleDocumentActionTest.php` sono porting **corretti** (verificati contro il codice reale) del sorgente `gestionale_commesse`, ma **non passano ancora** per questo blocco — da ri-eseguire dopo la fix, non da riscrivere.

Stesso blocco riconfermato **2026-07-24** portando i test `Auth` (22 Feature + 1 Unit) e `HR` (5
Feature) da `gestionale_commesse` — vedi `Modules/Auth/docs/gestionale-commesse-port.md` e
`Modules/HR/docs/gestionale-commesse-port.md` § "Test migrati dal sorgente". Anche qui: unico
fixture sqlite condiviso disponibile (`database/fixcity_data.sqlite`, usato da
`prepareSharedFixcitySqliteForTesting()` in `Modules/Gdpr`/`Modules/Activity`) appartiene a un
progetto diverso e non contiene `users`/`roles`/`employees`/`absence_requests`: non riusabile per
Auth/HR. Risultato onesto di quella sessione: Auth 11-12 passed / ~62-67 failed (quasi tutti
`QueryException` "no such table"); HR 1 passed / 5 failed / 2 skipped (bug di tipo scoperto a
parte, vedi doc HR). Nessun `RefreshDatabase` introdotto per aggirare il blocco.

### ✅ Pattern che FUNZIONA davvero (verificato 2026-07-24, `Modules/Customer`)

Contrariamente al risultato negativo su Auth/HR sopra, lo stesso identico meccanismo
(`prepareSharedFixcitySqliteForTesting()`, definito in `Modules/Xot/tests/XotBaseTestCase.php:249`)
**funziona per `Customer`**: 33/33 test passano (`vendor/bin/pest Modules/Customer/tests --no-coverage`
rieseguito e confermato due volte). Il `TestCase.php` del modulo:

```php
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    protected $connectionsToTransact = ['sqlite', 'xot', 'customer'];

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();
        parent::setUp();
    }
}
```

`prepareSharedFixcitySqliteForTesting()` punta le connessioni `driver=sqlite` al file
`database/fixcity_data.sqlite` (via `config()->set` + `DB::purge`), **non esegue migrazioni lui stesso**
— le tabelle del modulo vengono comunque rese disponibili (probabilmente via convenzione Testbench che
carica le migration del package/modulo in `getPackageProviders()`/`defineDatabaseMigrations()` a monte).

**Perché non ha funzionato su Auth/HR ma sì su Customer**: causa precisa isolata (2026-07-24, riprovato
su `WorkOrder` che HA GIÀ il pattern `prepareSharedFixcitySqliteForTesting()` nel suo `TestCase.php` ma
fallisce comunque su 16/43 test): **il file sqlite condiviso `database/fixcity_data.sqlite` non ha la
tabella `users` migrata**. `Customer`/`Platform` passano perché i loro test non creano mai un vero
`User::factory()->create()` (solo entità proprie del modulo); `WorkOrder` fallisce esattamente sui test
che fanno `User::factory()->create()` (es. `WorkOrderInterventionSearchTest.php`) con
`SQLSTATE[HY000]: ... no such table: users`. Non è quindi un problema di ordine-migrazione cross-modulo:
è che **nessuno ha mai migrato `users` (e probabilmente altre tabelle piattaforma: `roles`,
`employees`, ecc.) dentro `fixcity_data.sqlite`**, che a quanto pare appartiene a un altro progetto
("fixcity") e non ha mai avuto lo schema applicativo di questo repo.

**Fix consigliato, unico e definitivo** (va fatto una volta sola, non modulo per modulo): migrare lo
schema applicativo core (`users`, `roles`, `permissions`, e le tabelle dei moduli nativi già enabled)
dentro `database/fixcity_data.sqlite`, oppure — più pulito — creare un file sqlite dedicato a questo
progetto (es. `database/testing_shared.sqlite`) con lo schema completo migrato una tantum, e cambiare
`prepareSharedFixcitySqliteForTesting()` (o una sua variante) per puntare lì invece che al file
"fixcity" di un altro progetto.

**Tentativo 2026-07-24 (fermato di proposito, non eseguito)**: verificato che
`Modules/User/database/migrations/` contiene **numerose migration duplicate/storiche** per `users` e
`roles` (es. `2024_01_01_000001_create_users_table.php`, `2024_01_01_000002_create_users_table.php`,
`2024_01_01_000006/000007_create_users_table.php`, `2026_02_13_172135/172136_create_users_table.php` —
almeno 5 varianti diverse solo per `users`, più altrettante per `roles`/`permissions`). Lanciare
`migrate --path=Modules/User/database/migrations` in blocco su `fixcity_data.sqlite` rischierebbe di
applicare la migration "sbagliata" (una vecchia versione dello schema) o fallire per conflitti, con
`fixcity_data.sqlite` **condiviso da più agenti in parallelo** in questo momento — un fallimento a metà
lo corromperebbe per tutti. **Non eseguito di proposito.**

**Prossimo passo corretto per chi riprende D17**:
1. Determinare quale migration `users`/`roles`/`permissions` è quella "attiva" nell'ambiente reale
   (`php artisan migrate:status` contro il DB mysql di sviluppo/test, non contro sqlite)
2. Lanciare **solo quelle specifiche** (path esplicito al singolo file, non alla cartella) contro
   `fixcity_data.sqlite`, con lock preso su quel file per tutta la durata dell'operazione
3. Verificare con `php -r "..."` (PDO, sqlite3 CLI non è installato in questo ambiente) che le tabelle
   compaiano e abbiano le colonne attese prima di considerare il fix chiuso

## DoD

- [ ] `phpunit.xml` non scavalca più `.env.testing` per `DB_CONNECTION`/`DB_DATABASE` (o profilo alternativo documentato e in uso)
- [ ] `workorder_data_test` ha le tabelle dei moduli GC enabled migrate
- [ ] `./vendor/bin/pest Modules/Vehicle/tests/Feature` passa senza errori di connessione

## Non fare

- Introdurre `RefreshDatabase`/`migrate:fresh` per "far funzionare" i test più in fretta — viola la regola "dati sacri" già in vigore nel repo.
- Riscrivere i 2 test Vehicle assumendo che siano sbagliati: sono corretti, il blocco è a monte.
