---
title: "Comment — PHPStan scope modulo owner"
type: concept
module: Comment
tags: [comment, phpstan, quality-gate, module-owner]
created: 2026-06-10
updated: 2026-06-10
qmd: "phpstan analyse Modules Comment laravel neon immutable tests included"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/22"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/23"
related:
  - ./phpstan-zero-errors-pest-assert.md
  - ./widget-ui-spatie-data.md
  - ../../../../docs/wiki/memories/phpstan-module-owner-scope.md
---

# PHPStan — scope modulo owner (Comment)

## Comando (sempre)

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Comment --no-progress
```

Usa **`laravel/phpstan.neon`** (default). **Vietato modificarlo** — niente `excludePaths` su `./Modules/**/tests/*`.

## Gate

**0 errori** su tutto `Modules/Comment` (app + config + **tests**).

Test Pest: `PHPUnit\Framework\Assert` statico, no `expect()` — vedi [phpstan-zero-errors-pest-assert.md](./phpstan-zero-errors-pest-assert.md).

## Filosofia

Owner scope = feedback locale sul modulo che tocchi. I test sono codice: vanno corretti, non esclusi dal neon globale.
