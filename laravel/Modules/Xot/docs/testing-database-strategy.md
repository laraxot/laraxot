---
title: Strategia database per i test
description: Regola canonica sui database usati dalla suite di test. Repliche MySQL con suffisso _test, tre connessioni isolate, file di ambiente generato, divieto di SQLite, RefreshDatabase e migrate:fresh.
module: Xot
area: testing
status: canonical
audience: [developer, ai-agent]
tags:
  - testing
  - database
  - mysql
  - pest
  - env
  - refresh-database
related:
  - Modules/Xot/docs/database-testing-rule.md
  - Modules/Xot/docs/testing-strategy.md
  - Modules/Xot/docs/database-testing-pattern.md
  - Modules/Xot/docs/testing-refresh-database-rule.md
  - Modules/Xot/docs/testing-migrate-env-testing-deep-dive.md
  - Modules/Xot/tests/XotBasePest.php
  - bashscripts/tools/sync-env-testing.sh
---

# Strategia database per i test

Questo documento e' la fonte unica sulla configurazione del database nella suite di test.
Sostituisce e assorbe i contenuti di `database-testing-rule.md`, `testing-strategy.md` e
`database-testing-pattern.md`, che ora rimandano qui.

## Regola in una riga

I test girano su repliche MySQL dei database di sviluppo, con suffisso `_test` sul solo nome
del database. Mai SQLite, mai `RefreshDatabase`, mai `migrate:fresh`.

## Perche' non SQLite

Il progetto interroga LimeSurvey con query SQL scritte a mano e usa funzionalita' specifiche
di MySQL. SQLite diverge dal dialetto di produzione e introduce classi di errore che non
esistono a runtime:

1. Dialetto differente: funzioni di data, `JSON_*`, `GROUP_CONCAT`, cast impliciti.
2. Vincoli di integrita' gestiti in modo diverso, con foreign key spesso disattivate.
3. Locking del file su piu' connessioni nominate, che produce `database is locked`.
4. Test verdi su SQLite e rossi in produzione, ossia il peggior esito possibile.

Testare sullo stesso motore della produzione elimina l'intera categoria di problemi.

## Le tre connessioni

L'applicazione usa un'architettura multi database. Ogni connessione ha la propria replica di
test. Le connessioni sono registrate dinamicamente da `TenantServiceProvider::registerDB()`,
quindi `config/database.php` resta standard e non va modificato a mano.

| Variabile | Connessione | Sviluppo | Test |
|---|---|---|---|
| `DB_DATABASE` | `mysql` (default) | `quaeris_data` | `quaeris_data_test` |
| `DB_DATABASE_USER` | `user` | `quaeris_user` | `quaeris_user_test` |
| `DB_DATABASE_LIMESURVEY` | `limesurvey` | `quaeris_survey` | `quaeris_survey_test` |

Host, porta, utente e password sono identici a quelli di sviluppo. Cambia solo il nome del
database. Configurare una sola connessione su `_test` e lasciare le altre sui database di
sviluppo significa scrivere sui dati reali durante i test.

## File di ambiente

Il file canonico caricato dai test e' `laravel/.env.sqlite`.

Il nome e' storico e fuorviante: il file non usa SQLite, contiene `DB_CONNECTION=mysql`.
Non va rinominato senza aggiornare `Modules/Xot/tests/XotBasePest.php`,
`App\Application::environmentFile()` e il generatore. `laravel/.env.testing` viene generato
con contenuto identico e resta valido come convenzione nativa Laravel, per esempio con
`php artisan migrate --env=testing`.

Entrambi i file sono generati, non si editano a mano:

```bash
./bashscripts/tools/sync-env-testing.sh          # rigenera .env.sqlite e .env.testing
./bashscripts/tools/sync-env-testing.sh --check  # exit 1 se non allineati a .env
```

Il generatore copia `laravel/.env` riga per riga, forza `APP_ENV=testing` e aggiunge il
suffisso `_test` a ogni chiave `DB_DATABASE*`. L'operazione e' idempotente: un valore che
termina gia' in `_test` non riceve un secondo suffisso.

## Come viene caricato

Il collegamento e' esplicito e vive in un unico punto:

1. `laravel/phpunit.xml` imposta `APP_ENV=testing` e nient'altro di relativo al database.
2. `App\Application::environmentFile()` intercetta `APP_ENV=testing` e restituisce
   `.env.sqlite`, con fallback su `.env.testing`.
3. Le tre connessioni leggono i propri valori da quel file.

Le credenziali di database non vanno duplicate in `phpunit.xml`. Una copia hardcoded copre
una sola connessione, lascia le altre due sui database di sviluppo e va fuori sincrono al
primo cambio di credenziali.

Verifica rapida dell'ambiente effettivamente risolto:

```bash
cd laravel && APP_ENV=testing php artisan tinker --execute="dump(app()->environmentFile(), config('database.connections.mysql.database'), config('database.connections.user.database'), config('database.connections.limesurvey.database'));"
```

## Migrazioni

Le migrazioni si eseguono una volta, fuori dalla suite, contro i database `_test`:

```bash
cd laravel && php artisan migrate --env=testing
```

Si usa il comando generico, senza `--path` di modulo: i modelli hanno relazioni che
attraversano i moduli e i test hanno bisogno dello schema completo.

## Divieti

### RefreshDatabase

`RefreshDatabase` esegue una migrazione a ogni classe di test e, su database condivisi con
dati di riferimento, li distrugge. Su piu' connessioni nominate il comportamento e' inoltre
non deterministico. Per l'isolamento si usa `DatabaseTransactions`, che apre e annulla una
transazione per test.

```php
// Vietato
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// Corretto
uses(Illuminate\Foundation\Testing\DatabaseTransactions::class);
```

### migrate:fresh

`migrate:fresh` elimina tutte le tabelle. Non va eseguito ne' dai test ne' dagli script di
supporto. Lo schema dei database `_test` si aggiorna in avanti con `migrate`.

### SQLite in memoria

Nessun test deve forzare `driver => sqlite` o `database => :memory:` nella configurazione,
ne' in `setUp()` ne' nei `TestCase` di modulo.

## Checklist per un nuovo test

- [ ] `laravel/.env.sqlite` presente e allineato: `sync-env-testing.sh --check`
- [ ] Tutte e tre le connessioni puntano ai database `_test`
- [ ] Il file di test usa `DatabaseTransactions`, mai `RefreshDatabase`
- [ ] Nessuna sovrascrittura di `database.connections.*` verso SQLite
- [ ] Le classi si risolvono con `XotData::make()->getUserClass()`, non con `new User()`
- [ ] Nessuna credenziale di database aggiunta a `phpunit.xml`

## Riferimenti

- Bootstrap Pest condiviso: `Modules/Xot/tests/XotBasePest.php`
- Generatore ambiente: `bashscripts/tools/sync-env-testing.sh`
- Risoluzione file di ambiente: `laravel/app/Application.php`
- Configurazione PHPUnit: `laravel/phpunit.xml`
