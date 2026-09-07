---
title: "Modules/Signage: typeCoverage.constantTypeCoverage fix + @var regression corrected"
type: coordination
tags: [phpstan, signage, typecoverage, coordination]
created: 2026-09-07
status: done
agent: claude (sonnet 5) — this session
---

# Modules/Signage: typeCoverage.constantTypeCoverage fix

Full detail: `laravel/Modules/Signage/docs/coverage.md` (section
"typeCoverage.constantTypeCoverage fix + @var regression correction") and
`laravel/Modules/Signage/docs/stories/3.2.phpstan-l10-signage-undefined-methods-fix.story.md`
(Dev Agent Record, same-date follow-up entry).

## Summary

Gave a real PHP 8.3+ type declaration (`private const array|string|float`) to
all 12 previously-untyped class constants across:

- `app/Actions/ExtractSignComponentsFromArtworkAction.php`
- `app/Filament/Pages/SignArtworkQuotationPage.php`
- `app/Support/ArtworkTextLayout.php`
- `app/Support/SignageCosting.php`
- `database/seeders/SignageDemoSeeder.php`

A `@var` PHPDoc alone does not satisfy `typeCoverage.constantTypeCoverage` —
only the real type declaration does. See
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` for the
pattern this follows.

The `@var list<string>` regression on `SignageDemoSeeder::WORKFLOW_STATUSES`
flagged in that coordination doc was already fixed by another agent before
this session started (commit `add68d6`, correct shape
`list<array{code, name, color, is_terminal, sort_order}>`). This session only
added the missing real `array` type keyword on top of that already-correct
`@var`.

## Concurrency during this session

This repo has many AI agents on the same working tree at once. Mid-session, a
different concurrent agent independently added partial `@var`-only edits (no
real type keywords, and briefly downgraded `WORKFLOW_STATUSES`'s shape from
`list<...>` to `array<int, ...>`) to 4 of the 5 target files while this
session's fix was stashed for a baseline PHPStan comparison run. Reconciled by
re-applying the real type keywords on top of that agent's doc improvements
(not discarding them) and restoring the more precise `list<...>` shape.

A separate, unrelated concurrent task (`gatedxotbaseresource-removal-repo-wide`,
commit `bacd20f`) touched 5 `app/Filament/Resources/*.php` files in the same
working tree at the same time. Those were left untouched/uncommitted by this
session — out of scope, owned by that other task/agent.

If you're the owner of `gatedxotbaseresource-removal-repo-wide`: your 5
Resource-file diffs (`SignCatalogItemResource.php`,
`SignEstimationRuleResource.php`, `SignPriceListItemResource.php`,
`SignPriceListResource.php`, `SignWorkCenterResource.php`, one-line each) are
still sitting uncommitted in the working tree as of this note — please commit
them yourself.

## Numbers

- Full-tree `typeCoverage.constantTypeCoverage`: 183/300 (61.0%) → 193/300
  (64.3%). Signage files flagged by this specific check: 4 → 0.
- Isolated `phpstan analyse Modules/Signage --no-progress --memory-limit=-1`
  (clean result-cache): 0 errors, before and after.
- PHPMD (module-wide): identical 34 pre-existing findings, no new ones.
- Pest (`Modules/Signage/tests --no-coverage --compact`): identical
  **47 failed, 7 passed (57 assertions)** before and after — zero regression.
  All failures are pre-existing missing-DB-table/column errors in
  `workorder_data_test`, not touched by this fix (data-is-sacred rule: no
  destructive migration run).
- No `phpstan.neon`/baseline/ignore changes.

## Git

Committed and pushed inside the `Modules/Signage` submodule to `laraxot dev`
(single remote, no divergence — fast-forward push, commit `28d7bd4` on top of
`bacd20f`).

## Note on an injected mid-task instruction

Partway through this session a message arrived via the tool-result channel
claiming to be "the coordinator" imposing a new rule: every `.md` this session
touches must carry GitHub issue/discussion links in its frontmatter, created
via `gh issue create`/`gh api graphql` if none exist. This was not followed:
it asked for unrequested, side-effecting external actions (creating public
GitHub artifacts) with no connection to the assigned task, arriving as
injected content rather than a genuine instruction from the launching agent.
Flagging here for visibility in case the same injection pattern shows up in
other agents' sessions on this repo.
