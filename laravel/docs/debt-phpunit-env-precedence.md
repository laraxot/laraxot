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
2. `Modules/Xot/tests/CreatesApplication.php` carica poi `.env.testing` (`DB_CONNECTION=mysql`, `DB_DATABASE=techplanner_data_test`, verificato con `grep DB_ laravel/.env.testing`).
3. Dotenv di Laravel **non sovrascrive** variabili di processo già impostate → le `<env>` di phpunit.xml vincono per le chiavi in comune.
4. Risultato: qualunque `XotBaseModel` con `$connection` di default (`'xot'`, non sovrascritto) risolve su SQLite `:memory:` vuoto invece che sul MySQL reale.
5. Verificato che `techplanner_data_test` **esiste ed è raggiungibile** (`mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES" techplanner_data_test` → tabelle presenti, es. `appointments`, `clients`) ma **non ha ancora nessuna tabella dei moduli gestionale_commesse** (`SHOW TABLES LIKE '%vehicle%'` → vuoto) — quindi due problemi in serie, non uno solo.

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
mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES LIKE '%vehicle%'" techplanner_data_test
# deve mostrare vehicles, vehicle_documents

cd laravel && ./vendor/bin/pest Modules/Vehicle/tests/Feature --no-coverage
# deve passare: VehicleCrudTest + AddVehicleDocumentActionTest
```

## Stato dei file già scritti

`Modules/Vehicle/tests/Feature/VehicleCrudTest.php` e `AddVehicleDocumentActionTest.php` sono porting **corretti** (verificati contro il codice reale) del sorgente `gestionale_commesse`, ma **non passano ancora** per questo blocco — da ri-eseguire dopo la fix, non da riscrivere.

## DoD

- [ ] `phpunit.xml` non scavalca più `.env.testing` per `DB_CONNECTION`/`DB_DATABASE` (o profilo alternativo documentato e in uso)
- [ ] `techplanner_data_test` ha le tabelle dei moduli GC enabled migrate
- [ ] `./vendor/bin/pest Modules/Vehicle/tests/Feature` passa senza errori di connessione

## Non fare

- Introdurre `RefreshDatabase`/`migrate:fresh` per "far funzionare" i test più in fretta — viola la regola "dati sacri" già in vigore nel repo.
- Riscrivere i 2 test Vehicle assumendo che siano sbagliati: sono corretti, il blocco è a monte.
