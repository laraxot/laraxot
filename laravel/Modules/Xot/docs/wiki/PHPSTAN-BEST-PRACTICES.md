---
title: "PHPStan Best Practices - Xot Module"
type: guideline
tags: [phpstan, testing, quality, static-analysis, pest, xot]
created: 2026-06-13
updated: 2026-07-22
qmd: "Xot PHPStan best practices Pest Assert method.internalClass Mockery allows Blade"
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

## Pattern per Test in Pest con PHPStan Level Max

### 1. Property Dinamiche in Closure Pest

**Problema:** PHPStan non riconosce `$this->property` nelle closure Pest.

**Soluzione A - Variabile Locale (Consigliata):**
```php
test('example', function (): void {
    $action = new MyAction;
    $result = $action->execute();
    Assert::assertSame('expected', $result);
});
```

**Soluzione B - assert() Type Narrowing:**
```php
beforeEach(function (): void {
    $this->workDir = sys_get_temp_dir() . '/test';
    assert(is_string($this->workDir));
});
```

**Soluzione C - @phpstan-ignore (Quando inevitabile):**
```php
test('example', function (): void {
    /** @phpstan-ignore varTag.variableNotFound */
    $mock = Mockery::mock(MyClass::class);
});
```

### 2. Mock PHPUnit in Closure Pest

**Problema:** `$this->atLeastOnce()` è protected, PHPStan segnala errore.

**Soluzione:**
```php
$mock = $this->createUnitMock(MyClass::class);
/** @phpstan-ignore-next-line */
$mock->expects($this->atLeastOnce())
    ->method('execute')
    ->willReturn($result);
```

### 3. Return Type Covarianza

**Problema:** `mockService()` in TestCase figlio deve essere covariante con parent.

**Soluzione:** Usare lo stesso tipo del parent:
```php
// XotBaseTestCase
public function mockService(string $abstract, ?\Closure $callback = null): MockInterface

// TestCase (figlio) - stessa signature, stesso tipo
public function mockService(string $abstract, ?\Closure $callback = null): MockObject
```

### 4. Chiamate static::assert*() Fuori Classe

**Problema:** `static::assertDirectoryDoesNotExist()` in closure Pest.

**Soluzione:** Usare `Assert::` (classe PHPUnit):
```php
// ❌ Errore
static::assertDirectoryDoesNotExist($path);

// ✅ OK
Assert::assertDirectoryDoesNotExist($path);
```

### 5. Type Narrowing per Funzioni PHP

**Problema:** `tempnam()` ritorna `string|false`, PHPStan segnala errore.

**Soluzione:** assegnazione diretta senza `assertIsString` ridondante se il ramo `false` è irraggiungibile in test, oppure:

```php
$tempFile = tempnam(sys_get_temp_dir(), 'prefix');
if ($tempFile === false) {
    Assert::fail('tempnam failed');
}
```

**Anti-pattern:** `Assert::assertIsString($tempFile)` quando `$tempFile` è già `string` — `staticMethod.alreadyNarrowedType`.

### 6. `@var TestCase $this` solo se serve

**Problema:** annotare `/** @var TestCase $this */` prima di `$action = app(...)` genera `varTag.differentVariable`.

**Soluzione:** mettere `@var` solo nelle closure che chiamano `$this->rrmdir()`, `$this->mockService()`, ecc. Se il test usa solo variabili locali, **omettere** `@var`.

### 7. `expect()->toBe*()` → `method.internalClass` (Pest mixins)

**Problema:** in alcuni file Pest namespaced, PHPStan segnala `method.internalClass` su `Pest\Mixins\Expectation` (`toBe`, `toBeTrue`, `toThrow`, …) anche se altri test con `expect()` passano.

**Soluzione (DRY/KISS):** preferire `PHPUnit\Framework\Assert` + asserzione sul dato reale (count, instance, stesso valore). Evitare tautologie `expect(true)->toBe(true)` / `Assert::assertTrue(true)` (`staticMethod.alreadyNarrowedType`).

```php
use PHPUnit\Framework\Assert;

Assert::assertSame(0, $mockComps->count());
```

### 8. Mockery sotto PHPStan

**Problema:** `->andReturn()` / `->andReturns()` su catena `shouldReceive` spesso dà `method.notFound` (union Mockery).

**Soluzione:** pattern Xot collaudato:

```php
/** @var GetComponentsAction&MockInterface $getComponents */
$getComponents = Mockery::mock(GetComponentsAction::class);
$getComponents->allows(['execute' => $mockComps]);
app()->instance(GetComponentsAction::class, $getComponents);
```

`RegisterBladeComponentsAction::execute(string $path, string $namespace, string $prefix = '')` — mockare `Modules\Xot\Actions\File\GetComponentsAction` (non un fantasma `Actions\Blade\GetComponentsAction`).

## Checklist Pre-Commit

- [ ] `php -d memory_limit=2048M vendor/bin/phpstan analyse Modules` passa (non solo Xot)
- [ ] Test Pest eseguibili: `vendor/bin/pest Modules/Xot/tests/Unit`
- [ ] Nessun `static::` in closure Pest (usare `Assert::`)
- [ ] Preferire `Assert::` se `expect()->…` dà `method.internalClass`
- [ ] Mockery: `allows(['method' => $value])` + `@var Class&MockInterface` (non catene `andReturn` fragili)
- [ ] Mock con `@phpstan-ignore-next-line` solo se inevitabile

## Links

- [platform-completion-roadmap](overviews/platform-completion-roadmap.md)
- [phpstan-pest-bridge-discipline](concepts/phpstan-pest-bridge-discipline.md)
- [PHPSTAN-INDEX](../../../../../docs/wiki/PHPSTAN-INDEX.md)
- [module-testcase-xotbase-hierarchy](rules/module-testcase-xotbase-hierarchy.md)
