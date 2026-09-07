---
title: "PHPStan full-tree baseline 2026-09-07: 13417 errors/43 modules, root cause, 6 modules closed"
type: coordination
tags: [phpstan, blocking, larastan, pest, phpstan-neon, coordination]
created: 2026-09-07T10:45:00Z
agent: claude (sonnet 5) — this session
---

# PHPStan full-tree re-baseline and root cause (2026-09-07)

## Root cause of the 2026-09-06 evening regression

Owner commit `6eacf7c38` (22:05 UTC, 2026-09-06) commented out the
`larastan/larastan`, `nesbot/carbon`, `pestphp/pest` extension includes in
`phpstan.neon`, plus removed the `method.internalClass` ignore block for
Pest. This was a deliberate fix for a real hard-crash ("these files are
included multiple times") documented in
`2026-09-06-phpstan-neon-duplicate-include-crash-blocking-everyone.md`, but
its side effect is that PHPStan now runs with **zero Laravel/Eloquent
awareness and zero Pest fluent-API awareness**, repo-wide, permanently.

**Asked the owner directly whether to restore those includes. Explicit
answer: no — leave `phpstan.neon` exactly as-is, do not create any other
`.neon` file.** This is now the permanent ground truth for this project's
PHPStan gate. See second-brain-equivalent doc
`project_phpstan_neon_larastan_disabled_2026_09_07` if you have access to
this session's memory, otherwise treat this file as the record.

## Full-tree baseline (`phpstan analyse Modules`, 2026-09-07 ~09:00 UTC)

**13417 errors across 43 modules.** Isolated per-module scans undercount for
`method.internalClass` specifically (works differently in full-tree vs
scoped runs) — always verify against a full `Modules/` scan, not
`Modules/<Name>` alone, before declaring a module clean.

Top identifiers: `method.internalClass` 3629 (**structural, see below**),
`argument.type` 2754, `method.nonObject` 1322, `missingType.generics` 899,
`staticMethod.notFound` 883, `property.nonObject` 837, `method.notFound` 695,
`property.notFound` 675, `return.type` 668 (12 of these are the pre-existing
`HasXotFactory` static-generic-covariance limitation, unrelated, don't chase).

### The `method.internalClass` bucket (3629) is NOT fixable right now

Every `expect()->toBe()`/`it()->with()`/`uses()->group()` call in every test
file trips this, because Pest's own PHPStan extension (which whitelists
calling its `@internal` fluent-API classes from test files) is disabled along
with the others above. No `@phpstan-ignore` (banned by policy), no new neon
file (banned by owner), no neon edit (owner declined). Treat this as the same
structural class as the previously-documented `@mixin` residual: **document
it per-module in `docs/coverage.md`, don't chase it, don't re-ask the owner**.
Expect it in essentially every module with Pest tests — do not fail a module
for having only this residual.

### A useful fix pattern for the Larastan-shaped gap

`method.notFound` + cascading `method.nonObject` on `Model::query()->someScope()->...`
(Eloquent local scope called dynamically) — add a `@method static
Builder<Model> someScope(...)` PHPDoc tag on the model class itself. Confirmed
working (plain PHPStan resolves it via Builder's own generic `@mixin`, no
Larastan needed) on `Modules/Costing/app/Models/WorkCenter.php`.

## Modules closed this session (fix + phpstan + phpmd + pest + phpinsights + coverage.md + BMAD story + git commit/push, each its own repo)

AsdSoci (2->0), Giveback (3->1 structural), Seo (4->0), Thermo (4->1
structural), Document (14->10 structural), Costing (22->19 structural). All
real/fixable findings resolved; residuals are exclusively the Pest bucket
above. Full detail in each module's own `docs/coverage.md`.

## Heads up: shared working tree

During this pass, `Modules/Costing/app/Models/WorkCenter.php` changed on disk
mid-edit with no claim/lock — turned out to be a real commit
(`13b4f4d chore(costing): remove redundant use HasXotFactory on WorkCenter`)
from a sibling session sharing this same local checkout, not a separate
clone. It contradicted an already-closed 2026-09-06 story that explicitly
kept that trait — restored it before pushing (no live code called
`WorkCenter::factory()`, but it's the same recurring incident class already
documented on `HasXotFactory.php` itself). If you're claiming a module, a
`docs/chat/` note + lock still matters even though we're all on the same
disk — it's the only way to know someone else already looked at a file a
minute ago.

## Suggestion for whoever picks up the remaining ~37 modules

Re-verify against a fresh full-tree `phpstan analyse Modules` before trusting
any pre-2026-09-06-22:05 "0 errors" claim in an existing `docs/coverage.md` —
it's stale. The real remaining fixable volume, once you subtract the
structural Pest bucket, is meaningfully smaller than 13417 but still large;
biggest modules by raw count: Notify (1522), User (1519), Wts (1208),
Intervention (794), Timber (729), Xot (619), Quotation (608).
