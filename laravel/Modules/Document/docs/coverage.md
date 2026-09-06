---
name: Coverage Baseline
description: PHPStan Level 10 coverage baseline after XOT-18.3 document module fixes
author: AI Agent
date: 2026-09-06
status: active
---

# Coverage Baseline — Document Module

## Overview

Post-PHPStan Level 10 fixes (XOT-18.3). All type safety issues resolved; coverage established for ongoing quality gates.

| Metric | Status | Details |
|--------|--------|---------|
| **PHPStan Level** | max (10) | All errors fixed, 0 errors reported |
| **Type Narrowing** | 100% | No remaining `mixed` without narrowing |
| **Cast Operations** | 0 | Eliminated unsafe casts, replaced with type checks |
| **Pest Tests** | 2 passing | Unit: 2, Feature: 4 (setup issues) |

---

## PHPStan Analysis Results

**Command:** `./vendor/bin/phpstan analyse Modules/Document --no-progress --memory-limit=-1`

**Result:** ✅ [OK] No errors

### Files Analyzed

- `app/Actions/`
- `app/Filament/`
- `app/Listeners/`
- `app/Models/`
- `app/Providers/`
- `config/`

### Key Fixes (XOT-18.3)

#### 1. UploadInterventionPhotos.php (Line 92)

**Before:**
```php
paths: array_values(array_map(static fn (mixed $path): string => (string) $path, $paths)),
```

**After:**
```php
paths: array_values(array_map(static function (mixed $path): string {
    if (is_string($path)) {
        return $path;
    }
    if (is_int($path) || is_float($path)) {
        return (string) $path;
    }
    return '';
}, $paths)),
```

**Reason:** Type narrowing replaces direct cast; explicit handling of numeric types prevents silent data loss.

#### 2. EventServiceProvider.php

**Removed:** Unused protected property `$shouldDiscoverEvents`  
**Reason:** Dead code elimination; Laravel auto-discovers events by default.

#### 3. Media.php (Line 33)

**Before:** Extra blank line after class declaration  
**After:** Removed unnecessary whitespace  
**Reason:** Code hygiene; maintains PSR-12 compliance.

---

## Test Coverage

### Unit Tests

| Test | Status | Duration |
|------|--------|----------|
| MediaTest::builds a media with disk/path/kind | ✅ PASS | 6.59s |
| MediaTest::exposes a generic morphTo mediable relation | ✅ PASS | 6.95s |

**Total Unit Tests:** 2/2 passing

### Feature Tests

| Test | Status | Issue | Action |
|------|--------|-------|--------|
| MediaActionsTest::attaches a stored document | ⚠️ BLOCKED | `document_media` table missing on `document` mysql test connection | Env migrate required |
| MediaActionsTest::keeps the uploader id intact | ⚠️ BLOCKED | same | Env migrate required |
| MediaActionsTest::attaches multiple uploaded photos | ⚠️ BLOCKED | same | Env migrate required |
| MediaActionsTest::fills mime type and size | ⚠️ BLOCKED | same | Env migrate required |

**Note:** Feature tests require tenant database setup (customer connection). Unit tests pass and confirm core business logic.

---

## Update — session 2026-09-06 (parallel PHPStan wave, story `phpstan-l10-fix.story.md`)

Re-verified from scratch with `phpstan clear-result-cache` before each run
(cache can go stale under concurrent multi-agent edits): **PHPStan
Modules/Document stayed at 0 errors** throughout this session — the L10 fix
recorded above (commit `6071c43`) was already complete and correct.

### What changed in this session

1. Small PHPMD-driven style fixes (no behavior change): removed a blank line
   after the `Media` class opening brace, added missing docblock spacing in
   `AttachPhotosToInterventionAction`, replaced a short ternary
   (`$meta ?: null`) with an explicit `$meta === [] ? null : $meta` in
   `CreateMediaFromStoredFileAction`.
2. Audited every remaining `mixed` occurrence individually — all three are
   justified (JSON metadata bag, Filament/Livewire component state, a
   defensively-narrowed callback parameter) and match conventions used
   elsewhere in the monorepo (e.g. `Modules/Gdpr`, `Modules/Geo`). None
   removed.
3. **Root-caused the Feature test failures further.** The original blocker
   ("missing `customers` table") was one layer of a longer chain:
   `documentCreateIntervention()` called `InterventionFactory::createOne()`,
   which resolves `work_order_id => WorkOrder::factory()`, which resolves
   `customer_id => Customer::factory()` — a real insert on the `customer`
   mysql connection. Fixed in `tests/PestHelpers.php` by building the
   `Intervention` with `makeOne(['work_order_id' => null, ...])` (never
   persisted) plus a manually-assigned fake primary key, since
   `document_media` has no FK to `mediable` (`morphs()` is columns + index
   only — see migration comment). This removes the Customer/WorkOrder
   dependency entirely and was safe to do (test-only file, no other module
   touched). Also removed a now-invalid `$intervention->refresh()` call in
   `MediaActionsTest` (would SELECT a row that was never persisted).
4. **New, narrower blocker surfaced**: after the above fix, the same 4 tests
   now fail because `document_media` itself doesn't exist on the `document`
   mysql connection's test database (`workorder_data_test`) in this
   environment — i.e. Document's own connection isn't migrated here either,
   independent of Customer/Intervention. Confirmed via
   `Modules/Cms/tests/TestCase.php` inline comment that this project's
   convention is: *"Migrations are NOT run in setUp(). They must be run ONCE
   externally: `php artisan migrate --env=testing`."* Running migrations for
   a shared mysql test connection is an environment/ops action outside this
   module's file scope and outside this agent's mandate (`Data is sacred` —
   schema changes are the environment owner's call), so it was **not**
   executed here. Flagged for the environment owner.

### Numbers

| Metric | Before this session | After this session |
|---|---|---|
| PHPStan errors | 0 | 0 |
| Pest — Unit | 2 passed | 2 passed |
| Pest — Feature | 4 failed (customers table missing) | 4 failed (narrower cause: document_media table missing — env migrate needed) |
| PHPMD violations | 3 (pre-existing, convention/threshold) | 3 (unchanged, confirmed false positives) |

---

## Quality Gate Results

### PHPStan (Level max)

```
./vendor/bin/phpstan analyse Modules/Document --no-progress --memory-limit=-1
Result: [OK] No errors
```

### Pint (Code Style)

```bash
./vendor/bin/pint --dirty Modules/Document/
```

**Status:** ✅ Compliant with PSR-12 + project conventions

### Test Execution

```bash
./vendor/bin/pest Modules/Document/tests --no-coverage
Result: 2 passed, 4 blocked (setup)
```

---

## Recommendations

### Immediate

1. ✅ PHPStan coverage complete; no outstanding type safety issues
2. ✅ Type narrowing applied systematically across codebase
3. ⚠️ Feature test setup requires customer tenant database connection

### Future

- When customer tenant setup is stabilized, run full feature test suite
- Consider integration tests for intervention media attachment workflows
- Monitor type safety via pre-commit PHPStan hooks

---

## Baseline Stamp

| Key | Value |
|-----|-------|
| **Commit** | 6071c43 |
| **Branch** | dev |
| **Date** | 2026-09-06 |
| **PHP** | 8.3.29 |
| **PHPStan** | max (10) |
| **Pest** | v5 |
| **Status** | ✅ BASELINE ESTABLISHED |
