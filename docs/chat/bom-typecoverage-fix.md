---
title: "PHPStan typeCoverage.constantTypeCoverage — Bom done (4 constants typed)"
type: coordination
tags: [phpstan, bom, typecoverage, story-2.8, done]
created: 2026-09-07T00:00:00Z
status: done
agent: claude (sonnet 5)
blocking: false
---

# Bom — story 2.8 (constantTypeCoverage) closed

Fresh full-tree scan at claim time (`phpstan analyse Modules`, clean cache):
300 total constants project-wide, 227 typed, 75.6% (up from the story's 49%
baseline — other agents made progress in parallel). Bom's 4 occurrences,
exactly matching the assignment:

- `Modules/Bom/app/Filament/Resources/BomResource.php:36` `ACCOUNT_FEATURE_ADMIN`
- `Modules/Bom/app/Filament/Resources/BomResource.php:39` `ACCOUNT_FEATURE_OPERATOR`
- `Modules/Bom/app/Filament/Resources/BomResource.php:42` `ACCOUNT_FEATURE_OPERATOR_DEFAULT`
- `Modules/Bom/app/Filament/Resources/BomResource/Pages/ViewBom.php:23` `MAX_TREE_DEPTH`

## Fix applied

Native PHP 8.3+ const type declarations, matching the already-fixed sibling
pattern in `Production/ProductionOrderResource.php` and
`Customer/ResellerResource.php` (no `@var` PHPDoc, just the real type):

```php
protected const string ACCOUNT_FEATURE_ADMIN = 'admin.bom';
protected const ?string ACCOUNT_FEATURE_OPERATOR = null;
protected const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;
```

```php
private const int MAX_TREE_DEPTH = 8;
```

## Found and corrected: stale WIP wrong-pattern attempt

`ViewBom.php` already had an **uncommitted** diff from a prior/other session
that had added `/** @var int */` above `MAX_TREE_DEPTH` without a native
type — exactly the incorrect pattern flagged in
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` (a
PHPDoc alone does not satisfy `TomasVotruba\TypeCoverage`'s
`ConstantTypeDeclarationCollector`, only a real type declaration on the
`const` does). Lock was FREE at claim time, so this was picked up and fixed
correctly (native `const int`) rather than left or duplicated.

## Important mechanic for whoever works this bucket next

`typeCoverage.constantTypeCoverage` (tomasvotruba/type-coverage) only fires
when the **full configured `paths`** (`./Modules/` per `phpstan.neon`) are
analysed in one run — see
`TomasVotruba\TypeCoverage\Configuration\ScopeConfigurationResolver::areFullPathsAnalysed`.
Scanning a single module (`phpstan analyse Modules/Bom`) will **always**
report 0 occurrences of this rule regardless of how many untyped constants
exist in that module. You must run the full `phpstan analyse Modules` (no
subpath) to see current occurrences and confirm which files/lines are still
flagged. That run takes several minutes on this repo — plan for it (ran via
background process here, ~5-6 min).

## Quality gate

- PHPStan `analyse Modules/Bom` (clean cache): 0 errors before and after.
- PHPMD (`./tools/phpmd.sh Modules/Bom text Modules/Bom/phpmd.ruleset.xml`):
  8 pre-existing violations, unrelated to touched lines (CouplingBetweenObjects
  in `BomResource.php`, ElseExpression in `ViewBom.php`, UnusedFormalParameter
  x6 in `BomItemPolicy.php`/`BomPolicy.php`). Not in scope, not touched.
- phpinsights: not available in this repo this session (removed, incompatible
  with Pest 5 per repo hook) — skipped, not chased.
- Pest (`./vendor/bin/pest Modules/Bom/tests --no-coverage`): 2 passed, 4
  assertions, no regression.

## Git

Commit `ef168ad` in `laravel/Modules/Bom` (independent per-module `.git`),
message `refactor(bom): type constants [story: 2.8]`. `git fetch laraxot dev`
+ `git merge laraxot/dev --allow-unrelated-histories -s resolve` → already up
to date, no conflicts. Pushed clean to `laraxot dev`
(`3c8b57f..ef168ad`). Single remote (`laraxot`), no others to push to.

Full detail: `Modules/Bom/docs/coverage.md`,
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` (log entry added).
