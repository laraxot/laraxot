---
title: "application_data.sqlite — bootstrap Pest (fail-fast)"
type: concept
module: Xot
tags: [testing, pest, sqlite, xotbasetestcase, database-transactions]
created: 2026-07-12
updated: 2026-07-12
qmd: "application_data.sqlite prepareSharedApplicationSqliteForTesting pest hang empty sqlite fail fast"
issues:
  - "https://github.com/laraxot/<nome repository>/issues/372"
discussions:
  - "https://github.com/laraxot/<nome repository>/discussions/273"
related:
  - ../concepts/module-testcase-xotbase-hierarchy.md
  - ../../../../../../docs/wiki/memories/data-sacred-no-destructive-db.md
  - ../../../../../../bashscripts/ai/wiki/rules/testing-modules-pest.md
---

# application_data.sqlite — bootstrap Pest

## Scopo

I moduli con `DatabaseTransactions` chiamano `prepareSharedApplicationSqliteForTesting()` **prima** di `parent::setUp()` per condividere un unico PDO SQLite su `laravel/database/application_data.sqlite` ed evitare `database is locked`.

## Sintomo (root cause hang)

| Condizione | Effetto |
|------------|---------|
| File assente | Eccezione Laravel / hang su connessione |
| File **0 byte** (`touch`) | SQLite non valido → lock / `busy_timeout` 10s → **Pest sembra bloccato** senza output |
| Header non `SQLite format 3` | Stesso comportamento |

`touch database/application_data.sqlite` **non** crea un database utilizzabile.

## Guard in XotBaseTestCase

`assertApplicationSqliteReadyForTesting()` verifica esistenza, dimensione minima e magic header **prima** di `DB::purge()` e della condivisione PDO. Fallisce con `RuntimeException` e messaggio operativo.

## Ripristino (dati sacri)

| Consentito | Vietato |
|------------|---------|
| Copia da backup team / altro clone migrato | `migrate:fresh`, `db:wipe`, `migrate --force` |
| `cd laravel && php artisan migrate` (forward-only, una volta) | `touch` sul file sqlite |
| `RefreshDatabase` / `DatabaseMigrations` nei test | Pest parallelo sullo stesso file |

### Verifica locale

```bash
ls -la laravel/database/application_data.sqlite
# atteso: size >> 0 (tipico ~1MB+), non 0 byte

cd laravel
php -r 'echo file_get_contents("database/application_data.sqlite", false, null, 0, 16);'
# atteso: SQLite format 3
```

### Dopo migrate forward-only

```bash
cd laravel
php artisan migrate
./vendor/bin/pest Modules/Comment/tests/Unit/CommentSanitizerTest.php --configuration phpunit.xml
```

## Moduli che usano prepareSharedApplicationSqliteForTesting

Activity, Comment, Gdpr, Job, Rating, UI (e altri con stesso pattern nel `TestCase`).

## Helper traduzioni correlato

`require_translation_file()` in `Modules/Xot/helpers/Helper.php` — carica file lang con `require`, valida chiavi `string`, ritorno `array<string, mixed>` (PHPStan L10). Consumer: loader lang split (es. Job `job.php`).

## Backlink

- [module-testcase-xotbase-hierarchy.md](../concepts/module-testcase-xotbase-hierarchy.md)
- [testing-modules-pest.md](../../../../../../bashscripts/ai/wiki/rules/testing-modules-pest.md)
