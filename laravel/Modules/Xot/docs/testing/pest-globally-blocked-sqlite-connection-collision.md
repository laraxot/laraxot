---
title: "Pest bloccato a livello globale — collisione connessione sqlite + Signature/tests/Pest.php"
type: bugfix
module: Xot
tags: [pest, phpstan, testing, sqlite, database, critical, project-wide]
created: 2026-07-27
updated: 2026-07-27
related:
  - ../../../../docs/chat/phpstan-modules-zero.md
  - ./hasxottable-tablesearch-property-conflict.md
  - ../../../Rating/docs/bugfix-supported-locale-translation-and-morph-table-name.md
---

# Pest bloccato a livello globale (2 cause distinte, entrambe risolte)

## Sintomo

**Qualunque** `./vendor/bin/pest {qualsiasi file}` falliva, per **qualunque modulo**, con:

```
SQLiteDatabaseDoesNotExistException
Database file at path [workorder_data_test] does not exist.
```

Questo non è un problema di un modulo specifico: bloccava l'intera suite Pest del
progetto, per ogni agente, indipendentemente da cosa si stesse testando.

## Causa 1 — collisione nome connessione `sqlite`

`config/database.php` (e le sue **5 copie di override per-tenant**:
`config/local/workorder/database.php`, `config/localhost/database.php`,
`config/net/sottana1/database.php`, `config/net/sottana2/database.php`,
`config/com/sottana1/database.php`) definivano la connessione `sqlite` così
(stub standard Laravel):

```php
'sqlite' => [
    'driver' => 'sqlite',
    'database' => env('DB_DATABASE', database_path('database.sqlite')),
    ...
],
```

`.env.testing` imposta `DB_CONNECTION=mariadb` + `DB_DATABASE=workorder_data_test`
(un **nome di database MariaDB**, non un path sqlite). Il fallback
`env('DB_DATABASE', ...)` nello stub sqlite leggeva **la stessa variabile**,
risultando in `database.connections.sqlite.database === 'workorder_data_test'`
— una stringa letterale che SQLite tenta di aprire come path di file e
ovviamente non trova.

Verificato con diagnostica diretta (bypassa Pest/PHPUnit, replica
`CreatesApplication::createApplication()`):

```php
php -r '... $app->boot(); echo config("database.connections.sqlite.database");'
// → "workorder_data_test" (bug) invece di un path .sqlite
```

Verificato anche: **zero** usi di `DB::connection('sqlite')` fuori da `tests/`
in tutto `Modules/` — la connessione `sqlite` esiste **esclusivamente** per il
pattern "fixture condivisa" (`Rating`/`Xot`/`Cms`/`HR`/... `TestCase::setUp()`
sovrascrive `database.connections.sqlite.database` con
`database_path('fixcity_data.sqlite')` a runtime). Nessun rischio per codice
di produzione.

### Fix

Rimosso il fallback `env('DB_DATABASE')` dallo stub `sqlite` in **tutti e 6**
i file (`config/database.php` + le 5 copie tenant): ora è sempre
`database_path('database.sqlite')` hardcoded (Laravel non lo userà mai
davvero, dato che ogni `TestCase` lo sovrascrive comunque prima di ogni query
reale — ma deve esistere come file, vedi Causa 2).

```php
// PRIMA (in tutti e 6 i file database.php)
'database' => env('DB_DATABASE', database_path('database.sqlite')),

// DOPO
// Non usare env('DB_DATABASE'): quella env var e' la connessione
// primaria (mysql/mariadb). I test la sovrascrivono sempre a runtime
// con la fixture condivisa (fixcity_data.sqlite).
'database' => database_path('database.sqlite'),
```

## Causa 2 — placeholder sqlite fisicamente assente

Anche con la Causa 1 risolta, il boot falliva ancora — stesso errore, path
diverso: `Database file at path [.../database/database.sqlite] does not exist`.
Qualcosa durante `$kernel->bootstrap(); $app->boot();` in
`CreatesApplication::createApplication()` prova la connessione di default
prima che il fixup per-modulo in `TestCase::setUp()` abbia la possibilità di
rimappare `sqlite` sulla fixture condivisa — il file deve esistere anche solo
come placeholder vuoto perché `SQLiteConnector` non fallisca subito.

### Fix

```bash
touch laravel/database/database.sqlite
```

File vuoto, **non tracciato con dati**, mai usato realmente (sovrascritto da
ogni `TestCase` prima di qualunque query) — è il default upstream del
boilerplate Laravel (`laravel/laravel` lo include di serie), qui semplicemente
mancante in questo repo.

## Causa 3 — `Modules/Signature/tests/Pest.php` richiede un file inesistente

Indipendente dalle prime due, ma **ugualmente bloccante a livello globale**:
il file `tests/Pest.php` root fa `require_once` del `Pest.php` di **ogni**
modulo (loop, non solo del modulo sotto test). `Modules/Signature/tests/Pest.php`
conteneva:

```php
require_once __DIR__.'/PestHelpers.php';
```

ma `Modules/Signature/tests/PestHelpers.php` **non esiste** — quindi
`./vendor/bin/pest {qualunque test di qualunque modulo}` falliva sempre,
anche per moduli che non c'entrano nulla con Signature.

Verificato **prima** di agire (per rispettare "correggi il test, non inventare
la cosa mancante"): nessun test in `Modules/Signature/tests/` chiama funzioni
con prefisso `signature*` (a differenza di `Cms`/`HR`/altri 10 moduli, che
hanno un vero `PestHelpers.php` con wrapper `cmsTest()`/`hrTest()`/ecc.
effettivamente usati dai propri test). La `require_once` era boilerplate
copiato da un altro modulo e mai popolato — non serviva a nulla.

### Fix

Rimossa la `require_once` non necessaria da `Modules/Signature/tests/Pest.php`
(non creato nessun `PestHelpers.php` fittizio, dato che nulla lo richiede
davvero).

## Verifica finale

```bash
cd laravel
./vendor/bin/pest Modules/Xot/tests/Unit/HasXotTableTest.php   # 2 passed
./vendor/bin/pest Modules/Rating/tests/Unit/RatingTest.php     # 3 passed
```

## Impatto

Questi 3 fix insieme sbloccano Pest per **l'intero progetto**, per **ogni
modulo**, per **ogni agente**. Prima di questi fix, il gate "controlla ogni
file con phpstan+phpmd+phpinsights+pest" richiesto per questo task era
impossibile da rispettare alla lettera per la parte Pest — ogni singolo
tentativo falliva con lo stesso errore di boot, indipendentemente dal file
realmente modificato.

## Canon / collegamenti

- `docs/chat/phpstan-modules-zero.md` — swarm coordination
- `Modules/Xot/docs/filament/hasxottable-tablesearch-property-conflict.md` — altro fix critico correlato nella stessa sessione (fatal di composizione trait)
- `Modules/Rating/docs/` — primo modulo verificato end-to-end dopo questo sblocco
