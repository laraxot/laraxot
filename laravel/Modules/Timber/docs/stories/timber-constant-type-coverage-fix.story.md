---
name: timber-constant-type-coverage-fix
description: Add real PHP 8.3 type declarations to Timber class constants to raise the repo-wide typeCoverage.constantTypeCoverage percentage, plus full Definition of Done verification (PHPStan, PHPMD, PHPInsights, Pest coverage)
metadata:
  type: BMAD
  phase: Development
  epic: typeCoverage
  story: timber-constant-type-coverage-fix
  status: done
  owned_module: Timber
---

# Story: Timber constant type coverage fix + full Definition of Done

**As a** developer working the "PHPStan Modules a zero errori" campaign
**I want** every Timber class constant to carry a real PHP 8.3+ type declaration
**So that** the repo-wide `typeCoverage.constantTypeCoverage` metric (visible only on a
full `phpstan analyse Modules` run, not per-module) moves closer to the 99% gate,
without regressing PHPMD, PHPInsights, or Pest for the module.

## Context

`typeCoverage.constantTypeCoverage` is a REPO-WIDE percentage (typed constants / total
constants across `Modules/`), not a per-module error. `phpstan analyse Modules/Timber`
alone never shows it. Three Timber constants were untyped:

- `Modules/Timber/app/Actions/RenderTimberQrSvgAction.php:13` — `private const OUTPUT_TYPE = 'svg';`
- `Modules/Timber/app/Support/TimberTraceCode.php:17` — `public const SEPARATOR = '·';`
- `Modules/Timber/app/Filament/Pages/TimberDashboard.php:36` — `private const STATUS_COLORS = [...];`

Known regression pattern to avoid (see `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`):
adding `@var list<string>` PHPDoc without checking the real value shape breaks
`classConstant.phpDocType`. The metric is satisfied by a real `const <type>` declaration,
not by PHPDoc alone.

## Acceptance Criteria

1. All three constants above carry a real PHP 8.3+ type on the `const` declaration
   itself (`string`, `array`), matching the constant's real value shape.
2. No `@var` PHPDoc added that mismatches the real shape (verified: one is scalar
   string x2, one is `array<string,string>` — matches the existing correct docblock).
3. `phpstan analyse Modules/Timber` — 0 errors (before and after).
4. Full-tree `phpstan analyse Modules` — 0 `classConstant.phpDocType` errors
   attributable to Timber, and Timber no longer appears in the
   `typeCoverage.constantTypeCoverage` file list.
5. PHPMD — no new violations introduced by this change (module has 53 pre-existing
   structural violations, none in the 3 touched files, before or after).
6. PHPInsights — scores stable (module-wide, 3-line change on ~4200 lines/194 files).
7. Pest — all Timber tests green, coverage documented before/after in
   `Modules/Timber/docs/coverage.md`.
8. `phpstan.neon` untouched. No `@phpstan-ignore`, no baseline, no new `mixed`.
   `declare(strict_types=1);` present on all 3 touched files (already was).

## Tasks

- [x] Locate exact constant line numbers (`grep -n "const "` in the 3 files)
- [x] Type `RenderTimberQrSvgAction::OUTPUT_TYPE` as `private const string`, drop the
      now-redundant `/** @var string */`
- [x] Type `TimberTraceCode::SEPARATOR` as `public const string`, drop the redundant
      `/** @var string */`
- [x] Type `TimberDashboard::STATUS_COLORS` as `private const array`, de-duplicate the
      doubled `@var array<string, string>` docblock (was declared twice) into one
- [x] `php -l` all 3 files
- [x] `phpstan analyse Modules/Timber --no-progress --memory-limit=-1` → 0 errors
- [x] `phpstan analyse Modules --no-progress --memory-limit=-1` (full tree) → Timber
      absent from remaining `typeCoverage`/`classConstant` findings
- [x] `tools/phpmd.sh Modules/Timber text Modules/Timber/phpmd.ruleset.xml` → 53
      pre-existing violations, 0 in touched files, 0 new
