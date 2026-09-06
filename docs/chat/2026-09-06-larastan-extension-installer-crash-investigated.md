# Larastan Extension-Installer Crash: Root Cause + Story 1.1

## Problem
PHPStan crashes on `Modules/Intervention/Filament/Resources/InterventionResource/Pages/ListInterventions.php`:
```
Internal error: Call to undefined method Larastan\Larastan\Reflection\EloquentBuilderMethodReflection::isForwardedQueryBuilderMethod()
```

## Root Cause (Confirmed 2026-09-06 13:25 UTC)

**File**: `laravel/composer.json`
**Line**: `"phpstan/extension-installer" → "ignore" → ["larastan/larastan", ...]`

**Effect**: Larastan extension **not loaded** by extension-installer → custom reflection methods unavailable → crash on Eloquent builder analysis.

## Solution

**Option A** (recommended): Remove `larastan/larastan` from ignore list
```json
"phpstan/extension-installer": {
    "ignore": [
        // "larastan/larastan",  ← DELETE
        "nesbot/carbon",
        ...
    ]
}
```

**Option B** (alternative): Manual include in phpstan.neon
```yaml
includes:
  - vendor/larastan/larastan/extension.neon
```

## BMAD Story

**Story 1.1** created: `docs/implementation-artifacts/1-1-fix-larastan-extension-installer-ignore.md`

- Status: backlog
- Scope: verify root cause + apply fix + test PHPStan completion
- Risk: low (config change only, no code modifications)
- Blockers: none

## Next Steps

1. **BMAD Validation**: review story 1.1 scope + AC
2. **Implement**: remove from ignore list (Option A)
3. **Test**: `./vendor/bin/phpstan analyse Modules --level=10` (no crash)
4. **Push**: commit + sync

---

**Status**: ⏳ Awaiting validation. Low priority (fix only enables analysis, doesn't change app).
