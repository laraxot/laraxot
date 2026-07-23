---
title: "Sixteen — test FO e .env.testing"
type: concept
tags: [theme-sixteen, testing, env, playwright]
created: 2026-06-12
updated: 2026-06-12
qmd: "Sixteen theme env testing playwright pest fo laravel"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/364"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/365"
related:
  - ../../../../../Modules/Xot/docs/wiki/concepts/env-testing-parity-copy-env.md
  - ../../../../../../docs/wiki/bmad/architecture-env-testing-parity.md
---

# Tema Sixteen e ambiente test

## Ruolo del tema

Sixteen non definisce un `.env` proprio per Pest. I test PHP dei moduli/temi usano il bootstrap Xot:

- `laravel/.env.testing` (generato da `.env`)
- `Modules\Xot\Tests\CreatesApplication`

## Playwright / smoke browser

I test browser (`PLAYWRIGHT_BASE_URL`) puntano al server dev (`http://127.0.0.1:8000`) — **non** sostituiscono `.env.testing` per Pest.

## Quando rigenerare `.env.testing`

Dopo aver cambiato connessioni DB in `laravel/.env` (es. nuovo `DB_DATABASE_GEO`):

```bash
./bashscripts/tools/sync-env-testing.sh
```

## Canon

- Modulo Xot: [env-testing-parity-copy-env.md](../../../../../Modules/Xot/docs/wiki/concepts/env-testing-parity-copy-env.md)
- BMAD: [architecture-env-testing-parity.md](../../../../../../docs/wiki/bmad/architecture-env-testing-parity.md)
