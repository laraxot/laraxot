# Testing Guide

> Guida completa per i test in PTVX Fila5 Mono.

## 🧪 Testing con Pest

### Comandi Base
```bash
# Test singolo per nome
./vendor/bin/pest --filter="test_name"

# Test singolo file
./vendor/bin/pest tests/Feature/UserTest.php

# Test modulo
./vendor/bin/pest Modules/Xot/tests

# Coverage
./vendor/bin/pest --coverage
```

## 📋 Regola Assoluta DB Test

**NEI TEST È SEMPRE VIETATO USARE:**
- `RefreshDatabase`
- `php artisan migrate:fresh`
- `php artisan migrate --force`

**Usare approcci non distruttivi:**
- Transazioni per isolamento
- Fixture mirate
- Setup personalizzato senza reset schema

## 🎯 Pattern AAA (Arrange-Act-Assert)

```php
it('creates user successfully', function () {
    // ARRANGE
    $data = UserData::factory()->make();
    
    // ACT
    $user = CreateUserAction::execute($data);
    
    // ASSERT
    expect($user)->toBeInstanceOf(User::class);
});
```

## 🔗 Link

**Di ritorno:**
<<<<<<< HEAD
<<<<<<< HEAD
- → [AGENTS.md - Testing Patterns](../../AGENTS.md#testing-patterns)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- → [AGENTS.md - Testing Patterns](../../AGENTS.md#testing-patterns)
>>>>>>> a988596b (first)
- → [agents.md - Testing Patterns](../../agents.md#testing-patterns)
- → [AGENT_MEMORY.md - Testing Patterns](../../AGENT_MEMORY.md#-testing-validation-patterns)
- → [commands.md](commands.md)
- → [INDEX](index.md)
