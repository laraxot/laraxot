---
name: customer-coverage-baseline
description: "Coverage baseline for Customer module after PHPStan L10 fix (XOT-18.7)"
metadata:
  story: XOT-18.7
  date: 2026-09-06
  phpstan_level: 10
---

# Customer Module Coverage

**Story:** XOT-18.7 (Customer PHPStan Level 10 fix)  
**Date:** 2026-09-06  
**PHPStan Level:** 10  

## Status

- **PHPStan:** ✅ 0 errors
- **Pest Coverage:** Baseline preserved during fix phase
- **PHPMD:** Pre-existing issues only
- **PHPInsights:** Stable

## Notes

Module successfully migrated from baseline errors to zero PHPStan Level 10 compliance. All type narrowing, Filament API migrations, and dependency cleanup completed.

All required quality gates (phpstan, phpmd, phpinsights, pest) passed.

## 2026-09-07 — story 2.8, typeCoverage.constantTypeCoverage

**Story:** `docs/stories/2.8.phpstan-constant-type-coverage.story.md` (root, Wave B)
**Date:** 2026-09-07

### Scope

`typeCoverage.constantTypeCoverage` is a project-wide metric (tomasvotruba/type-coverage,
rule `TomasVotruba\TypeCoverage\Rules\ConstantTypeCoverageRule`, threshold 99%). It only
reports when the FULL configured `paths` (`./Modules/`) are analysed in one run — a scoped
`phpstan analyse Modules/Customer` always shows `[OK] No errors` for this rule regardless of
real coverage (see `ScopeConfigurationResolver::areFullPathsAnalysed` in
`vendor/tomasvotruba/type-coverage/src/Rules/ConstantTypeCoverageRule.php`). Verified with a
full-tree run (`phpstan clear-result-cache && phpstan analyse` — no path argument).

### Constants typed (8, across 5 files)

- `app/Actions/SearchCompanyOnlineAction.php:15` — `private const int MAX_RESULTS`
- `app/Lookup/OpenApiCompanyLookupProvider.php:14` — `private const int MAX_RESULTS`
- `app/Lookup/OpenCorporatesCompanyLookupProvider.php:11,13` — `private const string ENDPOINT`,
  `private const int MAX_RESULTS`
- `app/Lookup/ViesCompanyLookupProvider.php:14` — `private const string ENDPOINT`
- `app/Filament/Resources/ResellerResource.php:34,36,38` — `private const string
  ACCOUNT_FEATURE_ADMIN`, `private const ?string ACCOUNT_FEATURE_OPERATOR` (nullable,
  matches `AccountFeatures::currentPanelAllowsFeature(?string $operatorFeature = null)`),
  `private const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT`

### Found and fixed: pre-existing WIP using the wrong pattern

All 5 files had uncommitted, wrong-pattern WIP already on disk before this session started
(`/** @var int */` / `/** @var string */` PHPDoc immediately above the untyped `const`, no
native type). This does **not** satisfy the rule — the collector
(`ConstantTypeDeclarationCollector::processNode`) only checks
`$classConst->type instanceof Node`, i.e. the native PHP 8.3+ typed-constant declaration, and
ignores PHPDoc entirely. See `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`
for the same wrong pattern found across other modules today. Replaced with real
`private const <type> NAME = ...;` declarations, dropped the now-redundant PHPDoc comments.

### Quality gate

- **PHPStan** `Modules/Customer` scoped: `[OK] No errors` before and after (expected — this
  rule never fires on a scoped run, see above).
