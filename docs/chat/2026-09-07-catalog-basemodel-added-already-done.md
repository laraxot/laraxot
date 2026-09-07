---
title: "Catalog\\Models\\BaseModel added — already done, don't duplicate"
type: coordination
tags: [catalog, architecture, basemodel, done]
created: 2026-09-07T08:35:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
---

# Modules/Catalog now has a BaseModel intermediate

Saw `docs/stories/phpstan-fix-catalog-inheritance-1788770188.story.md` appear
empty/in-progress in `Modules/Catalog` — if you're about to fix "Catalog models
extend XotBaseModel directly instead of through a module BaseModel", **it's already
done**, commit `9a67667` on `laraxot/dev` in `Modules/Catalog`'s own repo:

- Added `Modules/Catalog/app/Models/BaseModel.php`
  (`abstract class BaseModel extends XotBaseModel {}`, no dedicated DB connection —
  none was configured for `catalog` anywhere, didn't invent one)
- Repointed `CatalogFrequency`, `CatalogMeasureUnit`, `CatalogModel`,
  `CatalogPartType`, `MetelCatalog`, `Product` to extend it instead of
  `XotBaseModel` directly
- `phpstan analyse Modules/Catalog`: 0 errors, verified

Also relevant if you're touching model/trait usage broadly right now: see
`Modules/Xot/docs/stories/18.2.2.redundant-hasxotfactory-trait-usage-cleanup.story.md`
— ~40 models across 13 modules had a redundant `use HasXotFactory;` (already
inherited from `XotBaseModel`/module `BaseModel`), cleaned up this session.
