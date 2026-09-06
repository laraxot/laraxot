---
name: HR Module - Test Coverage Baseline
description: Coverage baseline and testing strategy for HR module
metadata:
  status: baseline
  date: 2026-09-06
  version: 1.0
---

# HR Module - Test Coverage Baseline

## Overview
Pest test coverage baseline for HR module (Laravel Modules/HR).

## Test Summary (2026-09-06)

| Test Suite | Status | Duration | Notes |
|---|---|---|---|
| EmployeeCrudTest | PASS | 12.01s | Employee creation and soft-deletion |
| AbsenceRequestWorkflowTest | FAIL | - | Database schema issue: `roles` table missing |
| AddEmployeeAttachmentsTest | FAIL | - | Database schema issue: `document_media` table missing |
| AddEmployeeDocumentActionMutationTest | FAIL | - | Database schema issue: `document_media` table missing |
| AbsenceRequestResourceTypeNormalizationTest | ? | - | Not tested |

## Known Test Dependencies
- `roles` table (missing in test database)
- `document_media` table (missing in test database)
- User seeding for operator role

## Quality Gates - Current Status

### Code Quality

| Tool | Status | Notes |
|---|---|---|
| PHPStan Level 10 | GREEN | 0 errors |
| Laravel Pint | GREEN | Fixed formatting issues |
| PHPMD | PENDING | Manual review needed |
| Insights | PENDING | Manual review needed |

### Key Fixes Applied (2026-09-06)

1. **Type Narrowing in Form Closures**
   - DocumentsRelationManager: Helper method `stringValue()` for form data type narrowing
   - MedicalVisitsRelationManager: Proper type casting with isset() checks
   - TrainingsRelationManager: Same pattern as MedicalVisitsRelationManager

2. **Deprecated Filament v5 Methods**
   - Deprecated methods already migrated:
     - `actions()` → `recordActions()` 
     - `bulkActions()` → `toolbarActions()`

3. **Code Style**
   - Ordered imports fixed
   - Operator spacing normalized
   - PHPDoc alignment corrected

## Next Steps

1. Fix test database schema (roles, document_media tables)
2. Ensure all tests pass without database errors
3. Document any coverage gaps
4. Set up CI/CD baseline

## Files Modified

- Modules/HR/app/Filament/Resources/EmployeeResource/RelationManagers/MedicalVisitsRelationManager.php
- Modules/HR/app/Filament/Resources/EmployeeResource/RelationManagers/TrainingsRelationManager.php
- Modules/HR/app/Filament/Resources/EmployeeResource/RelationManagers/DocumentsRelationManager.php

## Related Stories
- XOT-18.11: HR PHPStan Level 10 fix
