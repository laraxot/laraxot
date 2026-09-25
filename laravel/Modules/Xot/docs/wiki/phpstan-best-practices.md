---
title: "PHPStan Best Practices - Xot Module"
type: guideline
tags: [phpstan, testing, quality, static-analysis, pest, xot]
created: 2026-06-13
updated: 2026-09-21
qmd: "Xot PHPStan best practices Pest Assert method.internalClass Mockery allows Blade mockService rrmdir"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/43"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/29"
related:
  - concepts/phpstan-pest-bridge-discipline.md
  - overviews/platform-completion-roadmap.md
  - ../../../../../docs/wiki/PHPSTAN-INDEX.md
---

# PHPStan Best Practices - Xot Module

Disciplina: **risolvere**, non sopprimere. Niente `@phpstan-ignore` di evasione,
niente baseline, niente `mixed` per zittire l'analizzatore.

## Pattern per Test in Pest con PHPStan Level Max

### 1. Property dinamiche in closure Pest

PHPStan non vede `$this->property` nelle closure Pest. Preferire variabile locale:

```php
test('example', function (): void {
    $action = new MyAction;
    $result = $action->execute();
    Assert::assertSame('expected', $result);
});
```

Narrowing solo se il body usa `$this`:

```php
beforeEach(function (): void {
    $this->workDir = sys_get_temp_dir().'/test';
    assert(is_string($this->workDir));
});
```

### 2. Mock PHPUnit in closure Pest

`$this->atLeastOnce()` è protected. Usare `createUnitMock()` o Mockery `allows()`.

### 3. Return type covarianza

`mockService()` nel TestCase figlio deve avere la stessa firma del parent.

### 4. Chiamate `static::assert*()` fuori classe

Nelle closure Pest usare `Assert::`, mai `static::`.

### 5. Type narrowing per funzioni PHP

`tempnam()` è `string|false`. Gestire `false` con `Assert::fail()`, non con
`assertIsString` su un valore già ristretto (`staticMethod.alreadyNarrowedType`).

### 6. `@var TestCase $this` solo se serve

Annotare `$this` solo nelle closure che chiamano metodi dell'istanza.
Se il test usa solo variabili locali, omettere `@var` (`varTag.differentVariable`).

### 7. `expect()->toBe*()` → `method.internalClass`

Su file Pest namespaced, `Pest\Mixins\Expectation` è `@internal`. Preferire
`PHPUnit\Framework\Assert` sul dato reale. Evitare tautologie `assertTrue(true)`.

### 8. Mockery sotto PHPStan

Catene `shouldReceive()->andReturn()` spesso `method.notFound`. Pattern Xot:

```php
/** @var GetComponentsAction&MockInterface $getComponents */
$getComponents = Mockery::mock(GetComponentsAction::class);
$getComponents->allows(['execute' => $mockComps]);
app()->instance(GetComponentsAction::class, $getComponents);
```

## Checklist pre-commit

- [ ] `php -d memory_limit=-1 vendor/bin/phpstan analyse` (comando che certifica) passa
- [ ] Pest del modulo: `vendor/bin/pest Modules/Xot/tests/Unit`
- [ ] Nessun `static::` in closure Pest
- [ ] Mockery: `allows(['method' => $value])` + `@var Class&MockInterface`

## Links

- [platform-completion-roadmap](overviews/platform-completion-roadmap.md)
- [phpstan-pest-bridge-discipline](concepts/phpstan-pest-bridge-discipline.md)
- [PHPSTAN-INDEX](../../../../../docs/wiki/PHPSTAN-INDEX.md)
- [module-testcase-xotbase-hierarchy](rules/module-testcase-xotbase-hierarchy.md)
- [phpstan-modules-fix](troubleshooting/phpstan-modules-fix.md)
