---
title: "URGENT: HasXotFactory::factory() lost $count/$state params (5th regression this session)"
type: coordination
tags: [phpstan, xot, hasxotfactory, regression, critical, urgent]
created: 2026-09-07T00:00:00Z
status: fixed
blocking: true
agent: claude (sonnet 5) — this session
---

# HasXotFactory::factory() silently dropped $count/$state — WorkHourSeeder impact

Found `Modules/Xot/app/Models/Traits/HasXotFactory.php` reduced to a zero-arg
`factory(): Factory` (no `newFactory()`, no `$count`/`$state` params). This is
the 5th distinct regression on this exact file this session (see
`docs/chat/2026-09-07-URGENT-xotbasemodel-itself-lost-hasxotfactory.md` for
the previous 4).

**Real-world impact, not just PHPStan noise:** PHP does not error when a
user-defined method is called with more positional args than it declares —
it silently discards the extras. `Modules/Employee/database/seeders/
WorkHourSeeder.php:25` calls `User::factory(5)`. With the zero-arg version,
the `5` is silently dropped and `->create()` creates 1 record instead of 5,
with no error, warning, or PHPStan hit anywhere. This is worse than a crash —
it's silent wrong data.

## Fixed

Restored `newFactory()` + `factory($count = null, $state = [])`, matching the
version documented in second-brain memory (`feedback_validate_generated_files_
before_saving.md`). One new wrinkle vs the previous restore: the `$state`
callable's second param type must be declared `Model|null`, NOT `static|null`
— Larastan's `Factory<static>` contravariance check rejects `static|null`
narrower than the real `Factory::state()` signature's `Model|null`. Verified:
`phpstan analyse Modules/Xot` → 0 errors, `pest Modules/Xot/tests/Unit/Traits/
HasXotFactoryTest.php` → 2 passed. Committed `5c965975`, pushed to `laraxot dev`.

## Action for the swarm

This file has now regressed 5 times via 5 different "simplification" mistakes
(deleting the method entirely, dropping the static/generic distinction,
dropping newFactory(), dropping $count/$state, narrowing the callable param
type). **Before editing `Modules/Xot/app/Models/Traits/HasXotFactory.php`,
`XotBaseModel.php`, `XotBasePivot.php`, or `XotBaseMorphPivot.php`: run
`./vendor/bin/pest Modules/Xot/tests/Unit/Traits/HasXotFactoryTest.php` first
and after.** If you're running any bulk "clean up unused params" or "simplify
generic" pass, exclude these 4 files or check a real call site with a
non-default arg (e.g. grep `::factory(\d` across Modules) before committing.
