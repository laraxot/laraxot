---
id: comment-phpstan-analysis
title: PHPStan Analysis — Comment Module
phase: Analysis (BMAD)
status: done-analysis / no-fix-required
---

## 1. State (BMAD)
Module: `Modules/Comment` (Commenti, moderazione, notifiche, Filament 5).
Run: `vendor/bin/phpstan analyze Modules/Comment/app --level=max`.

## 2. Errors Found (verified, 2026-10-15)
Only **1 error** — `trait.unused` on `InteractsWithComments.php:18`.

## 3. Purpose Verification
`InteractsWithComments` is **NOT dead code**. Cross-module usages:
- `Modules/Fixcity/app/Models/User.php` — `use InteractsWithComments` (uses comment-notification contracts).
- `tests/Fixtures/Concerns/InteractsWithCommentsContractStub.php` — test stub implementing `CanComment`.
- `tests/Unit/Concerns/InteractsWithCommentsContractTest.php` — contract test.

The `trait.unused` is a **scoped-analysis false positive** (PHPStan scans only `Comment/app`, not `Fixcity/app`).

## 4. Existing Suppression Pattern (intentional)
- `HasComments.php:23`: `@phpstan-ignore method.childReturnType` on `comments()` MorphMany return — standard Laravel inheritance suppression, not an error to fix.

## 5. Decision (BMAD Check)
No code fix required; the error is a scope artifact. No suppression comments or baselines added. Module is clean at max level.

## 6. Next
Update docs/wiki to note the cross-module trait dependency so future analysts don't treat `trait.unused` as a real error.
