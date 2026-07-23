---
title: "E2E e .env.testing — stesso stack DB dei moduli"
type: concept
tags: [sixteen, testing, playwright, env, e2e]
created: 2026-06-12
updated: 2026-06-12
qmd: "Sixteen Playwright E2E env testing parity laravel pest"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/364"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/365"
related:
  - ../../../../Modules/Xot/docs/wiki/concepts/env-testing-parity-copy-env.md
  - ../../../../../../docs/wiki/bmad/architecture-env-testing-parity.md
---

# E2E Sixteen e database di test

I test browser (Playwright) contro il tema Sixteen condividono la **stessa religione** dei test Pest: niente SQLite inventato, niente DB di sviluppo.

## Principio

- Backend sotto test deve usare `APP_ENV=testing` e `laravel/.env.testing`
- Rigenerare env test dopo modifica `.env`: `./bashscripts/tools/sync-env-testing.sh`
- FO Folio + API ticket/geo leggono dati dal DB `_test` quando la suite E2E parte in modalità testing

## Collegamenti

- Canon moduli: [TESTING-ARCHITECTURE.md](../../../../Modules/docs/TESTING-ARCHITECTURE.md)
- BMAD: [architecture-env-testing-parity.md](../../../../../../docs/wiki/bmad/architecture-env-testing-parity.md)
