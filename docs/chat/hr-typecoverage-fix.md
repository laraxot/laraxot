---
title: HR module — typeCoverage.constantTypeCoverage fixed (story 2.8)
type: coordination
tags: [phpstan, type-coverage, hr, story-2.8]
created: 2026-09-07T15:26:00+02:00
agent: claude (sonnet 5)
status: done
---

# HR — `typeCoverage.constantTypeCoverage` closed (story 2.8, Wave A)

5 untyped class constants fixed in 2 Filament Resources, no functional change:

- `Modules/HR/app/Filament/Resources/AbsenceRequestResource.php`
  - `private const ABSENCE_TYPE_ALIASES = [...]` → `private const array ABSENCE_TYPE_ALIASES = [...]`
    (also removed a pre-existing accidental duplicate `/** @var array<string, string> */` docblock line)
  - `private const ACCOUNT_FEATURE_ADMIN` / `..._OPERATOR` → `private const string ...`
    (removed now-redundant `/** @var string */` docblocks)
- `Modules/HR/app/Filament/Resources/EmployeeResource.php`
  - same `ACCOUNT_FEATURE_ADMIN` / `..._OPERATOR` → `private const string ...`

## Why this needed a full-tree scan to verify

`typeCoverage.constantTypeCoverage` (package `tomasvotruba/type-coverage`) is
a **project-wide** metric. Its rule explicitly no-ops when PHPStan is given a
subpath instead of the full configured `paths: ./Modules/`
(`TomasVotruba\TypeCoverage\Configuration\ScopeConfigurationResolver::areFullPathsAnalysed()`).
So `phpstan analyse Modules/HR` alone always prints `[OK] No errors` for this
identifier — before AND after the fix — regardless of whether HR's own
constants are typed. Verification required
`phpstan clear-result-cache && phpstan analyse Modules --error-format=raw`
(full tree) and grepping the raw output for `Modules/HR/`.

Before: 5 occurrences (`Modules/HR/` matches for this identifier).
After: 0. Project-wide coverage moved 209/300 (69.6%) → 265/300 (88.3%) in
the same run (other agents fixing other modules concurrently account for
part of that delta too). Remaining project-wide gap is outside HR: Billing,
Bom (`ViewBom.php`), Compliance, Platform, Quotation, WorkOrder.

## Quality gate

- `php -l`: clean on both files.
- PHPStan `Modules/HR` scoped: 0 errors before/after (this rule aside — see
  above). Full-tree fresh scan: 0 `Modules/HR/` matches of any identifier.
- PHPMD (`Modules/HR/phpmd.ruleset.xml`, pre-existing, not created by this
  session): only pre-existing `CouplingBetweenObjects` on both Resources —
  not introduced, not correlated with constant typing.
- PHPInsights: `--disable-security-check` flag does not actually suppress
  `ForbiddenSecurityIssues` when scoped to one module (looks for
  `composer.lock` under the analysed path's ancestor, not app root — known
  tool limitation, see second-brain memory). Worked around with an ephemeral
  `/tmp` `--config-path` removing only that insight (shared `phpinsights.php`
  untouched). Result: Code 91.8, Complexity 95.6, Architecture 82.4, Style
  84.3 — all above the 70-point thresholds.
- Pest `--testsuite=HR --no-coverage`: **6 failed / 2 passed** (was 5
  failed / 3 passed in the 2026-09-06 baseline documented in
  `Modules/HR/docs/coverage.md`). **Not a regression from this fix** —
  confirmed via `git stash` A/B test (stashing only the 2 touched files
  reproduces the identical failure). `EmployeeCrudTest` and part of
  `AddEmployeeDocumentActionMutationTest` now fail with
  `InvalidArgumentException: Database connection [hr] not configured`.
  Root cause: `Modules/HR/app/Models/BaseModel.php` sets
  `protected $connection = 'hr';` (already committed in `laraxot/dev` before
  this session — see `git log --oneline -- app/Models/BaseModel.php`, commit
  `2f978c1` and the emergency-repair commit before it — unrelated to story
  2.8), but `config/database.php` has no `hr` connection configured. This is
  a pre-existing infra gap, out of scope for constant typing; flagging for
  whoever owns the HR `BaseModel`/DB-connection work.

## Pre-existing unrelated WIP found, deliberately not touched

At claim time `Modules/HR` already had uncommitted changes not from this
session: `AbsenceRequestResource/Pages/ViewAbsenceRequest.php` and
`EmployeeResource/Pages/ViewEmployee.php` (a `ViewRecord` →
`XotBaseViewRecord` / `getInfolistSchema()` canonical-structure migration,
same pattern as the Billing 4.1 story) plus an untracked
`phpmd.ruleset.xml`. These were left exactly as found and **excluded** from
this story's commit (`git add` targeted the 2 constant files + coverage.md,
not `git add -A`) to avoid misattributing someone else's in-flight,
unreviewed work under a `[story: 2.8]` commit message. Whoever owns that WIP
should commit it separately.

## Git

Commit `8e5c91e` on `Modules/HR` (`refactor(hr): type constants [story:
2.8]`), fast-forward push to `laraxot dev` (single remote, `laraxot`, no
merge needed — local HEAD was already built on top of the fetched tip).

Full detail: `Modules/HR/docs/coverage.md` (new section, 2026-09-07).
Story log: `docs/stories/2.8.phpstan-constant-type-coverage.story.md`.
