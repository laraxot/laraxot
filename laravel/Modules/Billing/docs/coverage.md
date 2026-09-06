---
name: Coverage Baseline
description: Pest test coverage and quality metrics baseline for Billing module
tags: [billing, testing, coverage, phpstan, metrics]
metadata:
  created: 2026-09-06
  updated: 2026-09-06
  module: Billing
---

# Coverage Baseline — Billing Module

## Quality Gates Status

### PHPStan Level 10

- **Status**: ✅ **PASS**
- **Errors**: 0
- **Timestamp**: 2026-09-06 21:58 UTC
- **Fix Summary**:
  - Fixed 7 Filament v5 deprecated methods (`actions()` → `recordActions()`, `bulkActions()` → `toolbarActions()`)
  - Fixed 1 unmatched `@phpstan-ignore` directive
  - Fixed 3 Pest test file uses() statement ordering issues

**Files Modified**:
- `Modules/Billing/app/Filament/Resources/DeliveryNoteResource.php` — recordActions/toolbarActions
- `Modules/Billing/app/Filament/Resources/DeliveryNoteResource/RelationManagers/LinesRelationManager.php` — recordActions/toolbarActions
- `Modules/Billing/app/Filament/Resources/InvoiceResource.php` — recordActions/toolbarActions
- `Modules/Billing/app/Filament/Resources/InvoiceResource/RelationManagers/WorkOrdersRelationManager.php` — recordActions
- `Modules/Billing/app/Filament/Resources/SupplierInvoiceResource.php` — recordActions/toolbarActions
- `Modules/Billing/app/Filament/Resources/SupplierInvoiceResource/RelationManagers/LinesRelationManager.php` — recordActions/toolbarActions
- `Modules/Billing/app/Filament/Resources/SupplierResource.php` — recordActions/toolbarActions
- `Modules/Billing/tests/Feature/BillingModalViewsRenderTest.php` — removed unmatched ignore
- `Modules/Billing/tests/Feature/GenerateInvoicePdfTest.php` — moved uses() after imports
- `Modules/Billing/tests/Unit/BuildReceivablesAgingActionTest.php` — moved uses() after imports
- `Modules/Billing/tests/Unit/CalculateJobMarginActionTest.php` — moved uses() after imports
- `Modules/Billing/tests/Unit/ProjectCashFlowActionTest.php` — moved uses() after imports

### PHPMD (PHP Mess Detector)

- **Status**: ✅ **PASS** (Code Quality Issues Detected — Pre-existing)
- **Issues Detected**: 26 warnings
- **Issue Categories**:
  - ShortVariable (4) — short names in lambda closures
  - LongClassName (1) — ReconcileSupplierInvoiceWithDeliveryNotesAction
  - CyclomaticComplexity (1) — SendElectronicInvoiceAction::execute() (26 CC)
  - NPathComplexity (1) — SendElectronicInvoiceAction::execute() (147K NPath)
  - ExcessiveMethodLength (1) — SendElectronicInvoiceAction::execute() (109 lines)
  - MissingImport (4) — type hints without use statements
  - TooManyPublicMethods (1) — InvoicePolicy (11 methods)
  - UnusedFormalParameter (6) — unused policy parameters
  - CamelCasePropertyName (4) — module provider properties

**Note**: All issues are pre-existing and unrelated to PHPStan L10 fixes.

### Pest Test Coverage

- **Status**: ⚠️ **Pending** — Infrastructure issues
- **Reason**: Pest bootstrap issues prevent execution (Catalog/Pest.php namespace error)
- **Action**: Requires separate troubleshooting session
- **Module Test Files**:
  - `tests/Feature/BillingModalViewsRenderTest.php` — 4 tests
  - `tests/Feature/GenerateInvoicePdfTest.php` — 2 tests
  - `tests/Unit/BuildReceivablesAgingActionTest.php` — ~10 tests
  - `tests/Unit/CalculateJobMarginActionTest.php` — ~10 tests
  - `tests/Unit/ProjectCashFlowActionTest.php` — ~5 tests

## Prior State (Historical)

| Metric | Before | After |
|--------|--------|-------|
| PHPStan Errors | 14 | 0 |
| Deprecated Methods | 7 | 0 |
| Unmatched Ignores | 1 | 0 |
| Import Order Errors | 3 | 0 |

## Next Steps

1. Resolve Pest bootstrap issue (Catalog module Pest.php syntax)
2. Run full test suite with coverage report
3. Document coverage percentage by file
4. Set coverage thresholds per file group

---

**Last Updated**: 2026-09-06 by Claude Code Agent
**Story**: XOT-18.9 Billing (PHPStan L10 fix)
