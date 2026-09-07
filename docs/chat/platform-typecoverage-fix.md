---
title: "Platform typeCoverage.constantTypeCoverage fix — story 2.8 Wave B"
type: coordination
tags: [phpstan, platform, type-coverage, story-2.8, multi-agent]
created: 2026-09-07T00:00:00Z
agent: claude (sonnet 5)
status: done
blocking: false
---

# Platform — typeCoverage.constantTypeCoverage fix (story 2.8, Wave B)

## Scope

5 class constants without an explicit native type, per
`docs/stories/2.8.phpstan-constant-type-coverage.story.md`:

- `Modules/Platform/app/Company/CompanyProfileProvider.php:19`
- `Modules/Platform/app/Support/Navigation/NavigationClassifier.php:28`
- `Modules/Platform/app/Support/Navigation/NavigationGate.php:35`
- `Modules/Platform/database/seeders/BoutiqueIdraulicaDemoSeeder.php:51`
- `Modules/Platform/database/seeders/IdroclimaRecaldiniDemoSeeder.php:53`

Explicitly out of scope, not touched, not committed: `app/Filament/Resources/*`
(active P0 story `4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md`
and `docs/stories/2.9.gated-resource-naming-migration.story.md`, other agents
mid-flight on those files today).

## What was found and fixed

All 5 files already had an **uncommitted WIP** in the working tree (not from
this session) that added `/** @var string */` / `/** @var list<string> */`
PHPDoc comments above the constants — the same wrong pattern already
documented for Bom/WorkOrder in this story's log. Verified this does **not**
satisfy the PHPStan rule: `TomasVotruba\TypeCoverage\Collectors\ConstantTypeDeclarationCollector`
only checks `$classConst->type instanceof Node` (the native AST type node on
the `const` declaration), never PHPDoc. Discarded that WIP and applied real
native PHP 8.3+ typed constants instead:

```php
// before
public const SETTINGS_KEY = 'company.profile';
private const SUBSYSTEM_SEGMENTS = [...];
private const PROTECTED_ITEM_CLASSES = [...];
private const REGION = 'emilia-romagna'; // x2, different values

// after
public const string SETTINGS_KEY = 'company.profile';
private const array SUBSYSTEM_SEGMENTS = [...];   // @var array<int, string> kept
private const array PROTECTED_ITEM_CLASSES = [...]; // @var array<int, string> kept
private const string REGION = 'emilia-romagna'; // x2
```

## Tooling finding worth flagging to other agents

`typeCoverage.constantTypeCoverage` only fires on a run where the analysed
paths exactly match `phpstan.neon`'s configured `paths: ./Modules/`
(`TomasVotruba\TypeCoverage\Configuration\ScopeConfigurationResolver::areFullPathsAnalysed`).
Running `phpstan analyse Modules/<Anything>` — the natural per-module
workflow — **always silently reports 0 errors for this specific rule**,
whether or not the module actually has untyped constants. The only way to see
real errors (or confirm they're gone) is a full run with no path argument:
`./vendor/bin/phpstan analyse --no-progress --memory-limit=-1
--error-format=raw`. Several other agents in this story's log independently
hit the same thing.

## Verification

- Full-tree run (no path arg, `--error-format=raw`): project-wide 277/300
  (92.3%), threshold 99%, 23 residual occurrences outside Platform (Billing,
  Compliance, WorkOrder). **0 occurrences in `Modules/Platform/`.**
- `php -l`: clean on all 5 files.
- `pint --test` (targeted at the 5 files, not `--dirty` — see second-brain
  `feedback_pint_dirty_scope_multi_agent.md`, `--dirty` ignores the path arg
  and reformats the whole repo including other agents' in-flight files):
  passed, no changes needed.
- `phpmd` (`Modules/Platform/phpmd.ruleset.xml`, targeted at the 5 files): 3
  pre-existing findings unrelated to this change (`ShortVariable $m` in
  `NavigationClassifier::moduleFor()`, `CouplingBetweenObjects` on both
  demo seeders — long-standing god-seeder pattern).
- phpinsights: not available in this repo (removed, incompatible with Pest 5).
- Pest: **blocked, not by this change**. `Modules/Platform/tests` fails to
  bootstrap Filament panel registration right now
  (`Class "Modules\Xot\Filament\Resources\XotBaseResourceForm" not found` /
  `Class "Modules\Xot\Filament\Schemas\XotBaseResourceInfolist" not found`,
  thrown from `Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/*`
  loaded during panel boot). Root cause: a concurrent, uncommitted
  cross-module WIP in `Modules/Quotation` + `Modules/Xot` (mid-flight
  `Filament/Resources/*` → `Filament/Resources/Schemas|Schemas/*` rename by
  another agent), same failure class as
  `docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md`
  and already reported independently for AiAssistant in this story's log.
  Verified independent of this session: `git stash` A/B on the 5 typed
  constants reproduces the identical failure with or without this diff.

## Git

- `Modules/Platform`: commit `b51dcb6` (`refactor(platform): type constants
  [story: 2.8]`), 6 files (5 target + `docs/coverage.md`). Fetch
  `laraxot dev`: already aligned (0 behind, 1 ahead), no merge needed. Pushed
  clean: `2116098..b51dcb6 dev -> dev`. Single remote (`laraxot`).
- Confirmed via `git status` before/after: no file under
  `app/Filament/Resources/` was staged or committed by this session.

## Details

`Modules/Platform/docs/coverage.md`,
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` (Log avanzamento,
Platform entry).
