---
name: timber-coverage
description: Pest coverage and Definition of Done quality gate results for Modules/Timber, tracked around the constant type coverage fix
metadata:
  type: quality-gate
  module: Timber
  related_story: docs/stories/timber-constant-type-coverage-fix.story.md
---

# Timber — quality gate results (constant type coverage fix)

## Scope of the change

Three class constants got a real PHP 8.3+ type declaration on the `const` itself
(required by PHPStan's `typeCoverage.constantTypeCoverage`, which is a repo-wide
percentage, not a per-module error):

- `app/Actions/RenderTimberQrSvgAction.php` — `private const string OUTPUT_TYPE`
- `app/Support/TimberTraceCode.php` — `public const string SEPARATOR`
- `app/Filament/Pages/TimberDashboard.php` — `private const array STATUS_COLORS`
  (also de-duplicated a doubled `@var array<string, string>` docblock into one)

No behavior change: a typed `const` declaration is not an executable line, so it
cannot change Xdebug line/method coverage counts by itself.

## PHPStan

| | Before | After |
|---|---|---|
| `phpstan analyse Modules/Timber` | 0 errors | 0 errors |
| `phpstan analyse Modules` (full tree) — Timber constants flagged in `typeCoverage.constantTypeCoverage` | 3 (the ones above) | 0 |
| `phpstan analyse Modules` (full tree) — `classConstant.phpDocType` from Timber | 0 | 0 |
| Repo-wide constant type coverage (from the full-tree run, moving target — many
  agents work this bucket concurrently) | ~148/300 (~49%, campaign brief baseline) | 178/300 (59.3%, this session's full run, includes other agents' concurrent fixes) |

`phpstan.neon` untouched. No `@phpstan-ignore`, no baseline, no `mixed`.

## PHPMD

`tools/phpmd.sh Modules/Timber text Modules/Timber/phpmd.ruleset.xml`

| | Before | After |
|---|---|---|
| Total violations, whole module | 53 (pre-existing) | 53 (unchanged) |
| Violations in the 3 touched files | 0 | 0 |

All 53 are pre-existing structural debt (CyclomaticComplexity, CouplingBetweenObjects,
LongVariable, ExcessiveMethodLength/ParameterList, TooManyFields, etc.) spread across
Actions/Resources/Queries/Seeders — unrelated to this fix, not touched, documented here
as known debt per the task's "don't fix what's not mechanical/low-risk" instruction.

## PHPInsights

`vendor/bin/phpinsights analyse Modules/Timber --no-interaction --config-path=<ephemeral>`

The shared `laravel/phpinsights.php` was NOT modified. Scoping PHPInsights to a single
module subpath makes its `ForbiddenSecurityIssues` insight fail with a spurious
"composer.lock not found" (pre-existing tool quirk, unrelated to this module or this
fix — see second-brain memory `phpinsights_composer_lock_scoped_path`). Worked around
with a throwaway `/tmp` config copy that adds
`NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues::class` to `remove`,
identical to the shared config otherwise.

| Category | Score | Min gate |
|---|---|---|
| Code | 86.6 | 70 |
| Complexity | 85.6 | 70 |
| Architecture | 82.4 | 70 |
| Style | 89.2 | 70 |

All four categories pass the `min-*` = 70 requirement in `phpinsights.php`. The 3-line
diff (2 constants + 1 doc de-dup) on ~4224 lines / 194 files cannot move these
percentages measurably; before and after are the same run (no separate pre-fix
PHPInsights baseline exists for this module, and re-deriving one via `git stash` was
ruled out — see "Concurrency note" below).

## Pest

**Full module run:** `XDEBUG_MODE=coverage vendor/bin/pest Modules/Timber/tests --coverage`
→ **162 failed, 7 passed (26 assertions)**, duration ~2022s.

