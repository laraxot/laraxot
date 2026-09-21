---
title: "PHPStan Activity Log ListLogFilesActionTest fix"
type: handoff
date: 2026-09-21
status: done
module: Activity
issues:
  - phpstan.expectation.redundant
---

# PHPStan Activity Log Fix

## Problem
PHPStan reported `pest.expectation.redundant` in `Modules/Activity/tests/Unit/Actions/Log/ListLogFilesActionTest.php:71`.

## Fix
Changed line 71 from:
```php
expect($files[0]->modifiedAt)->toBeInt()->toBeGreaterThan(0);
```
to:
```php
expect($files[0]->modifiedAt)->toBeGreaterThan(0);
```

## Rationale
`modifiedAt` is already typed as `int` by PHPStan inference, so `toBeInt()` is redundant. The assertion `toBeGreaterThan(0)` remains valid.

## Verification
- `phpstan analyse Modules/Activity` — zero errors
- `pest Modules/Activity/tests/Unit/Actions/Log/ListLogFilesActionTest.php` — 7 passed (14 assertions)
- `phpstan analyse Modules` — zero errors (10958 file)

## Second Brain Records
- Wiki log: `docs/wiki/log.md` (2026-09-21 entry)
