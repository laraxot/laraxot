---
title: "PHPStan constantTypeCoverage — Quotation (12) + Signage (7) regression re-fix"
type: coordination
tags: [phpstan, typecoverage, quotation, signage, regression, story-2.8, story-5.1]
created: 2026-09-07T22:45:00+02:00
status: done
agent: claude sonnet 5 (this session)
scope: Modules/Quotation, Modules/Signage
blocking: false
---

# Quotation + Signage — constantTypeCoverage: regression found and re-fixed

Continuation of `docs/chat/2026-09-07-claim-typecoverage-final-push-swarm.md`
(batch: Quotation 12 constants, Signage 7 constants), itself continuing
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` and
`docs/stories/5.1.phpstan-constant-type-coverage-remaining.story.md`.

## Key finding: this metric is undetectable when scoped

`./vendor/bin/phpstan analyse Modules/<Modulo>` **never** reports
`typeCoverage.constantTypeCoverage`, regardless of how many untyped constants
exist. `TomasVotruba\TypeCoverage\Rules\ConstantTypeCoverageRule` checks
`ScopeConfigurationResolver::areFullPathsAnalysed()`, which compares the CLI
path against `phpstan.neon`'s configured `paths` and silently returns no
errors when they differ (to avoid false positives on partial scans). Only
`./vendor/bin/phpstan analyse Modules` (the exact configured path) surfaces
this rule's errors. Any prior/future session scoping to a single module for
this specific check will always see "No errors" — that is not evidence of
compliance.

## Key finding: both modules had already been fixed today and regressed

Both modules' target constants had already been typed earlier the same day:

- Quotation: commit `352d16b` (15:38:42) — `refactor(quotation): type
  constants [story: 2.8]`, 12 constants/10 files.
- Signage: commit `28d7bd4` (15:16:09) — `fix(signage): type constants for
  typeCoverage.constantTypeCoverage + fix wrong @var shape regression`, 7 of
  the constants fixed here were part of that same commit (with others).

A full-tree scan run at the start of this session (before any edit) still
showed all 19 as untyped. Root cause confirmed by diffing the live working
tree against those commits: the Epic4 "filament-canonical-structure" swarm's
uncommitted WIP on both modules (Quotation: `docs/stories/4.13.sitesurvey-canonical-structure.story.md`;
Signage: `docs/stories/4.14.9.signage-canonical.story.md`) regenerates the
touched Resources/Widgets/Actions/Seeders from a pre-fix base, silently
dropping the native constant type while keeping legitimate unrelated
structural changes (e.g. `QuotationsMarginByProjectChart` renamed from
`ChartWidget` to `XotBaseChartWidget` survived; the `float`/`array`/`string`
on the adjacent constant did not). Same pattern already logged today for
Customer/HR/Signage in this same INDEX.

## What was done

- Locked all 14 target files (`bashscripts/lock/lock.sh`) before editing;
  confirmed via mtime (last touch in both modules ~18:41-18:42, >3h stale at
  the time of this session) that the Epic4 WIP was not actively mid-edit.
- Re-applied the exact same native PHP 8.3+ types as the original commits
  (verified verbatim against `git show 352d16b` / `git show 28d7bd4`):
  Quotation 12/12 constants across 10 files; Signage 6/7 (the 7th,
  `SignageCosting::CONTEXT`, was found already re-fixed by a concurrent agent
  mid-session — a `File has been modified since read` edit-tool error
  surfaced the race; re-read confirmed it was already correct, left
  untouched).
- `php -l` on every touched file: clean.
- Full-tree `phpstan analyse Modules --no-progress --memory-limit=-1` (clean
  result-cache): before — 302 constants project-wide, 209 typed (69.2%), 95
  `typeCoverage.constantTypeCoverage` lines (12 on Quotation files, 7 on
  Signage files, matching the claim's breakdown exactly); after — `[OK] No
  errors`, **0 occurrences anywhere in `Modules/`** (project-wide 99%
  threshold reached, combined with other parallel batches on the same claim).
  Also 0 non-typeCoverage errors in either module.
- PHPMD on touched files only: no new violations from these one-line const
  changes. Pre-existing structural findings (CouplingBetweenObjects,
  CyclomaticComplexity, ShortVariable, UnusedFormalParameter) documented in
  each module's `docs/coverage.md`, out of scope (unrelated method bodies,
  pre-date this session, overlapping with live Epic4 refactor).
  `phpinsights` not run: confirmed not installed in this repo (removed,
  incompatible with Pest 5).
- Pest: Quotation 31 passed/76 failed, Signage 7 passed/47 failed — all
  failures are pre-existing `QueryException: Base table or view not found` on
  `workorder_data_test` (missing `customers`, `sign_price_list_items`, etc.),
  documented in second-brain
  `project_test_db_missing_migrations_blocks_feature_tests`. Not caused by
  this work (a native type on an already-existing constant cannot change SQL
  behavior). No `migrate:fresh`/`--force` run (data is sacred).

## Docs updated

- `laravel/Modules/Quotation/docs/coverage.md` — new section, regression
  re-fix.
- `laravel/Modules/Signage/docs/coverage.md` — new section, regression
  re-fix.
- `laravel/Modules/Quotation/docs/stories/5.2.quotation-constant-type-coverage-regression-fix.story.md`
  — new, status done.
- `laravel/Modules/Signage/docs/stories/3.4.signage-constant-type-coverage-regression-fix.story.md`
  — new, status done.

## Commits

- Quotation (module repo `module_quotation_fila5`): see module `git log`.
- Signage (module repo `module_signage_fila5`): see module `git log`.
- Root monorepo (`base_workorder_fila5`): same exact file paths, committed
  separately (root tracks these files as regular blobs, not a submodule
  gitlink — `git add <exact paths>`, never `-A`).

## For whoever owns story 4.13 / 4.14.9 next

Every time the Epic4 canonical-structure extraction touches these files
again, it risks re-dropping these same constant types (it has now done so at
least twice for Signage and once for Quotation on the same day). Worth
adding "preserve native constant types" to that swarm's own checklist before
it regenerates a Resource/Widget/Action file, or re-running a full-tree
`typeCoverage.constantTypeCoverage` check as its own closing gate.
