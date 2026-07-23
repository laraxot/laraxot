---
title: "Filament Resource Zen Pattern (Blog Module)"
type: concept
sources: []
confidence: high
created: 2026-05-06
updated: 2026-05-06
tags: [filament, xotbase, zen-pattern, blog-module]
related:
  - ../../Xot/docs/wiki/concepts/xotbase-resource-zen-pattern.md
  - ../../Xot/docs/wiki/concepts/xotbase-resourceform-zen-pattern.md
  - ../../Xot/docs/wiki/concepts/xotbase-resource-table-zen-pattern.md
---

# Filament Resource Zen Pattern (Blog Module)

## Overview (2026-05-06)

Implementation of Zen philosophy for Blog module Filament resources per `wizard.txt` instructions.

## Core Zen Rules

1. **XotBaseResource does the magic** - don't override `form()`/`table()`
2. **XotBaseResourceForm** - `getFormSchema()` must be **static**
3. **XotBaseResourceTable** - no `configure()` override, only `getTable*()` static methods
4. **Safe functions mandatory** - `use function Safe\...` never removed
5. **No `->label()`/`->tooltip()`** - LangServiceProvider owns translations

## Blog Module Resources Status

| Resource | Form Schema | Table Schema | Zen Compliant |
|----------|-------------|--------------|------------------|
| ArticleResource | ✅ ArticleForm.php | ✅ ArticlesTable.php | ✅ |
| BannerResource | ✅ BannerForm.php | ✅ BannersTable.php | ✅ |
| CategoryResource | ✅ CategoryForm.php | ✅ CategoriesTable.php | ✅ |
| TextWidgetResource | ✅ TextWidgetForm.php | ✅ TextWidgetsTable.php | ✅ Fixed 2026-05-06 |

## Key Fixes Applied (2026-05-06)

### TextWidgetResource.php
- Removed wrong `table()` override (Zen: XotBaseResource does magic)
- Created `Tables/TextWidgetsTable.php` with correct `getTableColumns()` static method
- Fixed `getTableActions()` return type to match parent `array<int|string, Action|ActionGroup>`

## Verification

All Blog module resources now pass PHPStan level 5 with 0 errors, 0 ignores, 0 baselines.

## References

- Base class: `Modules/Xot/app/Filament/Resources/XotBaseResource.php`
- Wizard prompt: `laravel/Themes/Sixteen/docs/prompts/wizard.txt`
- Project wiki: `laravel/docs/wiki/index.md`
