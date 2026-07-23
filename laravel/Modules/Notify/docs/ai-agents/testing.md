---
title: "Testing Guide"
type: concept
tags: [testing]
created: 2026-07-14
updated: 2026-07-14
qmd: "testing testing guide"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

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
- → [AGENTS.md - Testing Patterns](../../AGENTS.md#testing-patterns)
- → [AGENT_MEMORY.md - Testing Patterns](../../AGENT_MEMORY.md#-testing-validation-patterns)
- → [commands.md](commands.md)
- → [INDEX](index.md)
