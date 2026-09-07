---
title: "CLAIM: Modules/Signage PHPStan cleanup (excluding smalot/pdfparser blocker)"
type: coordination
tags: [phpstan, signage, claim]
created: 2026-09-07T00:00:00Z
status: done
agent: claude (sonnet 5) — this session
---

# Claim: Modules/Signage

Working through remaining PHPStan errors in `Modules/Signage` per
`docs/chat/2026-09-07-missing-smalot-pdfparser-dependency.md` (that file's blocker is
explicitly out of scope, left as-is).

Scope: Filament v4->v5 `actions()`/`bulkActions()` deprecations, `cast.*` narrowing in
Actions/Pages/factories/seeders/tests.

Updating existing story `Modules/Signage/docs/stories/3.2.phpstan-l10-signage-undefined-methods-fix.story.md`
(and/or `01.Signage-phpstan-fix.story.md`) rather than creating a new one.

## Result

PHPStan `Modules/Signage`: 26 -> 10 errors. Remaining 10 are all in
`ExtractSignComponentsFromArtworkAction.php` (the smalot/pdfparser blocker above,
out of scope). Fixed: `PriceSignSpecAction.php`, `SignArtworkQuotationPage.php`,
5 Filament Resources (`actions()`/`bulkActions()` -> `recordActions()`/
`toolbarActions()`), `SignSpecFactory.php`, `SignWorkCenterSeeder.php`,
`SignArtworkQuotationPageTest.php`. No `phpstan.neon`/baseline/ignore changes.

Pest `Modules/Signage/tests`: unchanged 53 failed / 1 passed before and after
(pre-existing missing-DB-table/column errors in `workorder_data_test` — `roles`,
`sign_price_list_items`, `sign_specs.created_by_user_id` — not caused by this
work, not fixed here per the no-destructive-DB rule). PHPInsights: Complexity
83.6->84.1, Style 88.0->89.2, no regressions. Full detail in
`laravel/Modules/Signage/docs/coverage.md`.

Committed and pushed to `laraxot dev` (`d3fdc43`).

**Heads-up for whoever touches Signage next:** at commit time, the working tree
also had uncommitted whitespace-only drift (blank line after `{` removed) on 5
already-committed model files (`SignCatalogItem.php`, `SignCatalogItemComponent.php`,
`SignPriceList.php`, `SignPriceListItem.php`, `SignSpec.php`) that this session
did not create and left untouched/uncommitted, per "exclude anything not yours."
Origin unclear (not from this session's edits or hooks) — if you're the one who
ran a formatter across the module, please commit or discard that diff yourself.
