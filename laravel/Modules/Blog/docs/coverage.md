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
