---
title: PHPStan — analisi per modulo (cwd laravel)
type: rule
tags: [phpstan, quality-gate, comment]
qmd:
  index: true
created_at: 2026-06-10
updated_at: 2026-06-10
---

# PHPStan — analisi per modulo

## Comando (obbligatorio in sessione Comment)

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Comment
```

Usa **solo** `laravel/phpstan.neon` (default con cwd `laravel/`).

## Regole sacre su `phpstan.neon`

| Vietato | Perché |
|---------|--------|
| Modificare `laravel/phpstan.neon` per “sistemare” un modulo | Neon = contratto repo; fix nel codice/test |
| `excludePaths: ./Modules/**/tests/*` | I test del modulo vanno analizzati (Pest + stub, no Mockery fragile) |
| Ignorare globalmente `method.internalClass` | Usare `Assert::` nei test |

## Gate Comment

`Modules/Comment` (app **+** tests) → **0 errori**, livello max.

Pattern test: [phpstan-pest-test-patterns](../concepts/phpstan-pest-test-patterns.md)

## Post-edit

`./tools/phpmd.sh` · `./tools/phpinsights.sh` — stesso cwd `laravel/`.