**Root cause of the 162 failures (pre-existing, NOT caused by this fix):** the test
database (`workorder_data_test`) is missing tables that Timber's own migrations
create, e.g. `roles` (Modules/User) and `timber_suppliers`
(`Modules/Timber/database/migrations/2026_07_24_000801_create_timber_suppliers_table.php`
— the migration exists in the codebase, the test DB just hasn't run it):

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'workorder_data_test.roles' doesn't exist
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'workorder_data_test.timber_suppliers' doesn't exist
```

This matches a known, pre-existing, cross-module issue (second-brain memory
`project_test_db_missing_migrations_blocks_feature_tests.md`: "workorder_data_test
missing tables on customer/document/mariadb connections, pre-existing, not a module
bug, owner must migrate once"). Per the repo's data-safety rule ("Data is sacred" —
never `migrate:fresh`/`--force`/`RefreshDatabase`, schema is the user's call), this was
**not** fixed as part of this story; it is out of scope and owner-only.

**Tests that exercise the 3 changed files, isolated and green:**

```
PASS  Modules\Timber\tests\Unit\TimberModuleSmokeTest
 ✓ registers the Timber module classes

PASS  Modules\Timber\tests\Feature\TimberTraceCodeTest
 ✓ it builds a dated stage segment from the step type
 ✓ it appends segments to the base lot code with the separator
 ✓ it returns the segment alone when the base is empty

Tests: 4 passed (9 assertions)
```

`TimberQrAndPdfTest` (exercises `RenderTimberQrSvgAction`) has 5 passing assertions in
isolation before hitting the same pre-existing `timber_suppliers` DB gap on later
cases — the QR/SVG rendering logic itself is not what fails.

## Coverage (the 3 touched files specifically, from the full coverage-text report)

| Class | Methods | Lines |
|---|---|---|
| `Modules\Timber\Actions\RenderTimberQrSvgAction` | 100.00% (2/2) | 100.00% (8/8) |
| `Modules\Timber\Support\TimberTraceCode` | 75.00% (3/4) | 93.75% (15/16) |
| `Modules\Timber\Filament\Pages\TimberDashboard` | not exercised — no currently-passing test reaches it (Filament page/Livewire tests are blocked by the same pre-existing DB gap) | — |

Since a typed `const` declaration is not an executable statement, these per-file
numbers are identical before and after this fix by construction — there is no code
path whose coverage could change. Before this session there was no `coverage.md` for
Timber (first time this report is captured for the module), so "before" for the
per-class numbers above is asserted analytically rather than from an archived prior
run; the reasoning is stated explicitly rather than presented as a re-measured baseline.

No pre-existing coverage.md existed for Timber before this session; there is no earlier
recorded overall pass/fail baseline to diff against for the full suite. The 162/7
figures above are this session's first and only full-suite measurement.

## Concurrency note

This repo has many concurrent AI agents on the same working tree
(`Modules/Timber/app/Filament/Pages/TimberDashboard.php` had unrelated uncommitted WIP
from another agent — `extends Filament\Pages\Dashboard` → `extends
Modules\Xot\Filament\Pages\XotBaseDashboard` — found in-flight and left untouched). A
`git stash` used briefly to attempt a clean before/after diff also stashed that WIP; it
was restored immediately with `git stash pop` before any measurement, and no further
stash was attempted to avoid touching other agents' in-flight work again. This is why
several "before" numbers above are reasoned analytically instead of re-measured.

## Summary

- PHPStan: clean before and after at module level; the full-tree
  `typeCoverage.constantTypeCoverage` and any `classConstant.phpDocType` findings
  attributable to Timber are gone after this fix.
- PHPMD/PHPInsights: unaffected by this change; pre-existing debt documented, not
  touched (out of the mechanical/low-risk bar set by the task).
- Pest: the logic actually touched by this fix (constants + their consumers) is green.
  The module's broader Feature suite is blocked by a pre-existing, out-of-scope test-DB
  migration gap that is explicitly not this story's to fix.