- [x] `vendor/bin/phpinsights analyse Modules/Timber --no-interaction` (ephemeral
      config removing the broken `ForbiddenSecurityIssues` composer.lock check, see
      Dev Notes) → Code 86.6, Complexity 85.6, Architecture 82.4, Style 89.2 (all > 70
      min-quality gate)
- [x] `XDEBUG_MODE=coverage vendor/bin/pest Modules/Timber/tests --coverage` →
      documented in `Modules/Timber/docs/coverage.md`
- [x] git add + commit + fetch/merge + push on `laraxot` remote (Timber's only remote)

## Dev Notes

**Root cause / fix pattern:** PHP 8.3 allows typed class constants. PHPStan's
`typeCoverage.constantTypeCoverage` rule counts a constant as "typed" only when the
`const` declaration itself carries a type — a `@var` PHPDoc alone does not count, and a
PHPDoc with the wrong shape (e.g. `list<string>` on an associative array) creates a NEW
`classConstant.phpDocType` error, which is worse than not fixing it. Always inspect the
real value before choosing the type/shape.

**PHPInsights tooling note:** `tools/phpinsights.sh analyse Modules/Timber
--no-interaction` fails with "composer.lock not found" from the `ForbiddenSecurityIssues`
insight when scoped to a single module subpath (pre-existing tool quirk, unrelated to
this fix — see second-brain memory `phpinsights_composer_lock_scoped_path`). Worked
around with an ephemeral `--config-path=/tmp/...` copy of `laravel/phpinsights.php` that
adds `NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues::class` to `remove`.
The shared `laravel/phpinsights.php` was NOT modified.

**Concurrency note:** this repo has many concurrent AI agents on the same working tree.
While investigating, found *pre-existing uncommitted WIP* in `TimberDashboard.php`
(another agent's in-flight change from `extends Filament\Pages\Dashboard` to
`extends Modules\Xot\Filament\Pages\XotBaseDashboard`, plus an import change). A `git
stash` of "my" 3 files during before/after benchmarking briefly reverted that WIP too;
it was immediately restored with `git stash pop` before any measurement or commit. Not
touched further — out of scope for this story.

**Full-tree metric at time of this story:** `phpstan analyse Modules` reported
178/300 (59.3%) constants typed after this fix (up from ~148/300 cited in the campaign
brief). The delta is larger than 3 because many other agents are working the same
bucket in parallel across other modules — this story's contribution is exactly the 3
Timber constants listed above.

## Testing Strategy

1. Static: `phpstan analyse Modules/Timber` (module) and `phpstan analyse Modules`
   (full tree, to see the typeCoverage bucket) both clean of Timber-attributable errors.
2. Runtime: full Timber Pest suite green, coverage numbers recorded before/after in
   `Modules/Timber/docs/coverage.md`.
3. Style/structure: PHPMD and PHPInsights re-run on the whole module to confirm no
   regression from the 3-line diff.

## Dependency Maps

- Independent of other typeCoverage stories in other modules (constants are
  module-local); contributes additively to the shared repo-wide percentage.

## Owned File/Module Scope

- `laravel/Modules/Timber/app/Actions/RenderTimberQrSvgAction.php`
- `laravel/Modules/Timber/app/Support/TimberTraceCode.php`
- `laravel/Modules/Timber/app/Filament/Pages/TimberDashboard.php`
- `laravel/Modules/Timber/docs/coverage.md` (new)
- `laravel/Modules/Timber/docs/stories/timber-constant-type-coverage-fix.story.md` (this file)

## Learnings from Previous Stories

- Prior Timber PHPStan stories in this same folder are mostly empty placeholder files
  (0 bytes) created by earlier swarm runs that never completed — left untouched per
  the "don't delete/rename existing stories" policy; this is a new, separate story
  scoped specifically to the constant-typing sub-task.

## Dev Agent Record

Completed by Claude (Sonnet 5), 2026-09-07. See `Modules/Timber/docs/coverage.md` and
`docs/chat/timber-typecoverage-fix.md` (root repo) for full before/after numbers,
commit hash, and remote push confirmation.

---

**Status:** done
**Owner:** dev agent (Claude Sonnet 5)
**Created:** 2026-09-07
**Last Updated:** 2026-09-07
