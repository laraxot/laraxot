---
title: "Decisioni architetturali PHPStan Modules — 2026-09-20"
type: architecture
status: accepted
created: 2026-09-20
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---
# Decisioni

## A1 — Il comportamento domina il silenziamento

Ogni segnalazione è trattata come informazione su un contratto. Si correggono tipi, precondizioni, return type e modellazione alla sorgente; non si introducono ignore, baseline, cast ciechi o configurazioni più permissive.

## A2 — Configurazione immutabile

`laravel/phpstan.neon` è l'unica configurazione usata dal comando e non viene modificata. Non vengono passati `-c` o `--level` e non vengono creati file Neon.

## A3 — Ownership per modulo

Le correzioni vivono nel modulo proprietario del dominio. I worker ricevono scope disgiunti per repository di modulo; i contratti condivisi vengono integrati centralmente dopo verifica dei chiamanti.

## A4 — Gerarchie framework esistenti

Le Resource e Page Filament continuano a usare le basi Xot/Lang previste dal progetto. Le correzioni statiche non introducono Controller FO, service generici o dipendenze dirette non già giustificate.

## A5 — Git solo avanti e WIP sacro

La storia Git è usata soltanto come evidenza tramite `git show`, `git log` e `git blame`. Nessun comando ripristina o scarta file. Una versione storica valida può essere reimplementata esplicitamente in avanti solo dopo il confronto con lo stato corrente.

## A6 — Verifica stratificata

La prova minima è sintassi, analisi mirata, test del comportamento e analisi globale. Un PHPStan verde senza test non dimostra correttezza funzionale; un test verde senza scansione globale non dimostra coerenza dei contratti.
