---
title: "Bugfix — uses(TestCase::class) before its use import silently binds the wrong class"
type: bugfix
tags: [quotation, phpstan, pest, testing, use-imports]
created: 2026-07-27
updated: 2026-07-27
related:
  - "./README.md"
  - "../../../../docs/chat/phpstan-modules-zero.md"
---

# Bugfix — `uses(TestCase::class)` declared before its `use` import

## Problem

24 Pest test files in `Modules/Quotation/tests/Feature/` followed this shape:

```php
<?php

declare(strict_types=1);

uses(TestCase::class);

use Modules\Quotation\Tests\TestCase;
// ... other use imports
```

PHPStan reported `Class TestCase not found` on the `uses(TestCase::class)` line, plus a
large cascade of unrelated-looking errors further down the same file: `theCodingMachineSafe.function`
on `Safe\tempnam`/`Safe\unlink`/`Safe\file_get_contents` that were **already** imported via
`use function Safe\...;`, and `method.internalClass`/`property.notFound` on
`Pest\Mixins\Expectation` for every `expect(...)->toBeTrue()` etc. call in the file.

Scanning `Modules/Quotation` in isolation only ever surfaced the first error (`class.notFound`)
per file — PHPStan appears to abandon deeper analysis of a file once an early name resolution
like this fails. Only the full-tree `Modules` scan (the source of truth per
`docs/chat/phpstan-modules-zero.md`) showed the full cascade: **577 reported errors for
Quotation, of which 574 were test-file lines traceable back to just these 24 files** — an
average of ~24 cascading false/true positives per broken file.

## Root cause: this is a real PHP bug, not a PHPStan quirk

`use` import aliases are resolved by the PHP compiler in a **single top-down pass**. An
unqualified `Foo::class` reference is only resolved against a `use ... as Foo;` (or
`use Foo;`) statement that appears **earlier** in the same file. If the reference appears
first, PHP does not resolve it against a later `use` — it silently compiles it as the
literal, unqualified string `"Foo"` instead of raising any error (since `::class` doesn't
require the class to be loadable).

Reproduced directly, outside Pest/PHPStan entirely:

```php
<?php
declare(strict_types=1);

function uses(string $c) { echo "Resolved to: $c\n"; }

uses(TestCase::class);      // prints "Resolved to: TestCase" — NOT "Resolved to: ArrayObject"

use ArrayObject as TestCase;
```

Applied to the real files: `uses(TestCase::class);` on line 5, with
`use Modules\Quotation\Tests\TestCase;` further down, meant Pest was, at real test-execution
runtime, calling `uses('TestCase')` — a bare, non-existent global class name — instead of the
intended `uses('Modules\Quotation\Tests\TestCase')`. This is a genuine runtime defect in how
these 24 tests bound themselves to their base `TestCase`, independent of static analysis.

## Fix

Move the whole `use` import block above the `uses(TestCase::class);` line, matching the
convention already used correctly elsewhere in this codebase (e.g.
`Modules/Compliance/tests/Feature/CustomerSystemAndBoilerMaintenanceTest.php`):

```php
<?php

declare(strict_types=1);

use Modules\Quotation\Tests\TestCase;
// ... other use imports

uses(TestCase::class);
```

This is a pure reordering — `use` import statements are otherwise position-independent in
PHP as long as they precede their first reference — so there is no behavior change beyond
fixing the actual bug. Applied to all 24 affected files (commits `2baee556`, `8d6f0b49`).

## How to avoid next time

- Always put the full `use` import block (including any alias needed by a `uses(...)` call)
  directly after `declare(strict_types=1);`, and the Pest `uses(...)` call **after** it, never
  before. This matches the working pattern already established across most of the modules in
  this codebase.
- If you see PHPStan report `class.notFound` on a `uses(SomeClass::class);` line in a Pest
  test, check import order **first** before assuming it's a missing `use Modules\...\TestCase;`
  import — the import may already be present, just in the wrong position.
- Isolated per-module PHPStan scans understate/hide this class of bug (analysis of the file
  appears to stop after the failed resolution); only a full `Modules`-tree scan reliably
  surfaces the full cascade. See `docs/chat/phpstan-modules-zero.md` for why.
