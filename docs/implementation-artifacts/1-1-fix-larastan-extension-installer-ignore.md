# Story 1.1: Fix Larastan Extension-Installer Ignore Block

**Epic:** 1 - Build & Setup Foundation  
**Story Key:** 1-1-fix-larastan-extension-installer-ignore  
**Status:** backlog  
**Created:** 2026-09-06  
**Project:** base_workorder_fila5  

---

## Problem

PHPStan crashes with:
```
Internal error: Call to undefined method Larastan\Larastan\Reflection\EloquentBuilderMethodReflection::isForwardedQueryBuilderMethod()
```

**Root Cause**: `larastan/larastan` is in `extension-installer.ignore` list in `composer.json`, preventing Larastan from being loaded as a PHPStan extension.

**File**: `laravel/composer.json` line: `"ignore": ["larastan/larastan", ...]`

**Result**: Larastan custom reflection methods unavailable → PHPStan crashes on Eloquent builder analysis.

---

## Acceptance Criteria

### AC1: Root Cause Verified
- Open `laravel/composer.json`
- Confirm `phpstan/extension-installer` has entry: `"ignore": ["larastan/larastan", ...]`
- Verify Larastan version: `^4.0` (latest stable)

### AC2: Fix Applied
**Option A** (recommended): Remove from ignore list
```json
"phpstan/extension-installer": {
    "ignore": [
        // "larastan/larastan",  ← REMOVE THIS LINE
        "nesbot/carbon",
        ...
    ]
}
```

**Option B** (alternative): Explicit include in phpstan.neon
```yaml
includes:
  - vendor/larastan/larastan/extension.neon
```

Choose Option A (cleaner, no duplication risk).

### AC3: PHPStan Works
- Run: `./vendor/bin/phpstan analyse Modules --level=10`
- Expected: Analysis completes, no "isForwardedQueryBuilderMethod" error
- Result: Either [OK] or real errors (not internal crash)

### AC4: Coverage Maintained
- Baseline pre-fix noted
- Post-fix Pest + coverage: no regression
- If regression, investigate Larastan side effects

### AC5: Git Sync
- Commit: `git add laravel/composer.json && git commit "Fix: remove larastan from extension-installer ignore"`
- Push: `git push` (root repo)

---

## Risk Assessment

**Low risk**:
- Larastan is stable, widely used
- Only changes composer config, not code
- PHPStan crash → anything is improvement

**Potential side effects** (check with tests):
- Larastan may report new errors (better accuracy) → not a regression, expected
- Performance: Larastan adds analysis time (acceptable, needed for accuracy)

---

## Blockers

None — this is a quick fix.

---

## Dependencies

None — can be done immediately.

---

## Notes

- This is root-level repo fix (not modulo-specific)
- Similar ignore-list pattern exists in other repos (investigate after?)
- Larastan v4.0 requires PHPStan v1.10+; already satisfied

---

**Status:** ⏳ Awaiting BMAD validation before implementation

