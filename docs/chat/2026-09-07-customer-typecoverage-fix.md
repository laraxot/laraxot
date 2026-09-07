---
title: "Customer module: typeCoverage.constantTypeCoverage fix (story 2.8, Wave B)"
type: coordination
tags: [phpstan, customer, typecoverage, quality-gate]
created: 2026-09-07
agent: claude (sonnet 5) — this session
---

# Customer: constantTypeCoverage fix (2026-09-07)

Task: type the 8 untyped class constants in `Modules/Customer` to move the
repo-wide `typeCoverage.constantTypeCoverage` metric, per story
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` (Wave B).

## Result summary

- 8 constants typed across 5 files:
  - `app/Actions/SearchCompanyOnlineAction.php` — `private const int MAX_RESULTS`
  - `app/Lookup/OpenApiCompanyLookupProvider.php` — `private const int MAX_RESULTS`
  - `app/Lookup/OpenCorporatesCompanyLookupProvider.php` — `private const string
    ENDPOINT`, `private const int MAX_RESULTS`
  - `app/Lookup/ViesCompanyLookupProvider.php` — `private const string ENDPOINT`
  - `app/Filament/Resources/ResellerResource.php` — `private const string
    ACCOUNT_FEATURE_ADMIN`, `private const ?string ACCOUNT_FEATURE_OPERATOR`
    (nullable — matches `AccountFeatures::currentPanelAllowsFeature(?string
    $operatorFeature = null)`), `private const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT`
- Full-tree PHPStan (`phpstan clear-result-cache && phpstan analyse`, no path
  argument — required, this rule only fires when the FULL configured
  `./Modules/` scope is analysed, see
  `TomasVotruba\TypeCoverage\Configuration\ScopeConfigurationResolver::areFullPathsAnalysed`):
  277/300 = 92.3% project-wide, 23 residual occurrences, **zero in
  `Modules/Customer/`** (grep-confirmed).
- `php -l` clean on all 5 files.
- PHPMD: bundled `tools/phpmd.phar` (old pdepend parser) crashes
  intermittently when scanning the whole `Modules/Customer` directory in one
  pass. Isolated: every `app/*` subdirectory scans cleanly alone, and the
  same crash shape reproduces on an untouched module
  (`Modules/Rating/.../StatsOverview.php`, `Unexpected token: (`) — a
  pre-existing tool/parser limitation, not caused by this change. Scanned
  the 5 touched files individually: only pre-existing, unrelated findings
  (CyclomaticComplexity, NPathComplexity, ShortVariable,
  CouplingBetweenObjects on method bodies untouched by this fix).
- PHPInsights: not run — removed from this repo (incompatible with Pest 5),
  confirmed by session hook and other agents' sessions today.
- Pest (`Modules/Customer/tests`, 46 tests): **all fail**, but not because of
  this change — see blocker below.

## Heads up: found uncommitted WIP already on disk before starting

All 5 files had pre-existing, uncommitted WIP using the **wrong** pattern
(`/** @var int */` / `/** @var string */` PHPDoc immediately above the
untyped `const`, no native type). This does not satisfy the rule — the
collector (`ConstantTypeDeclarationCollector::processNode`) only checks
`$classConst->type instanceof Node`, the native PHP 8.3+ typed-constant AST
node, and ignores PHPDoc entirely (same pattern already documented in
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` and
found by several other agents today on Bom/WorkOrder/Platform). Replaced
with real native `const <type> NAME = ...;` declarations.

Also found but **not** touched (left as-is, excluded from commit via
targeted `git add`, not `git add -A`):
- `app/Filament/Resources/CustomerResource.php` — unrelated WIP, just an
  import reorder, no constants.
- `docs/stories/phpstan-fix-1788768735.story.md` — untracked, empty (0
  bytes).
- `phpmd.ruleset.xml` — untracked module-level PHPMD ruleset (used it
  read-only to run the phpmd checks above, but it's not mine to commit).

## Blocker, not caused by this session: Pest suite-wide failure

`Modules/Customer/tests` (all 46 tests) currently fail with `Class
"Modules\Xot\Filament\Resources\XotBaseResourceForm" not found` (and, on
retry, `"Modules\Xot\Filament\Schemas\XotBaseResourceInfolist" not found`),
raised from `Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/SiteSurveyForm.php`
and `SiteSurveyInfolist.php` — both currently `use` those two Xot classes at
the wrong namespace (real classes live under
`Modules\Xot\Filament\Resources\Schemas\...`). Any Feature test that boots
the Filament panel provider (`XotBasePanelProvider.php:100`) fails
project-wide, independent of which module owns the test — this is the same
incident already flagged today by the AiAssistant and Platform sessions (see
their entries in `docs/stories/2.8.phpstan-constant-type-coverage.story.md`)
and in `docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md`
(tracked as P0 story `4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md`,
owned by another agent). Confirmed unrelated to Customer: `git -C
Modules/Quotation status` shows ~20 files under active uncommitted WIP from
a concurrent session right now. Not fixed here — out of scope for a
Customer-only task, and those files are being actively edited elsewhere.

`ps aux` during this session showed multiple concurrent full-tree `phpstan
analyse` runs from other sessions racing on the same shared `/tmp/phpstan`
result cache, plus another agent polling the story-file lock waiting for me
to release it. Heavy contention today; expect slow full-tree runs.

## Git

Committed and pushed: `1d9da95` on `git@github.com:laraxot/module_customer_fila5.git`
branch `dev` (`68188d6..1d9da95`). No divergence from remote (only remote:
`laraxot`), fetch/merge was a no-op, clean fast push.
