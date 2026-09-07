---
title: "PHPStan typeCoverage.constantTypeCoverage — Catalog + Platform reconfirmed closed (regression recurrence)"
type: coordination
tags: [catalog, platform, phpstan, type-coverage, story-2.8, story-5.1, regression]
created: 2026-09-07T22:40:00Z
status: done
agent: claude (sonnet 5) — this session
related:
  - ../stories/2.8.phpstan-constant-type-coverage.story.md
  - ../stories/5.1.phpstan-constant-type-coverage-remaining.story.md
  - ./catalog-typecoverage-fix.md
  - ./platform-typecoverage-fix.md
  - ./typecoverage-constant-wrong-var-list-string-regression.md
  - ./2026-09-07-customer-typecoverage-fix.md
  - ./2026-09-07-claim-typecoverage-final-push-swarm.md
  - ./INDEX.md
---

# Catalog + Platform: constantTypeCoverage reconfirmed closed (third recurrence of the @var regression)

## Assignment

Continuation batch from `2026-09-07-claim-typecoverage-final-push-swarm.md`: type
the last untyped class constants in `Modules/Catalog` (12) and `Modules/Platform`
(5), per stories 2.8 and 5.1.

## What was actually found

Both modules had **already been fixed and closed** earlier today:

- Catalog: `docs/chat/catalog-typecoverage-fix.md`, commit `6d8d95a`
  (`refactor(catalog): type constants [story: 2.8]`), native
  `protected const string ACCOUNT_FEATURE_ADMIN/OPERATOR` on all 6 Resources.
- Platform: `docs/chat/platform-typecoverage-fix.md`, commit `b51dcb6`
  (`refactor(platform): type constants [story: 2.8]`), native
  `public const string SETTINGS_KEY`, `private const array
  SUBSYSTEM_SEGMENTS`/`PROTECTED_ITEM_CLASSES`, `private const string REGION`
  (x2 seeders).

At the start of this session, the full-tree scan (`phpstan analyse` with no
path argument — the only way this project-wide rule surfaces real errors, see
the two docs above) still flagged all 12 + 5 constants. On inspection, the
**working tree had regressed**, uncommitted, back to the exact wrong pattern
already documented in `typecoverage-constant-wrong-var-list-string-regression.md`
and in `2026-09-07-customer-typecoverage-fix.md`: the native type on the
`const` declaration was stripped and replaced with an inert `/** @var ... */`
docblock (which `TomasVotruba\TypeCoverage`'s `ConstantTypeDeclarationCollector`
does not read — it only looks at the native AST type node). Example
(`Platform/app/Company/CompanyProfileProvider.php`):

```php
// HEAD (already correct, already pushed)
public const string SETTINGS_KEY = 'company.profile';

// working tree at session start (uncommitted regression)
/** @var string */
public const SETTINGS_KEY = 'company.profile';
```

Same shape on `NavigationClassifier::SUBSYSTEM_SEGMENTS`,
`NavigationGate::PROTECTED_ITEM_CLASSES`, both demo seeders' `REGION`, and all
12 Catalog `ACCOUNT_FEATURE_*` constants.

This is now the **third+ documented recurrence** of this exact regression
pattern in this campaign (previously hit Bom/WorkOrder per the original
alert, and Customer per `2026-09-07-customer-typecoverage-fix.md`). Whatever
keeps reapplying story 5.1's "part 1" `@var`-only methodology across already-
fixed files is still active, or was re-run after the fixes landed — worth the
owner tracing which process this is, since it silently undoes committed work
without ever showing up as a git diff until someone runs the full-tree scan.

## Fix applied

Removed the stray `@var`-only docblocks, restored/applied the native PHP
8.3+ typed constant on all 17 declarations (6 Catalog files x2 constants,
5 Platform files x1 constant each). Pure type promotion, values unchanged.

## Result

- Catalog: edited all 6 files: while doing so, a concurrent agent
  independently committed the identical fix (`3ff6763`, already on
  `laraxot/dev`). Net diff after edit: zero — nothing to commit, module
  already correct and pushed.
- Platform: edited all 5 files; content converged exactly back to the
  already-good, already-pushed HEAD (`b51dcb6`). Net diff: zero — nothing to
  commit.
- Root monorepo: `git status`/`git diff` on all 11 paths: clean. A separate
  concurrent root commit (`3bd56c6a1`, "refactor: typed constants (100
  consts)...") had already synced this content into the root tree.
- Full-tree PHPStan (`clear-result-cache` then `analyse` with no path arg,
  `--error-format=raw`): **0 occurrences of `typeCoverage.constantTypeCoverage`
  anywhere in the project** (exit code 0). The 2.8/5.1 campaign target
  (49% → 99%+) appears met project-wide as of this scan.
- `php -l`: clean on all 11 files.
- Pint (`--dirty --test`, whole-repo scope per known limitation): no findings
  on Catalog/Platform files (3 unrelated failures in Production/Compliance,
  other agents' in-flight work, not touched).
- PHPMD (`Modules/Catalog/phpmd.ruleset.xml`, `Modules/Platform/phpmd.ruleset.xml`):
  only pre-existing violations unrelated to the touched lines/files
  (CouplingBetweenObjects, UnusedFormalParameter, ExcessiveClassComplexity on
  files/methods never touched by this fix) — same "structural debt bucket"
  class as documented elsewhere in this campaign, out of scope here.
- Pest (`Modules/Catalog/tests`, `Modules/Platform/tests`, `--no-coverage`):
  Catalog 5 passed/6 failed, Platform 28 passed/17 failed — all failures are
  `QueryException: Base table or view not found` (`roles`,
  `metel_catalogs`), the pre-existing shared test-DB migration gap (see
  second-brain `project_test_db_missing_migrations_blocks_feature_tests`),
  reproduced identically before and after this fix, not caused by it.
- phpinsights: not installed in this repo (removed, incompatible with Pest 5).

## Git

No commit needed in either module or at root — all three trees (Catalog,
Platform, root monorepo) already contain the correct fix via concurrent
agents' commits by the time this session's edits landed. Verified via
`git status`/`git diff` (all empty) and `git log` (module HEADs equal
`laraxot/dev`).

## Status

Catalog: **DONE** (reconfirmed, third time). Platform: **DONE** (reconfirmed,
regression neutralized). Project-wide `constantTypeCoverage`: **0 errors**,
stories 2.8 and 5.1 acceptance criteria met as of this scan — see closing log
entries added to both story files.
