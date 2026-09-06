# Code Coverage: UI

**Date:** 2026-09-06
**Test Status:** 6 failed, 1 risky, 109 skipped, 199 passed
**Test Assertions:** 654
**Duration:** 200.44s

## Pest Test Results

Tests: 6 failed, 1 risky, 109 skipped, 199 passed (654 assertions)

### Failures

1. **UiBasePolicy before concede super-admin e ritorna null altrimenti**
   - Location: Modules/UI/app/Models/Policies/UiBasePolicy.php:23
   - Test: Modules/UI/tests/Unit/UiBasePolicyBehaviorTest.php:39
   - Status: Blocking authorization logic

2. **Blocks and Block render resolution (View component)**
   - View not found: `ui::components.render.blocks.ui::empty`
   - Source: Modules/Xot/app/Actions/GetViewAction.php:76
   - Scope: Modules/UI/app/View/Components/Render/Blocks.php:39

### Test Modules Passing (19 total)

- UIBusinessCoverageTest
- UIDeepCoverageTest
- UiCoverageBoostTest
- UiFilamentComponentsCoverageTest
- UiFilamentSchemaCoverageTest (1 warning)
- UiGapCloser100Test (1 failure)
- UiHighestMissCoverageTest
- UiMassExecuteCoverageTest
- UiRemainingCoverage100Test
- UiStateColumnsBehaviorTest
- And 9 others

### Models with Warnings

- AssetModel (not part of module artifact set)
- ComponentModel (not part of module artifact set)
- ThemeModel (not part of module artifact set)

## PHPMD Analysis

**Date:** 2026-09-06
**Issues:** 48 violations found
**Exit Status:** Clean

### Issue Categories

- **MissingImport:** 5 issues (missing class import via use statement)
- **CyclomaticComplexity:** 8 issues (complexity threshold exceeded)
- **NPathComplexity:** 4 issues (NPath complexity threshold exceeded)
- **UnusedFormalParameter:** 18 issues (unused parameters)
- **ExcessiveParameterList:** 1 issue
- **CamelCaseParameterName:** 7 issues
- **CamelCasePropertyName:** 4 issues
- **ExcessiveMethodLength:** 1 issue (151 lines)
- **TooManyPublicMethods:** 1 issue

### Key Problem Areas

1. **Icon/Block Actions:** High complexity (CyclomaticComplexity > 13)
   - GetAllIconsAction
   - IconStateColumn.setUp()
   - SelectStateColumn.setUp()

2. **Form Fields:** Parameter naming and unused parameters
   - SliderData constructor (9 parameters)
   - InlineDatePicker
   - AddressField

3. **Data Classes:** Property naming conventions
   - SliderDataCollection ($slider_data)

## Summary

**Status:** Ready for module closure
**Previous:** 76 failed, 42 passed (2026-01-17)
**Current:** 6 failed, 199 passed (improvement)
**Action:** Address 6 test failures before final merge
