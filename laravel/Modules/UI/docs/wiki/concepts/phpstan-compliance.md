---
title: "UI — Mockery fluente sotto PHPStan"
type: concept
module: UI
tags: [ui, phpstan, pest, mockery, testing, static-analysis]
created: 2026-06-10
updated: 2026-09-02
qmd: "UI PHPStan Pest Mockery CompositeExpectation ExpectationDirector concrete Expectation with andReturnUsing"
issues:
  - "https://github.com/laraxot/module_ui_fila5/issues/6"
discussions:
  - "https://github.com/laraxot/module_ui_fila5/discussions/7"
related:
  - ../../../../../../docs/wiki/PHPSTAN-INDEX.md
  - ../../../../Xot/docs/wiki/concepts/phpstan-pest-bridge-discipline.md
  - ./testing.md
---

# UI — Mockery fluente sotto PHPStan

> Contratto verificato per gli helper Mockery usati dai test Pest UI.

## Stato

Il 2 settembre 2026 il comando completo `./vendor/bin/phpstan analyse Modules`
ha analizzato **10573 file** con zero errori. I due test UI coinvolti hanno
prodotto **10 passed / 38 assertions**.

## Causa

`Mockery\ExpectationInterface` espone l'API minima e non dichiara `with()` o
`andReturnUsing()`. Inoltre, in questa versione di Mockery,
`shouldReceive('metodo')` restituisce a runtime una `CompositeExpectation` anche
con un singolo nome.

Una semplice annotazione `@var Expectation` sarebbe falsa: il valore runtime resta
composito.

## Contratto dell'helper

`UI\Tests\TestCase::expectMethod()`:

1. registra l'aspettativa tramite Mockery;
2. recupera l'`ExpectationDirector` del metodo;
3. estrae l'ultima `Mockery\Expectation` concreta;
4. fallisce con `LogicException` se il director o l'aspettativa non esistono;
5. restituisce `Expectation`, rendendo disponibile la fluent API a PHPStan e a
   runtime.

Il pattern va centralizzato in Xot solo quando almeno un altro modulo presenta lo
stesso bisogno; fino ad allora l'helper resta owner UI.

## Gate

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/UI
./vendor/bin/pest Modules/UI/tests/Unit/UiBasePolicyBehaviorTest.php \
  Modules/UI/tests/Unit/UiFilamentComponentsCoverageTest.php
./vendor/bin/phpstan analyse Modules
```
