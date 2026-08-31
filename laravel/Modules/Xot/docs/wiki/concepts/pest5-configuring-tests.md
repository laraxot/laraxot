---
title: pest 5 configuring-tests — bootstrap senza require_once
description: Tre strati bootstrap Laraxot — XotBasePest PSR-4, Helpers.php dominio, pest()->extend con gate PHPStan.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ./pest4-bootstrap-composer.md
  - ./phpstan-pest-bridge-discipline.md
  - ./pest-official-plugins.md
  - ./pest-phpstan-plugin.md
  - ../../../../../docs/bmad/stories/3.7.pest5-configuring-tests-extend.story.md
  - ../../../../../bmad-output/architecture.md
tags: [pest, pest5, configuring-tests, xotbasepest, phpstan]
---

# Pest 5 — configuring-tests (ADR-016, ADR-017)

Fonte ufficiale: [configuring-tests](https://pestphp.com/docs/configuring-tests).

## Problema del `require_once`

```php
require_once __DIR__.'/../../Xot/tests/XotBasePest.php';
```

Eliminato (2026-08-19): path fragile, `function_exists()` per full-suite, helper fuori PSR-4.

## Soluzione a tre strati (LOCKED)

| Strato | Dove | Cosa |
|--------|------|------|
| 1 Cross-modulo | `Modules\Xot\Tests\XotBasePest` (PSR-4) | `XotBasePest::assertThrows()`, `assertTableHas()`, … |
| 2 Dominio | `Modules/<Mod>/tests/Helpers.php` | create*, make*, assert* modulo — BootFiles Pest 5 |
| 3 Binding TestCase | `Modules/<Mod>/tests/Pest.php` | opz. `pest()->extend(TestCase::class)->in('.')` |

**Vietato:** `tests/Support/`, `autoload.files` per bootstrap test, `phpunit.xml bootstrap="tests/Pest.php"`.

## Strato 1 — XotBasePest (classe, non file include)

```php
use Modules\Xot\Tests\XotBasePest;

XotBasePest::assertThrows(fn () => $action->execute(), InvalidArgumentException::class);
```

Mappa PSR-4 già in `Modules/Xot/composer.json`: `"Modules\\Xot\\Tests\\": "tests/"`.

## Strato 3 — `pest()->extend()` (**consigliato**) vs `uses()` per file

Con **`pestphp/pest-plugin-phpstan` v5** e `phpstan.neon` senza `includes:` duplicati:

| forma | PHPStan su Pest.php | Runtime |
| --- | --- | --- |
| `pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature')` | OK | **consigliato** — un binding per modulo |
| `uses(TestCase::class);` in ogni file | OK | ammesso, ma ripetitivo |

**Regola XOR:** mai entrambi — `pest()->extend()->in(...)` in `Pest.php` **e** `uses(TestCase)` nei file
→ `TestCaseAlreadyInUse`. Migrazione: aggiungi `extend()` in `Pest.php`, rimuovi `uses()` per-file.

Il divieto storico «Vietato `pest()->extend()`» (method.internalClass senza plugin) è **decaduto**
(XOT-5.41). Non reintrodurre divieti nei docblock.

Bindare sempre il `TestCase` **concreto** del modulo (estende `XotBaseTestCase`), mai
`XotBaseTestCase` abstract.

Gate obbligatorio dopo ogni modifica a `Pest.php`:

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/<Mod>/tests/Pest.php --no-progress
```

Fallback se gate fallisce: `uses(\Modules\<Mod>\Tests\TestCase::class);` nuda in ogni file test.

## PHPStan

- Solo `laravel/phpstan.neon` — **solo l'utente** lo modifica.
- Agenti: **zero** file `.neon` aggiuntivi.
- Utente: rimuovere `includes:` duplicati gestiti da `extension-installer` (vedi [pest-phpstan-plugin.md](./pest-phpstan-plugin.md)).

## Composer (nwidart)

Dipendenze Pest solo in `Modules/Xot/composer.json`. Dopo change:

```bash
cd laravel && composer update -W
```

## Story implementazione

- [3.7 pest5 configuring-tests extend](../../../../../docs/bmad/stories/3.7.pest5-configuring-tests-extend.story.md)
- [5.11 bootstrap senza require](../stories/5.11.pest-shared-bootstrap-without-require.story.md) — **done**

## Collegamenti

- [Plugin ufficiali Pest](./pest-official-plugins.md)
- [Architect handoff v1.4](../../../../../docs/chat/architect-handoff-v1.4-pest5-2026-08-19.md)
