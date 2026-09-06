---
title: "PHPStan module config env discipline"
type: rule
tags: [phpstan, config, larastan, employee]
created: 2026-07-03
updated: 2026-07-03
qmd: "phpstan larastan module config env employee Env get"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/34"
  - "https://github.com/laraxot/module_employee_fila5/issues/2"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/35"
---

# PHPStan module config env discipline

Larastan flags `env()` inside `Modules/<Module>/config/*.php` as `larastan.noEnvCallsOutsideOfConfig`, because module config folders are not detected as the Laravel root `config/` directory.

Use `Illuminate\Support\Env::get()` in module config files instead of the global `env()` helper. This keeps runtime behavior local to configuration loading and avoids changing the immutable root `phpstan.neon`.

Example:

```php
use Illuminate\Support\Env;

return [
    'enabled' => Env::get('EMPLOYEE_FEATURE_ENABLED', true),
];
```

Validation used for `Modules/Employee/config/config.php`:

```bash
cd laravel
php -l Modules/Employee/config/config.php
./vendor/bin/phpstan analyse Modules/Employee --memory-limit=-1 --no-progress
./tools/phpinsights.sh analyse Modules/Employee/config/config.php --no-interaction --min-quality=0 --min-complexity=0 --min-architecture=0 --min-style=0
./vendor/bin/pest Modules/Employee/tests --no-coverage
```

Notes:

- PHPMD wrapper currently fails because `laravel/tools/phpmd.phar` is missing.
- Employee Pest currently fails on pre-existing test/factory issues: missing `createEmployee()`, Faker `firstName`, and unauthenticated route expectations.
