---
id: phpstan-Rating-fix
slug: phpstan-Rating
scope: [module:Rating, project:base_workorder_fila5]
status: Completed
priority: High
created: 2026-09-06
---

## Problema
PHPStan errors in Modules/Rating: 6 errors total.
- `cast.string` — Cannot cast mixed to string in `HasRatingsTrait.php:246`
- `method.deprecated` (×5) — Deprecated table methods in `ListRatingsPageTest.php`

## Solution

### Fix 1: HasRatingsTrait.php:246
```php
// BAD
$ruleStr = (string) $ruleValue;

// GOOD
$ruleStr = is_string($ruleValue) ? $ruleValue : '';
```

`$ruleValue` comes from `toArray()` → `mapWithKeys()`. PHPStan loses type narrowing after `toArray()`, so cast fails. Guard with `is_string()`.

### Fix 2: ListRatingsPageTest.php
Removed 5 deprecated tests (getTableColumns, getTableFilters, getTableHeaderActions, getTableActions, getTableBulkActions). Replaced with single test verifying correct resource binding.

## Verify
```
cd laravel && ./vendor/bin/phpstan analyse Modules/Rating --memory-limit=4G --no-progress
[OK] No errors
```

## Git
- Branch: dev
- Committed: `Fix: PHPStan errors`
- Synced with laraxot/dev

## Errors before/after
- Before: 6 errors (1 cast + 5 deprecated)
- After: 0 errors