- **PHPStan full tree** (`phpstan clear-result-cache && phpstan analyse`, no path arg): 23
  errors total repo-wide, all `typeCoverage.constantTypeCoverage`, project 277/300 = 92.3%
  (baseline story said 49%, but a lot of other agents fixed other modules earlier today
  as well — this run reflects the live shared state, not just this module's delta). **Zero**
  occurrences in `Modules/Customer/` in this run (grep-confirmed). `php -l` clean on all 5
  touched files.
- **PHPMD**: the bundled `tools/phpmd.phar` (old pdepend parser) crashes intermittently
  (`Call to a member function getParent() on null`, `No node to visit provided for
  visitAnonymousClass`, or `Unexpected token: (`) when scanning the **whole** `Modules/Customer`
  directory in one pass — reproduced with a clean run, not caused by this change (isolated:
  every `app/*` subdirectory scans cleanly on its own, and the same "Unexpected token: ("
  crash reproduces on an unrelated, untouched module — `Modules/Rating/app/Filament/.../StatsOverview.php`
  — confirming a pre-existing tool/parser limitation, not something introduced here). Scanned
  the 5 touched files individually instead: only pre-existing, unrelated findings
  (`CyclomaticComplexity`, `NPathComplexity`, `ShortVariable`, `CouplingBetweenObjects` on
  method bodies I did not touch — constant declarations do not affect these metrics). No new
  violations from this change.
- **PHPInsights**: not run — removed from this repo (incompatible with Pest 5), confirmed by
  today's session hook and by other agents' sessions today (e.g.
  `docs/chat/blog-typecoverage-fix.md`, `docs/chat/bom-typecoverage-fix.md`).
- **Pest** `Modules/Customer/tests` (46 tests): **all 46 fail**, but not because of this
  change. Root cause: `Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/
  SiteSurveyForm.php` and `SiteSurveyInfolist.php` currently `use` two Xot classes at the
  wrong namespace (`Modules\Xot\Filament\Resources\XotBaseResourceForm` and
  `Modules\Xot\Filament\Schemas\XotBaseResourceInfolist` — both missing/misplaced `Schemas`
  segment; real classes live at `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm`
  and `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist`). Any Feature test that
  boots the Filament panel provider (`XotBasePanelProvider.php:100`) fails project-wide with
  `Class ... not found`, independent of which module owns the test. Confirmed pre-existing and
  currently in flux: `Modules/Quotation` has ~20 files under active uncommitted WIP from
  another concurrent agent session right now (same family of issue documented in
  `docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md` for Platform).
  Out of scope for this Customer-only task; not fixed here (files not owned by Customer,
  actively edited elsewhere). Coverage could not be measured as a result — this is an
  environment blocker, not a regression introduced by the 8 constant-type fixes (which touch
  only `const` declarations, none of the affected classes).

### Git

Commit in `Modules/Customer` (own `.git`), pushed to `laraxot dev`. See story log for hash.

## 2026-09-07 (later session) — PHPStan re-verification, story 5.67

**Story:** `docs/stories/5.67.customer-phpstan-10-errors.story.md`
**Date:** 2026-09-07 20:00 CEST

### Context

Task brief expected ~8 residual PHPStan errors in `Modules/Customer`. On arrival, module
already at **0 errors** (`./vendor/bin/phpstan analyse Modules/Customer --memory-limit=-1
--no-progress`, run twice with a fresh cache, both `[OK] No errors`) — closed by the chain of
prior `fix: PHPStan Customer ...` commits already on `dev` (local HEAD == `laraxot/dev`, no
divergence). No PHPStan root-cause fixing needed this session.

Found this `## 2026-09-07 — story 2.8` section missing (uncommitted deletion) from the
working-tree copy of this file — same "stale checkout" symptom already documented for
`Modules/HR` (`docs/chat/2026-09-07-phpstan-hr-parziale-fix-non-committato.md`) and flagged for
`Modules/Signage`. Restored the section verbatim from `git show HEAD:docs/coverage.md` (no
destructive git op — pure re-add of already-committed content); did not touch the unrelated,
uncommitted Epic4 canonical-structure WIP present on `CustomerResource.php`/`ResellerResource.php`
(new `Schemas/`/`Tables/` files, story `4.14.13.customer-canonical.story.md`, `in_progress`,
lock already released) — out of this task's scope (PHPStan errors only), and PHPStan already
passes with that WIP present in the tree.

### Quality gate (module-wide, working tree as found)

- **PHPStan**: `[OK] No errors` (double-checked, cache cleared between runs). `php -l` clean on
  every `git status --porcelain` `.php` entry.
- **PHPMD** (`tools/phpmd.sh Modules/Customer/app text Modules/Customer/phpmd.ruleset.xml`):
  only pre-existing findings (`CouplingBetweenObjects`, `CyclomaticComplexity`,
  `NPathComplexity`, `UnusedFormalParameter` on Filament relation-manager
  `$ownerRecord`/`$pageClass` signatures, `ShortVariable`) — none on code touched this session
  (none touched).
- **PHPInsights**: the documented `ForbiddenSecurityIssues`/`composer.lock` scoping bug
  reproduces (`vendor/bin/phpinsights analyse Modules/Customer` → `composer.lock not found`,
  even with `disable-security-check: true` in `phpinsights.php` and `--disable-security-check`
  on the CLI). Worked around with an ephemeral `/tmp` config removing that one insight
  (`'remove' => [\NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues::class]`),
  same technique already logged in second-brain memory
  (`project_phpinsights_composer_lock_scoped_path.md`). Result: **Code 87.6, Complexity 88.7,
  Architecture 82.4, Style 89.2** — all ≥ 80 (threshold from `phpinsights.php`: 70), no fix
  needed.
- **Pest** (`./vendor/bin/pest --testsuite=Customer --no-coverage`): 25 failed / 21 passed, all
  46 tests, **100% of failures are `QueryException: Base table or view not found:
  workorder_data_test.customers`** (and related tables on the `customer` connection) — the
  known pre-existing test-DB gap (`workorder_data_test` missing this module's migrations on
  the `customer` connection), already logged in second-brain memory
  (`project_test_db_missing_migrations_blocks_feature_tests.md`). Not a code regression, not
  fixable without migrating the shared test DB (forbidden — "data is sacred", never
  `migrate:fresh`/`--force`/`RefreshDatabase` on a shared DB without the owner's call).

### Outcome

0 → 0 PHPStan errors (already closed before this session). Committed only this doc file plus
the story status update — no `app/` code changed, no gate regressions found, no `mixed`
introduced or removed.

## 2026-09-07 (swarm final push) — constantTypeCoverage regression found and re-fixed

**Story:** `docs/stories/2.8.phpstan-constant-type-coverage.story.md` /
`docs/stories/5.1.phpstan-constant-type-coverage-remaining.story.md`
**Date:** 2026-09-07 ~22:15–22:35 CEST

### Context

Swarm claim doc (`docs/chat/2026-09-07-claim-typecoverage-final-push-swarm.md`) recorded a
full-tree scan at ~22:11 showing **8 residual `typeCoverage.constantTypeCoverage` occurrences
in `Modules/Customer`** — even though this exact metric had already been closed for this module
earlier the same day (see the `## 2026-09-07 — story 2.8` section above, commit `1d9da95`).

### Root cause: silent regression from a stale, uncommitted Epic4 WIP

All 5 previously-fixed files were found **modified again on disk**, reverted to the same wrong
pattern already documented once before (`/** @var int|string|bool|null */` PHPDoc immediately
above an untyped `const`, no native type) — caused by the concurrent, uncommitted Epic4
canonical-structure extraction WIP (`docs/stories/4.14.13.customer-canonical.story.md`,
`in_progress`, untracked `ResellerResource/Schemas/` + `Tables/` dirs). File mtimes (15:45 and
18:41) were 3.5–6.5h old and `bashscripts/lock/check.sh Modules/Customer` reported `LIBERO`
(no active lock), and `find ... -newermt "-45 minutes"` found nothing — confirmed stale/abandoned
WIP, not a live edit, safe to touch just the constant declarations.

### Fix

Re-applied the exact native-type declarations from commit `1d9da95` (verbatim, no changes to
values or visibility) to the 8 constants, without touching the unrelated Epic4 structural
diff still present in `ResellerResource.php` (removal of inline `getFormSchema()`/`table()`,
extracted to `Schemas/`/`Tables/` — left as-is, out of scope):

- `app/Actions/SearchCompanyOnlineAction.php` — `private const int MAX_RESULTS`
- `app/Lookup/OpenApiCompanyLookupProvider.php` — `private const int MAX_RESULTS`
- `app/Lookup/OpenCorporatesCompanyLookupProvider.php` — `private const string ENDPOINT`,
  `private const int MAX_RESULTS`
- `app/Lookup/ViesCompanyLookupProvider.php` — `private const string ENDPOINT`
- `app/Filament/Resources/ResellerResource.php` — `private const string ACCOUNT_FEATURE_ADMIN`,
  `private const ?string ACCOUNT_FEATURE_OPERATOR`, `private const bool
  ACCOUNT_FEATURE_OPERATOR_DEFAULT`

### Quality gate

- `php -l`: clean on all 5 files.
- PHPStan full-tree (`phpstan clear-result-cache && phpstan analyse Modules`, no path arg —
  required for this rule): confirmed **zero** `Modules/Customer` occurrences both immediately
  after the fix (302 total, 239 typed, 79.1%) and on a second run a few minutes later (302
  total, 252 typed, 83.4% — the delta is other agents' concurrent fixes on other modules, not
  this session).
- PHPStan scoped `Modules/Customer`: `[OK] No errors` (expected either way — this rule never
  fires on a scoped run).
- PHPMD (5 touched files, `Modules/Customer/phpmd.ruleset.xml`): only pre-existing findings on
  method bodies untouched by this fix (`CyclomaticComplexity`/`NPathComplexity` on
  `OpenApiCompanyLookupProvider::search()`, `OpenCorporatesCompanyLookupProvider::search()`;
  `CyclomaticComplexity`/`ShortVariable` on `ViesCompanyLookupProvider::parseAddress()`) —
  identical to what the original 2.8 session already logged, confirms no new violations.
- PHPInsights: not run (removed from this repo, incompatible with Pest 5).
- Pest (`./vendor/bin/pest --testsuite=Customer --no-coverage`): **25 failed / 21 passed**,
  identical split to the story 5.67 re-verification baseline above — 100% pre-existing
  `workorder_data_test` missing-migration `QueryException` on the `customer` connection, not a
  regression from this change (const-only edits touch no runtime behaviour).

### Interesting finding: no new commit needed

After restoring the 8 constants, `git diff` in **both** the nested `Modules/Customer` repo and
the root monorepo showed **zero delta** for 4 of the 5 files, and only the (unrelated, expected)
Epic4 structural diff on the 5th (`ResellerResource.php`) — because both repos' `HEAD` already
contained the correct typed-constant content from the original fix (nested: `1d9da95`/`6ab7070`;
root: the `ee91599e5` typeCoverage-campaign chain). The regression only ever existed in the
shared **working tree**, introduced by the stale Epic4 WIP checkout, never committed anywhere.
Restoring the working tree to match already-committed content requires no new commit — this
session's contribution is the diagnosis and the restoration, not a new commit hash. See
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` for the same wrong-pattern
class found on other modules, and `docs/chat/2026-09-07-customer-typecoverage-fix.md` for the
original fix this session re-applied.

### Outcome

`typeCoverage.constantTypeCoverage`: 0 → 0 occurrences in `Modules/Customer` (regression
caught and reverted before it could resurface in the next full-tree scan). No PHP commit
required (working tree now matches already-committed HEAD in both repos); only this doc update
committed.
