# Pest Testing Setup for Laravel Modules

## Overview
This document explains how to set up and run Pest PHP tests in the Laravel modules project with proper configuration.

## Installation and Configuration

### 1. Pest Installation
Pest is already installed in the project as a dev dependency. If you need to install it manually:

```bash
composer require --dev pestphp/pest
composer pest:install
```

### 2. Autoloading Configuration
The `composer.json` file is configured correctly with:
```json
"autoload-dev": {
    "psr-4": {
       "Tests\\": "tests/"
    }
}
```

Note: The `Modules\\` entry should NOT be in `autoload-dev` to avoid conflicts between the main modules autoloading and test autoloading.

### 3. Module-Specific Testing
Each module can have its own tests located in:
- `Modules/{ModuleName}/tests/`

## Running Tests

### 1. Running All Tests
From the Laravel root directory:
```bash
./vendor/bin/pest
```

### 2. Running Tests for Specific Module
```bash
./vendor/bin/pest Modules/User/tests/
```

### 3. Running with Coverage
```bash
./vendor/bin/pest --coverage
```

## Test Configuration

### Environment
Tests should run with the `.env.testing` file. Make sure the testing database configuration matches your development database type to avoid dialect issues.

### Database Considerations
- DO NOT use `Illuminate\Foundation\Testing\RefreshDatabase` as it can cause issues in the modules architecture
- Use alternative approaches like `DatabaseMigrations`, `DatabaseTransactions`, or custom database setup/teardown methods

### Test Structure
- Follow Pest's functional testing style when possible
- For module-specific tests, place them in the respective module's `tests/` directory
- Convert existing PHPUnit-style tests to Pest format when encountered

## Converting PHPUnit to Pest

### Before (PHPUnit):
```php
<?php

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
```

### After (Pest):
```php
<?php

use function PHPUnit\Framework\assertTrue;

test('example', function () {
    assertTrue(true);
});
```

## Best Practices

1. **Module Isolation**: Each module should have its own test suite
2. **No RefreshDatabase**: Avoid using `RefreshDatabase` trait
3. **Environment Consistency**: Use same database type in .env.testing as in .env
4. **Coverage Focus**: Strive for 100% coverage per module
5. **DRY + KISS + SOLID + Robust**: Follow these principles in test code too
6. **Type Safety**: Run tests through PHPStan, PHPMD, and PHPInsights after modifications

## Troubleshooting

### Common Issues:
1. **Class not found**: Make sure you're not using `RefreshDatabase` trait
2. **Module loading issues**: Verify the `autoload-dev` configuration doesn't include `Modules\`
3. **Database dialect issues**: Ensure testing and development databases use the same type

### Solutions:
1. Clear configuration cache: `php artisan config:clear`
2. Regenerate autoload: `composer dump-autoload`
3. Check your .env.testing configuration