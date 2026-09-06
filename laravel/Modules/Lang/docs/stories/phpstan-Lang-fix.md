---
id: phpstan-Lang-fix
slug: phpstan-Lang
scope: [module:Lang, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06
related:
  - "./lang-mixed-type-reduction.story.md"
  - "./lang-services-to-actions.story.md"
  - "./lang-duplicate-array-keys.story.md"
  - "../coverage.md"
---

## Problema
PHPStan errors in Modules/Lang: `class.notFound` on `@property-read` docblocks in
`app/Models/LanguageLine.php` and `app/Models/Post.php` referencing
`Modules\Quaeris\Models\Profile`, a class that does not exist in this codebase
(leftover from an unrelated project — no `Modules/Quaeris` anywhere in the tree).

## Solution
1. Analyze with phpstan → 6 errors, all `class.notFound` on the same bogus
   `Modules\Quaeris\Models\Profile` reference (3 properties x 2 files).
2. Fix root cause: replaced `Modules\Quaeris\Models\Profile` with
   `Modules\Xot\Contracts\ProfileContract` in both files' `use` imports and
   `@property-read` docblocks — matching the established pattern already used
   identically by `Modules/Geo/app/Models/Location.php`,
   `Modules/Employee/app/Models/Position.php`, `Modules/Job/app/Models/Task.php`,
   `Modules/Notify/app/Models/NotificationTemplate.php`, etc. Both models already
   inherit the real `creator()`/`updater()`/`deleter()` `BelongsTo` relations (typed
   `ProfileContract`) from `Modules\Xot\Traits\Updater` via `XotBaseModel` — the
   docblocks were a stale, incorrect re-declaration, not a missing relation.
3. Verified with phpmd (clean on both files) + pest (Modules/Lang tests, see
   `../coverage.md`).
4. Git sync: converged with a concurrent session that independently landed the
   identical fix in the same window (commit `fc3b2ea6`, already pushed to
   `laraxot/dev` before this session's own commit was needed — see coverage.md for
   full session notes on that same commit, which also covered mixed-type and other
   PHPStan work in the same module pass).

## Acceptance criteria — verified
- `./vendor/bin/phpstan analyse Modules/Lang` (cache cleared first): **0 errors**.
- No `@phpstan-ignore`, no baseline entries, `phpstan.neon` untouched.
- `Profile`/`User` access goes through `Modules\Xot\Contracts\ProfileContract`
  (already the case via the `Updater` trait; docblocks now consistent with it).
