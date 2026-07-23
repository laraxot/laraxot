---
title: "PHP coding standards (strict)"
type: rule
tags: [php, coding, standards]
created: 2026-07-14
updated: 2026-07-14
qmd: "php-coding-standards php coding standards (strict)"
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

# PHP coding standards (strict)

## Always required

```php
<?php declare(strict_types=1);

public function __construct(
    public readonly UserService $userService,
    public string $mode = 'default'
) {}

public function calculateTotal(array $items): float
{
    // implementation
}

public function processData(string|int $value): string|int
{
    return $value;
}
```

## Forbidden

- `mixed` parameters/returns (unless unavoidable and explicitly justified)
- Missing return types
- Untyped parameters (except variadic)
- Bare `array` without key/value types in PHPDoc when needed (`array<string, int>` etc.)

## Import ordering

```php
<?php declare(strict_types=1);

use Filament\Forms;
use Filament\Tables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Modules\User\Filament\Resources\UserResource;

use function Safe\json_decode;
```
