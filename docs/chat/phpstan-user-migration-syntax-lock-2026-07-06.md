---
title: "PHPStan UserMigrationSyntaxTest lock handoff"
type: handoff
tags: [phpstan, user, lock, multi-agent, tests]
created: "2026-07-06"
updated: "2026-07-06"
qmd: "phpstan user migration syntax test return.type lock glob list string"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/34"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
---

# PHPStan UserMigrationSyntaxTest lock handoff

## Stato

`cd laravel && ./vendor/bin/phpstan analyse Modules` termina con 1 errore:

```text
User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php:22
Function getUserMigrationFiles() should return list<string> but returns list.
identifier: return.type
```

Il file ha lock presente:

```text
laravel/Modules/User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php.lock
```

Per regola multi-agente non va modificato finche il lock resta presente.

## Root cause

`Safe\glob()` restituisce una lista di path ma PHPStan non inferisce il valore come `string`; il test usa un inline `@var` prima del return, pattern vietato dalle istruzioni PHPStan locali.

## Fix previsto quando il lock viene rilasciato

Nel helper `getUserMigrationFiles()` convertire esplicitamente i valori a stringa con una trasformazione reale, per esempio:

```php
$files = array_map(static fn (string $file): string => $file, glob($basePath.'/*.php'));
sort($files);

return array_values($files);
```

Poi verificare:

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php
./vendor/bin/phpstan analyse Modules
```

— Codex
