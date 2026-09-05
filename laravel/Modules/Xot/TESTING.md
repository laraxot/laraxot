# Testing Xot

Testing guide for Xot module and all modules that extend it.

## Quick Start

```bash
# Run all Xot tests
./vendor/bin/pest Modules/Xot/tests

# Run a specific test file
./vendor/bin/pest Modules/Xot/tests/Unit/Actions/Cast/SafeStringCastActionTest.php

# Filter by test name
./vendor/bin/pest --filter="TransKeyAction" Modules/Xot
```

## Test Structure

```
Modules/Xot/tests/
├── Unit/
│   ├── Actions/
│   │   ├── Cast/
│   │   ├── Model/
│   │   ├── Panel/
│   │   └── ...
│   └── Models/
└── Feature/
    └── Filament/
```

**Unit tests** validate individual actions and utilities.
**Feature tests** validate Filament resources, pages, and workflows.

## Base Test Class

`Modules/Xot/tests/XotBaseTestCase.php` provides:
- `assertDatabaseHasRow(string $table, array $where): void`
- `assertDatabaseCountRow(string $table, int $count): void`
- `createUnitMock(string $class, array $methods = []): Mock`

Example:
```php
use Modules\Xot\Tests\XotBaseTestCase;

class MyTest extends XotBaseTestCase
{
    public function test_something()
    {
        $this->assertDatabaseHasRow('users', ['email' => 'test@example.com']);
    }
}
```

## Testing Actions

Actions are pure functions. Test input → output:

```php
test('safe string cast works', function () {
    $action = app(SafeStringCastAction::class);
    expect($action->execute(456))->toBe('456');
    expect($action->execute(null))->toBe('');
});
```

## Testing Filament Resources

Use `Livewire::test()` to validate resource forms and tables:

```php
test('user resource creates model', function () {
    Livewire::test(UserResource\Pages\CreateUser::class)
        ->set('data.name', 'John')
        ->set('data.email', 'john@example.com')
        ->call('create')
        ->assertHasNoErrors();

    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
});
```

## Testing Panels

Test that panels render and discovery works:

```php
test('admin panel discovers resources', function () {
    $panel = app(AdminPanelProvider::class);
    $resources = $panel->getResources();
    expect($resources)->not->toBeEmpty();
});
```

## Coverage

Xot tests aim for >90% coverage of core actions and models. Check coverage:

```bash
./vendor/bin/pest Modules/Xot/tests --coverage --min=90
```

Coverage report: `Modules/Xot/docs/coverage.md` (auto-generated).

## Pest Practices in Xot

- Use datasets for parametric tests (validation rules, cast variations).
- Use `with()` for multiple test cases.
- Prefer high-level assertions: `->toBe()`, `->toContain()`, not `assertEquals()`.
- Mock external services (DeepL, Google Translate) but test fallback behavior.
- Use Factories for test data: never hardcode arrays.

## PHPStan Level 10

All tests must pass strict static analysis:

```bash
./vendor/bin/phpstan analyse Modules/Xot --memory-limit=-1
```

No `any` types, no `@suppresses`, no mixed return types. Errors fixed in code.

## Modules That Extend Xot

Every module that extends `XotBaseResource`, `XotBasePage`, or uses Xot actions **must**:
1. Have tests in `Modules/{Mod}/tests/`.
2. Pass `./vendor/bin/pest Modules/{Mod}/tests`.
3. Run `./vendor/bin/phpstan analyse Modules/{Mod}`.
4. Achieve ≥85% coverage (ideally >90%).

Xot changes are automatically propagated; ensure tests catch regressions.

## Debugging Tests

Use `dddx()` helper to dump and die:

```php
test('debug something', function () {
    $result = $action->execute($data);
    dddx($result); // dumps and exits
});
```

Or use Tinker:
```bash
php artisan tinker
>>> app(SafeStringCastAction::class)->execute('test')
```

## See Also

- `docs/testing/` — comprehensive testing guides
- `docs/xotbaseresource.md` — resource testing patterns
- `docs/xotbasepage-implementation.md` — page testing patterns
