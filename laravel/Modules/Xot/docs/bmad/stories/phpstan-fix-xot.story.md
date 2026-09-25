---
title: "PHPStan fix Xot — Safe function imports in AssetActionsTest"
type: story
module: Xot
slug: phpstan-fix-xot
status: done
created: 2026-09-23
updated: 2026-09-23
---

# PHPStan fix Xot — Safe function imports in AssetActionsTest

## Story

Come manutentore del modulo Xot, voglio che `phpstan analyse Modules/Xot`
(level max) torni 0 errori, eliminando i finding "Function X is unsafe to
use" su `tests/Unit/Actions/File/AssetActionsTest.php`.

## Perché

- PHPStan gira a level **max** senza baseline: ogni errore e' una regressione.
- I finding (25 occorrenze) riguardavano funzioni filesystem che possono
  restituire `false` invece di lanciare eccezione: `mkdir`,
  `file_put_contents`, `unlink`, `rmdir`, `chmod`, `file_get_contents`.
- Convenzione codebase: `use function Safe\X;` (pacchetto
  `thecodingmachine/safe`, presente in `vendor/`), una per riga, ordine
  alfabetico, dopo gli import di classe.

## Fix applicato

File: `tests/Unit/Actions/File/AssetActionsTest.php`

Aggiunto blocco import dopo `use ReflectionMethod;`:

```php
use function Safe\chmod;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\mkdir;
use function Safe\rmdir;
use function Safe\unlink;
```

## Esito

- `php -l`: nessun errore di sintassi.
- PHPStan: `php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules/Xot
  --error-format=raw --no-progress` → **exit 0, 0 errori**.
- Pest gate: `./vendor/bin/pest
  Modules/Xot/tests/Unit/Actions/File/AssetActionsTest.php` → **exit 0,
  6 passed (8 assertions)**.
