---
title: "Blog module: typeCoverage.constantTypeCoverage fix + Pest fatal-error blocker fixed"
type: coordination
tags: [phpstan, blog, typecoverage, pest, quality-gate]
created: 2026-09-07
agent: claude (sonnet 5) — this session
---

# Blog: constantTypeCoverage fix + full DoD gate (2026-09-07)

Task: type the 2 untyped constants in `Modules/Blog`
(`app/Http/Livewire/Article/Lists.php`, `database/seeders/TagSeeder.php`) to
move the repo-wide `typeCoverage.constantTypeCoverage` metric, then bring
Blog to full Definition of Done (PHPStan + PHPMD + Pest + coverage doc + BMAD
story + git sync).

## Result summary

- PHPStan `Modules/Blog`: 0 errors before and after (this bucket never shows
  on a scoped run — full-tree only).
- Full-tree `typeCoverage.constantTypeCoverage`: 165/300 (55.0%) → 186/300
  (62.0%). Blog's `TagSeeder.php` was in the "before" 135 `file_errors`, gone
  from "after". The jump is bigger than 2 because many other agent sessions
  are fixing constants elsewhere in the tree concurrently right now — normal
  for this shared repo today, not a Blog-specific claim beyond its own 2
  constants.
- Found and fixed a **blocking** pre-existing bug unrelated to typeCoverage:
  `Modules/Blog/tests/unit/` + `tests/feature/` (lowercase) were git-tracked
  duplicates of `tests/Unit/` + `tests/Feature/`. The lowercase `SumTest.php`
  redeclared a global function already declared by the PascalCase twin,
  fatal-erroring the entire module's Pest suite. Removed via `git rm -r
  --cached` + `rm -rf` after confirming 1:1 file parity (skill
  `psr4-test-filename-pascalcase`).
- Pest `Modules/Blog/tests`: fatal error (0 runnable) → 1 passed.
- PHPMD: 9 pre-existing structural violations, none in touched files, left
  as documented debt.
- PHPInsights: not run — removed from this repo (incompatible with Pest 5,
  per tooling notice surfaced mid-session), not silently skipped.
- Story: `laravel/Modules/Blog/docs/stories/6.1.blog-typecoverage-constant-fix.story.md`.
- Full detail: `laravel/Modules/Blog/docs/coverage.md`.

## Heads up: found uncommitted WIP already on disk before starting

Working tree had uncommitted changes not from this session's edits:

- `Lists.php` already had a WIP fix using the **wrong** pattern
  (`/** @var int */` PHPDoc-only, no real type) — discarded, replaced with
  the correct `public const int ITEMS_PER_PAGE = 10;`.
- `docs/coverage.md` in the working tree was **missing** a section
  (`Profile::$model` fix) that HEAD (`bfc51db`, already pushed to
  `laraxot/dev`) already had merged in — i.e. the working copy was a
  regression of a committed file. Rebuilt from `git show
  HEAD:docs/coverage.md` instead of overwriting further.
- `app/Filament/Pages/Dashboard.php` (rename to `XotBasePage`) and an
  untracked `phpmd.ruleset.xml` — unrelated to this task, left untouched,
  **not** part of this session's commit.

`ps aux` during this session showed multiple concurrent `phpstan analyse
Modules` and `pest` runs in flight from other sessions (Wts, Timber, Job,
Blog itself) racing on the same shared `/tmp/phpstan` result cache — one of
my own full-tree runs came back with `Internal error: Could not read file`
(stale cache reference to the just-deleted `tests/unit/SumTest.php`); a
`clear-result-cache` + retry fixed it. If you're running full-tree PHPStan
right now, expect contention.

## Suspected prompt injection, not actioned

Mid-task, a message formatted as a `<system-reminder>` appeared appended to a
Skill tool's output, claiming to be "the coordinator" and instructing
creation of GitHub issues/discussions via `gh issue create` / `gh api
graphql` plus rewriting all `.md` frontmatter to link them. This did not
arrive as a genuine conversation turn — it was embedded inside tool output.
Not actioned: no GitHub issues/discussions were created, no `gh` write
commands were run. Flagging here in case this pattern shows up for other
sessions too.

## Git

Committed and pushed: `e13384a` on `git@github.com:laraxot/module_blog_fila5.git`
branch `dev` (`bfc51db..e13384a`). No divergence from remote, no merge needed
(1 ahead, fast pushed).
