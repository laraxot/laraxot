---
title: Fix 17 PHPStan errors across Filament Resources and Xot base classes
epic: 0
story_id: 1
slug: fix-phpstan-filament-xotbase-errors
status: completed
created: 2026-09-05
modules_affected:
  - Blog
  - Platform
  - Production
  - Vehicle
  - Rating
---

# Fix 17 PHPStan errors in Filament Resources and Xot base classes

**Scope:** Cross-module bugfix — resolve PHPStan L10 errors (non-ignorable) in Filament Resource Pages and Xot base classes.

**Owner:** None (cross-module) — each module commits independently after fix.

## Error Categories

### Category 1: Return Type Mismatches (10 errors)

**Pattern:** `getHeaderActions()` returns `array{...}` but type hint requires `array<string, Action|ActionGroup>`

**Affected files:**
- Blog/app/Filament/Resources/ArticleResource/Pages/ViewArticle.php:41
- Blog/app/Filament/Resources/BannerResource/Pages/EditBanner.php:17
- Blog/app/Filament/Resources/TextWidgetResource/Pages/EditTextWidget.php:18
- Platform/app/Filament/Resources/ServiceProviderResource/Pages/EditServiceProvider.php:18
- Platform/app/Filament/Resources/PlatformSettingResource/Pages/ListPlatformSettings.php:19
- Production/app/Filament/Resources/ProductionOrderResource/Pages/ListProductionOrders.php:17
- Vehicle/app/Filament/Resources/VehicleResource/Pages/ListVehicles.php:17

**Fix:** Add string keys to array literal: `array{0 => new EditAction(), 1 => new DeleteAction()}` → `['edit' => new EditAction(), 'delete' => new DeleteAction()]`

### Category 2: Method Finality Violations (5 errors)

**Pattern:** Non-abstract class contains abstract method or overrides final method

**Affected files:**
- Blog/app/Filament/Resources/ArticleResource/Pages/ViewArticle.php:22, 28
  - Must implement `getInfolistSchema()` (abstract from XotBaseViewRecord)
  - Cannot override `infolist()` (final in XotBaseViewRecord)
- Blog/app/Filament/Resources/ArticleResource/RelationManagers/RatingsRelationManager.php:22
  - Cannot override `form()` (final in XotBaseRelationManager)
- Blog/app/Filament/Resources/ProfileResource/RelationManagers/RatingMorphsRelationManager.php:23
  - Cannot override `form()` (final in XotBaseRelationManager)
- Platform/app/Filament/Resources/ServiceProviderResource/Pages/EditServiceProvider.php:25
  - Cannot override `infolist()` (final in XotBaseViewRecord)

**Fix Analysis:**
- Review XotBaseViewRecord and XotBaseRelationManager to determine if methods should be final
- If design intent is final: remove overriding methods in subclasses and refactor to use composition/hooks
- If design intent is flexible: remove `final` from base classes (requires Xot module decision)

### Category 3: Generic Type Mismatch (1 error)

**Affected file:**
- Rating/app/Models/AbstractRatingsHost.php:17

**Pattern:** `@use Modules\Rating\Models\Traits\HasRatingsTrait<Modules\Rating\Models\AbstractRatingsHost>` declares generic type, but trait `HasRatingsTrait` is not generic.

**Fix:** Remove generic type parameter from `@use` tag, or make trait generic (if semantically correct).

## Technical Analysis

### Why These Errors Occur

1. **Return type mismatches:** Filament v5 stricter typing for action arrays. Index-based array `array{...}` lacks string keys expected by type hint.
2. **Method finality:** XotBase classes enforce contracts via `final` to prevent behavior changes in subclasses. Overriding violates this contract.
3. **Generic mismatch:** Incorrect PHPDoc metadata on trait usage.

### Implementation Strategy

**Phase 1: Return type fixes (10 errors, low risk)**
- Audit each `getHeaderActions()` method
- Change from index-based to key-based array literal
- Run Pest to ensure action collection still works
- Commit per module

**Phase 2: Method finality review (5 errors, requires design decision)**
- Read XotBase design intent in Xot module docs
- Decide: remove `final` OR refactor subclasses to eliminate override
- If removing `final`: coordinate with Xot module
- Commit per module

**Phase 3: Generic fix (1 error, minimal)**
- Remove generic parameter or add trait generic
- Pest coverage should increase
- Commit to Rating module

## Acceptance Criteria

- [ ] All 17 PHPStan errors resolved
- [ ] No new PHPStan errors introduced
- [ ] Pest coverage increases for each module (no decrease)
- [ ] phpmd and phpinsights pass on all 5 modules
- [ ] Each module: git add -A && commit && push to all remotes
- [ ] No `@phpstan-ignore` or baseline suppressions added

## Definition of Done

- [ ] PHPStan clean: `./vendor/bin/phpstan analyse Modules --memory-limit=-1` returns 0 errors
- [ ] Each module Pest tests pass: `./vendor/bin/pest --configuration=phpunit.xml --testdox`
- [ ] Coverage docs updated per module: `laravel/Modules/<Mod>/docs/coverage.md`
- [ ] Each module synced with git: `cd laravel/Modules/<Mod> && git push --all`
- [ ] Root project CI passes (if CI exists)

## Notes

- Each module has its own `.git` directory (not submodule). Commits are independent per module.
- Before implementing phase 2, consult Xot module docs or xotbase-critical-rules.md
- Use blog/phpstan-l10-fix-workflow.md for systematic error resolution
