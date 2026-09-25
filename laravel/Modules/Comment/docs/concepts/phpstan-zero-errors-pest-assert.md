---
title: "Comment — PHPStan 0 errori inclusi test Pest"
type: concept
module: Comment
tags: [comment, phpstan, pest, phpunit-assert, quality-gate]
created: 2026-06-10
updated: 2026-06-10
qmd: "phpstan zero errors comment tests Assert PHPUnit not expect"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/22"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/23"
related:
  - ./phpstan-module-owner-scope.md
  - ../../../../docs/wiki/memories/phpstan-pest-assert-pattern.md
---

# PHPStan 0 errori — test Pest nel modulo Comment

## Problema

`expect()->toBe()` → PHPStan `method.internalClass` (Pest interno).  
`$this->assert*()` nei closure Pest → PHPStan vede `TestCall`, non `TestCase`.

## Soluzione (canon Comment)

1. **`use PHPUnit\Framework\Assert;`** in ogni file Pest
2. **`Assert::assertSame()` / `assertTrue()`** al posto di `expect()` e `$this->assert*()`
3. **`tests/Pest.php`**: niente `pest()->extend()` — ogni file usa `uses(TestCase::class)`
4. **Stub tipizzati** al posto di `Mockery::mock(CanComment::class)` → `tests/Support/ParityCommentatorStub.php`
5. **Null safety**: `fresh()` → `assertInstanceOf(Comment::class, $fresh)` prima dell'uso
6. **Safe**: `use function Safe\file_get_contents` dove serve

## Gate

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/Comment --no-progress
```

Target: **0 errori** (app + config + tests).

## Anti-pattern

| Vietato | Perché |
|---------|--------|
| `expect($x)->toBe($y)` | method.internalClass |
| `$this->assertTrue()` in closure Pest | method.notFound su TestCall |
| Mockery su `CanComment` | argument.type |
| `Model::make()` nei test | larastan.noModelMake |
| `env()` in `Modules/*/config/` | Larastan — solo `laravel/config/` |
