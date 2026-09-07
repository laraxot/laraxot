---
name: blog-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Blog — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Blog — quality gate status (2026-09-07)

## PHPStan

- Before: 8 errors — `Filament\Forms\Components\BaseFileUpload::enableOpen()`/`enableDownload()`
  deprecated in Filament v5, replaced by `openable()`/`downloadable()`.
- Fix: mechanical rename in `CategoryForm.php`, `CategoryFormSchema.php`,
  `TextWidgetResource.php`, `TextWidgetResource/Schemas/TextWidgetForm.php`.
- After: **0 errors** (`phpstan analyse Modules/Blog`, clean cache).

## PHPMD

No new findings in touched files (pre-existing `ShortVariable` notes elsewhere,
unrelated to this fix).

## Pest

Not run for this module in this pass (mechanical, zero-behavior-change rename;
verified via PHPStan + `php -l` only). No functional change to test coverage.

## 2026-09-07 (later same day) — Profile::$model static/instance collision fix

`Modules\Blog\Http\Livewire\Profile` had `public BlogProfile $model;`
colliding with `Filament\Pages\Page`'s static `$model` — PHPStan
`property.nonStatic` (non-ignorable, a real PHP engine-level incompatibility).
Renamed to `$profile` throughout the class and `heading_photo.blade.php`.

No dedicated Pest test exists yet for `Modules\Blog\Http\Livewire\Profile`
(checked `Modules/Blog/tests` — no `*Profile*` file). Not adding one in
this pass: reproducing this class's Livewire mount cycle needs a real
`BlogProfile` fixture and this module's test harness wasn't otherwise
touched today. Flagged here as a coverage gap for a future pass, not
silently left undocumented.

phpstan analyse Modules/Blog: 0 errors (before this second fix: 1).

## 2026-09-07 (later same day, 2) — typeCoverage.constantTypeCoverage: 2 constants typed

`typeCoverage.constantTypeCoverage` is a **repo-wide** percentage, only visible
running `phpstan analyse Modules` (full tree), not `analyse Modules/Blog`
alone. Full-tree run before this fix: 165/300 constants typed = **55.0%**,
135 `file_errors`, one of them `Modules/Blog/database/seeders/TagSeeder.php:16`.

### Fix

- `app/Http/Livewire/Article/Lists.php:18` — `public const ITEMS_PER_PAGE = 10;`
  had no type declaration. Also found an uncommitted, incorrect WIP attempt
  already on disk (`/** @var int */` PHPDoc-only, no real type — the exact
  anti-pattern documented in
  `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`).
  Discarded that and applied the correct fix: `public const int
  ITEMS_PER_PAGE = 10;` (no PHPDoc needed for a scalar, same verified pattern
  as `Rating/app/Filament/Blocks/Rating.php`).
- `database/seeders/TagSeeder.php:16` — `private const TAGS = [...]` already
  had a correct `@var list<array{name: array{it: string, en: string}, type:
  string}>` docblock (real shape, not a wrong `list<string>`), but no real
  type declaration on the constant itself. Added `array`:
  `private const array TAGS = [...]`.

### Verification (full-tree, clean result cache)

- Before: `Out of 300 possible constant types, only 165 - 55.0 %`. Blog's
  `TagSeeder.php` listed among the 135 `file_errors`.
- After: `Out of 300 possible constant types, only 186 - 62.0 %`. Blog no
  longer appears anywhere in the full-tree `file_errors` list. (The jump from
  165→186, not just +2, reflects other concurrent agent sessions fixing other
  modules' constants in the same window — this repo has many AI agents
  working the same tree in parallel today; Blog's own contribution is exactly
  the 2 constants above, both individually confirmed absent from the "after"
  file_errors.)
- `phpstan analyse Modules/Blog --memory-limit=-1` (clean cache): **0 errors**,
  before and after (scoped runs never showed this bucket — that's the whole
  point of it being a full-tree metric).

### Blocking pre-existing debt fixed in this pass: duplicate lowercase test dirs

`Modules/Blog/tests/unit/` and `tests/feature/` were git-tracked duplicates of
`tests/Unit/` and `tests/Feature/` (PSR-4 case violation). `tests/unit/SumTest.php`
redeclared the global `sum()` function already declared in
`tests/Unit/SumTest.php`, causing a **fatal error** that prevented `pest
Modules/Blog/tests` from running at all — a hard blocker for the "Pest verde"
requirement of this pass. Verified every file in both lowercase dirs had an
identical PascalCase counterpart (`.gitkeep`, `SumTest.php`) before removing:

```
git rm -r --cached tests/unit tests/feature
rm -rf tests/unit tests/feature
```

### Pest — before/after

- Before this pass: `pest Modules/Blog/tests` → **fatal error**, 0 tests
  runnable (blocked by the duplicate-directory issue above).
- After: `pest Modules/Blog/tests --no-coverage` → **1 passed** (1 assertion),
  `Modules\Blog\tests\Unit\SumTest`.
- Coverage: this module's only Pest test (`SumTest`) exercises a standalone
  helper function, not any `Modules\Blog` class — line coverage of the
  module's actual application code is effectively **0%**, unchanged by this
  pass (this was already true before; the fix restored the ability to run
  the suite at all, it did not add new tests). Real coverage of Blog's
  Livewire/Models/Actions remains a pre-existing gap, same class of gap
  already flagged above for `Profile`. Not addressed in this pass — out of
  scope (this pass's mandate is `typeCoverage.constantTypeCoverage`, not
  new test authoring).

### PHPMD (`tools/phpmd.sh Modules/Blog/app text phpmd.xml`)

9 pre-existing violations, **none in the two files touched by this fix**:
`LongClassName` (1), `ExcessiveParameterList` (2), `MissingImport` (2),
`UnusedFormalParameter` (3), `TooManyPublicMethods` (2). Structural
(parameter-count/public-API shape), not mechanical — left as documented debt,
not fixed in this pass.

### PHPInsights

Not run: this repo's PHPInsights was removed (incompatible with Pest 5, per
repo tooling notice surfaced during this session). Skipped, not silently
omitted.

### Out of scope, left untouched

Found uncommitted in the working tree, unrelated to this fix, not verified or
tested by this session: `app/Filament/Pages/Dashboard.php` (rename
`Filament\Pages\Page` → `Xot\Filament\Pages\XotBasePage`) and an untracked
`phpmd.ruleset.xml`. Not included in this fix's commit.

Story: `docs/stories/6.1.blog-typecoverage-constant-fix.story.md`.
