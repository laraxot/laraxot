---
name: HR Module - PHPStan L10 fix + coverage
description: PHPStan Level 10 fix results and Pest coverage snapshot for the HR module
metadata:
  status: done
  date: 2026-09-07
  version: 1.3
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

## 2026-09-07 — `typeCoverage.constantTypeCoverage` (story 2.8, project-wide)

`typeCoverage.constantTypeCoverage` (package `tomasvotruba/type-coverage`) is a
**project-wide** metric: it only fires when PHPStan analyses the full configured
`paths: ./Modules/` (its rule explicitly skips subpath scans — see
`ScopeConfigurationResolver::areFullPathsAnalysed()` — so
`phpstan analyse Modules/HR` alone always reports `[OK] No errors` for this rule
regardless of whether HR's own constants are typed). Verification therefore
requires a full-tree `phpstan analyse Modules` run, grepped for `Modules/HR/`.

| | HR occurrences (full-tree scan) |
|---|---|
| Before | 5 (`AbsenceRequestResource.php` ×3, `EmployeeResource.php` ×2) |
| After | 0 |

Fixed by adding explicit types to the 5 untyped class constants (no logic
change):

- `AbsenceRequestResource.php`: `private const ABSENCE_TYPE_ALIASES = [...]` →
  `private const array ABSENCE_TYPE_ALIASES = [...]` (also removed an
  accidental duplicate `/** @var array<string, string> */` docblock line
  above it, pre-existing before this fix); `private const ACCOUNT_FEATURE_ADMIN`
  and `..._OPERATOR` → `private const string ...` (redundant `/** @var string */`
  docblocks removed, now redundant with the native type).
- `EmployeeResource.php`: same `ACCOUNT_FEATURE_ADMIN` / `..._OPERATOR` →
  `private const string ...` fix.

Verified with a fresh full-tree scan (`phpstan clear-result-cache` then
`phpstan analyse Modules --error-format=raw` on the stabilized post-edit
tree): 0 matches for `Modules/HR/` of any identifier, and project-wide
constant coverage moved from 209/300 (69.6%) to 265/300 (88.3%) — the
remaining project-wide gap is in other modules (Billing, Bom, Compliance,
Platform, Quotation, WorkOrder), out of HR's scope.

### Pest — regression check

`./vendor/bin/pest --testsuite=HR --no-coverage` after the fix: **6 failed, 2
passed** (was 5 failed / 3 passed in the 2026-09-06 baseline above). This is
**not a regression from this fix** — verified via `git stash` A/B test
(stashing only the 2 files touched here reproduces the exact same failure
with the stash popped back out): `EmployeeCrudTest` and part of
`AddEmployeeDocumentActionMutationTest` now fail with
`InvalidArgumentException: Database connection [hr] not configured`,
independent of whether this fix's 2 constant-typing edits are applied or
not. Root cause: `Modules/HR/app/Models/BaseModel.php` now declares
`protected $connection = 'hr';` (committed separately, outside this story,
by other concurrent HR-module work — see `git log --oneline -- app/Models/BaseModel.php`),
but `config/database.php` has no `hr` connection configured. Pre-existing
infrastructure gap, not caused by and out of scope for story 2.8 — flagged
here for whoever owns the HR `BaseModel`/database-connection work.

### PHPMD / PHPInsights — regression check

- `./tools/phpmd.sh` on the 2 touched files (against `Modules/HR/phpmd.ruleset.xml`):
  only pre-existing `CouplingBetweenObjects` on both Resources (documented
  above, not introduced by this fix — constant type annotations don't change
  coupling).
- `./tools/phpinsights.sh analyse Modules/HR --no-interaction`: the shared
  `phpinsights.php` `disable-security-check: true` setting does not actually
  suppress `ForbiddenSecurityIssues` when scoped to a single module (it looks
  for `composer.lock` under the analysed path's ancestor, not the app root —
  known unrelated tool limitation). Worked around with an ephemeral
  `--config-path=/tmp/...` removing only that one insight (no change to the
  shared `laravel/phpinsights.php`). Result: Code 91.8, Complexity 95.6,
  Architecture 82.4, Style 84.3 — all above the 70-point thresholds.

### Files touched (story 2.8 only)

- `app/Filament/Resources/AbsenceRequestResource.php`
- `app/Filament/Resources/EmployeeResource.php`

### Note on unrelated pre-existing WIP found in the working tree

At claim time, `git status` in `Modules/HR` already showed uncommitted,
unrelated changes not authored in this session: `AbsenceRequestResource/Pages/ViewAbsenceRequest.php`
and `EmployeeResource/Pages/ViewEmployee.php` (a `ViewRecord` →
`XotBaseViewRecord`/`getInfolistSchema()` canonical-structure migration, same
pattern as the Billing 4.1 story) and an untracked `phpmd.ruleset.xml`. These
were left untouched and **not included** in this story's commit — committing
someone else's in-flight, unreviewed work under a `[story: 2.8]` constants
commit would misattribute it. Whoever owns that WIP should commit it
separately.

## 2026-09-07 (later) — `method.unused` on `AbsenceRequestResource::getAbsenceTypeOptions()`

Scoped run `./vendor/bin/phpstan analyse Modules/HR --no-progress --memory-limit=-1`
(cache cleared first) found **1 error**:

| File | Errors before | Errors after |
|---|---|---|
| `AbsenceRequestResource.php` | 1 (`method.unused`) | 0 |
| Module total | 1 | 0 |

### Root cause

By the time this session started, `Modules/HR` already had a large **uncommitted**,
in-progress canonical-structure extraction (story
`docs/stories/4.14.3.hr-canonical.story.md`, `AbsenceRequestResource`/`EmployeeResource`
→ `Schemas/<Name>Form.php` + `Tables/<Plural>Table.php`, same pattern as
`Modules/Billing` 4.1) sitting in the working tree. That extraction duplicated
`AbsenceRequestResource::getAbsenceTypeOptions()` into the new
`AbsenceRequestResource/Schemas/AbsenceRequestForm.php`, but left the original
private method behind in the Resource — now genuinely dead code (confirmed via
repo-wide grep: no remaining caller). `translateAbsenceType()` /
`normalizeAbsenceType()` / `ABSENCE_TYPE_ALIASES` on the Resource are **not**
dead — they're still called from `Pages/ListAbsenceRequests.php` and from a
`ReflectionMethod` in `tests/Feature/AbsenceRequestResourceTypeNormalizationTest.php`
— so those were left untouched.

### Fix

Removed the dead `private static function getAbsenceTypeOptions(): array` from
`AbsenceRequestResource.php` (root cause: superseded duplicate, not a symptom
suppression). Ran `./vendor/bin/pint Modules/HR/app/Filament/Resources/AbsenceRequestResource.php`
(scoped to this single file, **not** `--dirty`, to avoid reformatting other
agents' in-flight files elsewhere in the repo per known `pint --dirty` scope
gotcha) which additionally removed 14 now-unused `use` imports left behind by
the same in-progress extraction (`Filament\Actions\*`, `Filament\Forms\Components\*`,
`Filament\Schemas\Components\*`, `Filament\Tables*`, the two HR Action classes)
— those imports became dead the moment the extraction moved `getFormSchema()`/
`table()` out of the Resource, independently of this fix.

### Side-effect regression found and documented, not fixed here

Diffing the working tree against `HEAD` (which already contains story 2.8's
committed constant-typing fix) shows the in-progress 4.14.3 extraction was
built from a **stale pre-2.8 base**: it silently reverts
`private const array ABSENCE_TYPE_ALIASES` back to untyped
`private const ABSENCE_TYPE_ALIASES` (plus reintroduces the duplicate
`/** @var array<string, string> */` docblock line), and `private const string
ACCOUNT_FEATURE_ADMIN`/`..._OPERATOR` back to untyped with `@var` docblocks —
and it uncommitted-deleted this entire "2026-09-07 — story 2.8" section from
this file. Not fixed as part of this session's scope (out of scope per the
"limit changes to files needed for the real PHPStan error, don't absorb
another agent's in-progress migration" rule) — flagged here so the 4.14.3
owner rebases/reconciles before committing. This section itself has been
restored (it was found deleted, uncommitted, in the working tree) since
losing previously-completed, previously-committed documentation to an
unrelated concurrent edit is not an acceptable side effect.

### Quality gate

- `php -l`: clean.
- PHPStan `Modules/HR` scoped, cache cleared: **0 errors** (was 1).
- PHPMD (`./tools/phpmd.sh Modules/HR text Modules/HR/phpmd.ruleset.xml`): only
  pre-existing findings across the module (`LongVariable` in 3 `AddEmployee*Action`
  constructors + `tests/TestCase.php`, `CouplingBetweenObjects` on the 2
  RelationManagers, `UnusedFormalParameter` on `$model` in the 3
  RelationManagers and on the Policy methods) — `AbsenceRequestResource.php`
  itself has **zero** findings after this fix (previously flagged for
  `CouplingBetweenObjects`, now below threshold since the dead method/imports
  were removed).
- PHPInsights (`./vendor/bin/phpinsights analyse Modules/HR`, ephemeral
  `/tmp` config removing only `ForbiddenSecurityIssues` — same scoped-module
  `composer.lock` workaround as story 2.8, shared `phpinsights.php`
  untouched): Code 91.8, Complexity 93.9, Architecture 82.4, Style 84.3 — all
  ≥ 80, exit code 0.
- Pest `./vendor/bin/pest --testsuite=HR --no-coverage`: **6 failed / 2
  passed**, identical to the post-story-2.8 baseline above — no regression.
  Same pre-existing infra gaps (`Database connection [hr] not configured`,
  `Base table or view not found: roles`), not caused by and not fixable
  within this module's scope.
- Coverage: a full numeric before/after on the whole module isn't obtainable
  because the 6 pre-existing Pest failures above block collection on most of
  the module (same blocker documented in story 2.8). Targeted line-coverage
  check on the one green test that exercises this class
  (`AbsenceRequestResourceTypeNormalizationTest`, `XDEBUG_MODE=coverage
  ./vendor/bin/pest Modules/HR/tests/Feature/AbsenceRequestResourceTypeNormalizationTest.php
  --coverage`) shows `AbsenceRequestResource.php` at 48.5% lines covered
  post-fix. Coverage cannot have decreased: the removed method had zero test
  callers (0% covered) both before and after, so removing it only shrinks the
  uncovered-lines denominator — the percentage can only stay flat or rise,
  never fall, per this specific change.

### Files touched (this session)

- `app/Filament/Resources/AbsenceRequestResource.php` (dead-code removal +
  import cleanup)
- `docs/coverage.md` (this section + restored the deleted 2.8 section)
- `docs/stories/phpstan-HR-fix.md` (this session's BMAD story, closed)

### Git

**Not committed.** `AbsenceRequestResource.php`'s working-tree diff against
`HEAD` is inseparable from the in-progress, uncommitted story-4.14.3
extraction (same file, overlapping hunks — the dead method this fix removes
only exists because 4.14.3 duplicated it elsewhere first). Committing that
file would either (a) bundle in and ship someone else's multi-file,
not-fully-audited-by-this-session feature under a phpstan-fix commit
message, or (b) if scoped with `git add -p` to only this session's hunks,
produce a broken intermediate commit (`getFormSchema()`/`table()` still
inline and calling `self::getAbsenceTypeOptions()`, which this fix deletes).
Neither is acceptable. The code fix is applied and verified in the shared
working tree (per "shared filesystem, concurrent sessions" — multiple agent
sessions read/write the same tree) and will be carried forward automatically
whenever the 4.14.3 owner commits that file. Only this session's own
documentation (`docs/coverage.md`, `docs/stories/phpstan-HR-fix.md`) was
committed directly.
