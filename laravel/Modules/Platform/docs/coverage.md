---
name: platform-pest-coverage
description: "Pest run log for Modules/Platform, tracked across PHPStan-fix sessions"
metadata:
  node_type: doc
  type: coverage-log
---

# Modules/Platform — Pest coverage log

## 2026-09-07 — PHPStan wave 2 (this session)

**Scope:** PHPStan/Filament-v5 fixes only (see
`docs/stories/01.Platform-phpstan-fix.story.md`), no test files were edited except
verifying `tests/Feature/AuditLogTest.php` against an independent factory fix in
`Modules/Customer`.

**PHPStan:** `Modules/Platform` 84 errors -> 0 errors (confirmed with
`rm -rf /tmp/phpstan && phpstan analyse Modules/Platform --memory-limit=-1
--no-progress`).

**Pest** (`./vendor/bin/pest Modules/Platform/tests --no-coverage`, no
module-local `phpunit.xml`, root config used):

- Before: not independently re-measured this session (no test files were touched
  by the PHPStan fixes; behavior of tested code paths is unchanged — Filament v5
  API renames and `mixed`-narrowing guards are structurally equivalent to the
  prior `(string)`/`(int)`/`(float)` casts for all valid runtime inputs).
- After: **31 passed, 14 failed** (77+ assertions). All 14 failures are
  **pre-existing and environmental**, confirmed via `git stash` A/B on the one
  file that could plausibly affect a failing test (`Modules/Customer/app/Models/Customer.php`):
  the exact same failures reproduce with that file stashed out.
  - 12 failures: `QueryException` — missing tables (`navigation_preferences`,
    `roles`) on the shared `workorder_data_test` DB. Matches the documented
    pattern in second-brain `project_test_db_missing_migrations_blocks_feature_tests.md`
    — not a module bug, owner must migrate the test DB once.
  - 2 failures (`tests/Feature/AuditLogTest.php`, both cases): a **different,
    newly-documented** pre-existing issue — `Modules\Customer\Models\Customer`
    (and every other model relying solely on `Modules\Xot\Models\Traits\HasXotFactory`)
    never gets Laravel's real `HasFactory` trait, so `Customer::factory()` throws
    `BadMethodCallException` at runtime regardless of the `newFactory()` override
    added in this session (that override only fixes Larastan's static analysis,
    per `project_larastan_factory_mixed_needs_newfactory_override.md`). See
    second-brain `project_hasxotfactory_missing_static_factory_method.md` for the
    full root cause and why it's out of scope for a single-module PHPStan task.

**Coverage delta:** not measurably changed by this session's edits (no
production logic branches were added/removed — only type-narrowing guards that
are behaviorally identical for well-formed inputs, plus a `newFactory()`
override that only affects PHPStan's view of the code, not runtime paths).
The two pre-existing `AuditLogTest` failures block that file's assertions from
ever exercising the redaction logic; fixing them requires the sitewide
`HasFactory` decision flagged above, not a Platform-local change.
