<<<<<<< HEAD
---
title: "Xot Module — QueueableActions Pattern"
type: guide
tags: [actions, utilities, architecture]
created: 2026-07-21
updated: 2026-07-21
---

# Xot Module — QueueableActions

## Overview

The Xot module uses **QueueableActions** to encapsulate reusable business logic and utilities.

### Utilities Actions

**Location:** `app/actions/Utilities/`

#### RangeIntersectAction

Find the intersection of two numeric ranges.

```php
use Modules\Xot\Actions\Utilities\RangeIntersectAction;

$result = app(RangeIntersectAction::class)->execute(1, 5, 3, 7);
// Returns: [3, 5] (intersection of ranges [1,5] and [3,7])
// Returns: false if no intersection
```

**Signature:**
```php
execute(int $a, int $b, int $c, int $d): array|bool
```

#### DiffAssocRecursiveAction

Recursively compute array difference while preserving keys. Useful for config merges and nested comparisons.

```php
use Modules\Xot\Actions\Utilities\DiffAssocRecursiveAction;

$array1 = ['name' => 'John', 'settings' => ['theme' => 'dark', 'lang' => 'en']];
$array2 = ['name' => 'John', 'settings' => ['theme' => 'light']];

$diff = app(DiffAssocRecursiveAction::class)->execute($array1, $array2);
// Returns: ['settings' => ['theme' => 'dark', 'lang' => 'en']]
```

**Signature:**
```php
execute(array $array1, array $array2): array
```

## Why QueueableActions?

1. **Composable:** Actions can call other actions via `app(ActionClass::class)->execute(...)`
2. **Testable:** Pure logic, no constructor dependencies
3. **Queueable:** Can be dispatched to queues without modification
4. **Type-safe:** Full type hints on `execute()` parameters and return types

## Adding New Actions

1. Create directory: `app/actions/<DomainName>/`
2. Create action file: `<WhatSubjectAction>.php`
3. Implement:
   ```php
   <?php
   declare(strict_types=1);
   namespace Modules\Xot\Actions\<DomainName>;
   use Spatie\QueueableAction\QueueableAction;
   
   class MyAction {
       use QueueableAction;
       public function execute(...) { ... }
   }
   ```

## Related

- **Pattern docs:** `docs/wiki/patterns/queueable-actions-architecture.md` (root project)
- **Organization rules:** `docs/wiki/rules/actions-subdirectory-grouping.md` (root project)
- **Conversion guide:** `docs/wiki/skills/convert-service-to-action.md` (root project)

---

**Last updated:** 2026-07-21
=======

>>>>>>> 7f6cf6be (.)
