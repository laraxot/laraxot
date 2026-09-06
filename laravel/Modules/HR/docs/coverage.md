---
name: HR Module - PHPStan L10 fix + coverage
description: PHPStan Level 10 fix results and Pest coverage snapshot for the HR module
metadata:
  status: done
  date: 2026-09-06
  version: 1.1
---

# HR Module — PHPStan Level 10 fix + coverage

## PHPStan (level max, `laravel/phpstan.neon`, not modified)

| | Errors |
|---|---|
| Before | 24 |
| After | 0 |

Verified twice with a cleared result cache:
`./vendor/bin/phpstan clear-result-cache && ./vendor/bin/phpstan analyse Modules/HR --memory-limit=-1` → `[OK] No errors`.

Breakdown of the 24 fixed errors:

| File | method.deprecated | cast.string |
|---|---|---|
| `AbsenceRequestResource.php` | 2 | — |
| `EmployeeResource.php` | 2 | — |
| `.../DocumentsRelationManager.php` | 2 | 3 |
| `.../MedicalVisitsRelationManager.php` | 2 | 5 |
| `.../TrainingsRelationManager.php` | 2 | 6 |

Fixes: `actions()`→`recordActions()`, `bulkActions()`→`toolbarActions()` (Filament
v4/v5 rename); replaced blind `(string) $data['x']` casts on the untyped Filament
`array $data` form-data parameter with a private `stringValue()` helper that
narrows via `is_string()`/`is_scalar()` before returning — no `mixed` left
unresolved, no `@phpstan-ignore` added.

## Pest — `./vendor/bin/pest --testsuite=HR --no-coverage`

| | Result |
|---|---|
| Before (verified via `git stash`) | 5 failed / 3 passed (10 assertions) |
| After | 5 failed / 3 passed (10 assertions) — unchanged |

Confirmed via A/B test (`git stash` the 5 fixed files, run, compare, `git stash
pop`): identical pass/fail set before and after. The fix introduces **no
regression**.

Passing: `AbsenceRequestResourceTypeNormalizationTest` (2/2),
`EmployeeCrudTest` (1/1).

Failing (pre-existing, environment-level, not code bugs): `AbsenceRequestWorkflowTest`
(2), `AddEmployeeAttachmentsTest` (1), `AddEmployeeDocumentActionMutationTest` (2) —
all `QueryException`:
- `Base table or view not found: roles` (connection `mariadb`, Spatie Permission
  migration not present in `workorder_data_test`).
- `Base table or view not found: document_media` (connection `document`, Document
  module migration not present in `workorder_data_test`).

Not fixed here: these tables belong to other modules'/packages' migrations, not
to HR, and fixing them requires either migrating the shared test database (touches
connections/modules outside HR scope, risky while other agents run parallel
migrations/tests on the same DB) or a Document/Permission-module fixture that HR
doesn't own. Flagged in
`docs/chat/2026-09-06-pest-v5-done-plus-phpstan-parallel-wave.md`.

Coverage could not be numerically raised on the 3 already-red suites without
unblocking the DB first; no coverage was lost either. New/changed production code
(the `stringValue()` helpers) is exercised indirectly by the tests above once the
DB blocker is cleared — no new production logic branch was added that isn't
already covered by an existing (currently DB-blocked) test.

## PHPMD (`Modules/Xot/phpmd.ruleset.xml` — canonical, no local HR ruleset existed)

Pre-existing findings, none introduced or removed by this fix (verified the 3
touched RelationManagers' `UnusedFormalParameter $model` predates this change —
required by Filament's `CreateAction::using()` closure signature, not removable):

- `CouplingBetweenObjects` on `AbsenceRequestResource`, `EmployeeResource`,
  `MedicalVisitsRelationManager`, `TrainingsRelationManager`.
- `UnusedFormalParameter` in the 3 RelationManagers (`$model`) and in
  `AbsenceRequestPolicy`/`EmployeePolicy` (standard Laravel Policy signatures).
- `LongVariable` in `AddEmployee*Action` constructors and `tests/TestCase.php`.

Out of scope for this story (not touched).

## Pint

`./vendor/bin/pint --test` scoped to the 5 files touched by this fix → `passed`
(no formatting changes needed). Module-wide `Modules/HR` has pre-existing,
unrelated style debt (`fully_qualified_strict_types`, `no_unused_imports`, etc. in
several test/model/policy files) not touched by this story.

## Files modified

- `app/Filament/Resources/AbsenceRequestResource.php`
- `app/Filament/Resources/EmployeeResource.php`
- `app/Filament/Resources/EmployeeResource/RelationManagers/DocumentsRelationManager.php`
- `app/Filament/Resources/EmployeeResource/RelationManagers/MedicalVisitsRelationManager.php`
- `app/Filament/Resources/EmployeeResource/RelationManagers/TrainingsRelationManager.php`

## Related story

`docs/stories/01.HR-phpstan-fix.story.md`
