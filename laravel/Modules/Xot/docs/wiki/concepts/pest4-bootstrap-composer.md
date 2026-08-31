---
title: pest4 bootstrap composer autoload
description: Bootstrap Pest 4 Laraxot senza require_once — Composer autoload files + Helpers.php nativo.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ../../../../../../docs/bmad/stories/3.6.pest4-bootstrap-composer-helpers.story.md
  - ../../../../../../bmad-output/architecture.md
  - ./phpstan-pest-bridge-discipline.md
  - ../../../tests/XotBasePest.php
tags: [pest, pest4, composer, helpers, phpstan]
---

# Pest 4 bootstrap — Composer + Helpers.php (ADR-014, parzialmente superseded)

> **Aggiornamento Pest 5:** vedi [pest5-configuring-tests.md](./pest5-configuring-tests.md) — soluzione
> canonica attuale. Questo file resta come storico ADR-014 / migrazione da `require_once`.

> **Aggiornamento Pest 5 (2026-08-19):** strato 1 (`autoload.files` XotBasePest) **superseded** —
> usare classe PSR-4 `Modules\Xot\Tests\XotBasePest`. Vedi
> [pest5-configuring-tests.md](./pest5-configuring-tests.md) e story 3.7.

## Problema col `require_once`

```php
require_once __DIR__.'/../../Xot/tests/XotBasePest.php';
```

Funziona ma:

- duplicato in ogni modulo
- path fragile se si sposta `tests/`
- non usa le convenzioni Pest 4

## Soluzione (3 strati)

| Strato | Dove | Cosa |
|--------|------|------|
| 1 Cross-modulo | `Modules\Xot\Tests\XotBasePest` (PSR-4) | metodi statici — **non** `autoload.files` |
| 2 Dominio | `Modules/<Mod>/tests/Helpers.php` | create*, make*, assert* modulo |
| 3 Config | `Modules/<Mod>/tests/Pest.php` | opz. `pest()->extend(TestCase::class)->in('.')` con gate PHPStan (ADR-017) |

Pest 4 [`BootFiles`](https://github.com/pestphp/pest/blob/4.x/src/Bootstrappers/BootFiles.php) carica automaticamente:

- `tests/Pest.php`
- `tests/Helpers.php`
- `tests/Helpers/*.php`

## PHPStan

Dopo ogni modifica bootstrap:

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Xot/tests/XotBasePest.php --no-progress
```

Se si introduce `pest()->extend()` in `Pest.php`, aggiungere stub `pest(): \Pest\Configuration` in `PestStubs.php`.

## Versione Pest

Progetto su **Pest 4.7.x** (2026-08-19). Constraint: `^4.7` in Xot composer (ADR-015).

## Story implementazione

[`docs/bmad/stories/3.6.pest4-bootstrap-composer-helpers.story.md`](../../../../../../docs/bmad/stories/3.6.pest4-bootstrap-composer-helpers.story.md)

## Anti-pattern

- `tests/Support/` — vietato (ADR-002)
- `phpunit.xml bootstrap="tests/Pest.php"` — sbagliato; usare `vendor/autoload.php`
- `uses()->in()` nel bootstrap senza stub PHPStan — preferire per-file o `pest()->extend` stubbed

## Collegamenti

- [pest-php configuring tests](https://pestphp.com/docs/configuring-tests)
- [Architect handoff](../../../../../../docs/chat/architect-handoff-quality-testing-2026-08-19.md)
