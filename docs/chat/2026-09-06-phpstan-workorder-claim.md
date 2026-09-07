---
title: "PHPStan WorkOrder — done, 75 -> 0 errors"
type: coordination
tags: [phpstan, workorder, multi-agent, bmad, done]
created: 2026-09-06T22:20:00Z
status: done
agent: claude (sonnet 5) — this session
scope: Modules/WorkOrder
blocking: false
---

# Modules/WorkOrder — PHPStan zero errors

Fixed 75 real PHPStan errors (Filament v4->v5 deprecations, `cast.*` narrowing, one
`generics.notGeneric` on `WorkOrder.php`'s stale `HasXotFactory<X>` docblock, and a
cross-module `mixed` cascade in `tests/Feature/PeriodicSummaryTest.php`). Full detail
in `Modules/WorkOrder/docs/stories/phpstan-workorder-fix.md` and
`Modules/WorkOrder/docs/coverage.md`. Committed `36c94a6`, pushed clean to
`laraxot/dev` (no merge needed, remote wasn't ahead).

`phpstan analyse Modules/WorkOrder`: **0 errors**, verified twice with clean cache.
Pest: 7/45/15 pass/fail/skip, unchanged before/after — all 45 failures are the
already-documented `workorder_data_test` missing-tables blocker
(`project_test_db_missing_migrations_blocks_feature_tests.md`), not touched.

## Heads-up for whoever is fixing Modules/User

`tests/Feature/PeriodicSummaryTest.php` called `Modules\User\Models\User::factory()->create()`
and PHPStan reported the result as `mixed` (`method.nonObject`/`property.nonObject`
cascade). Root cause: `Modules\User\Models\BaseUser` uses `HasXotFactory` but has **no**
explicit `newFactory()` override, so Larastan's own dynamic-return-type extension for
`::factory()` can't resolve the concrete `UserFactory` class and falls back to `mixed`
(same pattern already documented in second-brain
`project_larastan_factory_mixed_needs_newfactory_override.md`, confirmed on
Quotation/Platform/WorkOrder's own models).

I fixed it **locally inside WorkOrder only** (a `createSummaryUser()` test helper that
calls `Modules\User\Database\Factories\UserFactory::new()->create()` directly + an
`instanceof` narrow) rather than touching `Modules/User/app/Models/BaseUser.php`
(cross-repo, not my module to edit). If you're working User's own PHPStan errors,
adding:

```php
protected static function newFactory(): UserFactory
{
    return UserFactory::new();
}
```

to `BaseUser` would fix this at the real source and likely resolve the same `mixed`
cascade anywhere else `User::factory()` is chained with `->count()`/`->create()`
across other modules too.
