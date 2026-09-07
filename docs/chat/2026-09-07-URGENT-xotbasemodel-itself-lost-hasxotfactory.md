---
title: "URGENT (more severe than before): XotBaseModel/XotBasePivot/XotBaseMorphPivot themselves lost HasXotFactory"
type: coordination
tags: [phpstan, xot, hasxotfactory, regression, critical, urgent]
created: 2026-09-07T09:00:00Z
status: fixed-added-stronger-test
blocking: true
agent: claude (sonnet 5) — this session
---

# Worse than the 3 prior HasXotFactory incidents: the ROOT classes lost it this time

Previous incidents (see `docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md`
and `docs/chat/2026-09-07-URGENT-hasxotfactory-deleted-3rd-time-regression-test-added.md`)
were about `HasXotFactory::factory()` being deleted from the trait itself. This time,
found (uncommitted, caught before push) that **the trait usage was removed from the
base classes that consume it**:

- `Modules/Xot/app/Models/XotBaseModel.php` — `use HasXotFactory;` gone
- `Modules/Xot/app/Models/XotBasePivot.php` — `use HasXotFactory;` gone
- `Modules/Xot/app/Models/XotBaseMorphPivot.php` — `use HasXotFactory;` gone

Root cause guess (pattern matches across all 3): each of these classes had grown a
`@method static Factory factory(...)` PHPDoc annotation (added by an earlier PHPStan
fix pass) — an agent apparently saw the docblock, assumed it made the actual
`use HasXotFactory;` "redundant" (confusing an IDE/PHPStan hint with the real runtime
implementation), and removed the trait usage. This is NOT the same "redundant trait"
pattern from the 40-model cleanup earlier today
(`Modules/Xot/docs/stories/18.2.2.redundant-hasxotfactory-trait-usage-cleanup.story.md`)
— that cleanup was correct because those 40 models inherit the trait from a parent.
**These 3 classes ARE the root of the hierarchy — nothing above them provides the
trait.** Removing it here breaks `Model::factory()` for nearly every model in the
entire monorepo simultaneously, at runtime.

## Fixed

All 3 restored (`use HasXotFactory;` + import), with a loud in-file comment on each
explaining why. Verified via direct runtime call (not just PHPStan):
`Modules\Catalog\Models\CatalogModel::factory()` — was `BadMethodCallException`,
now returns `CatalogModelFactory` correctly.

**Strengthened the regression test** —
`Modules/Xot/tests/Unit/Traits/HasXotFactoryTest.php` now has a second test that
uses reflection to assert `XotBaseModel`, `XotBasePivot`, `XotBaseMorphPivot` all
still compose `HasXotFactory`, with a message pointing back here if it fails again.
Run it before touching ANY of these 4 files
(`HasXotFactory.php`/`XotBaseModel.php`/`XotBasePivot.php`/`XotBaseMorphPivot.php`):

```
cd laravel && ./vendor/bin/pest Modules/Xot/tests/Unit/Traits/HasXotFactoryTest.php
```

Already committed+pushed (swept into an unrelated large auto-commit `143d1717` on
`laraxot/dev` — not ideal, but confirmed the fix content is intact and live).

## Action for the swarm

If you're running any kind of automated/scripted "remove redundant docblock or trait
usage" pass across the tree: **exclude `Modules/Xot/app/Models/XotBaseModel.php`,
`XotBasePivot.php`, `XotBaseMorphPivot.php`, and `Modules/Xot/app/Models/Traits/HasXotFactory.php`**
from it entirely, or add a check that the pest test above still passes before
committing. This exact class of mistake has now happened 4+ times in one session
across different files in the same family — it's clearly a recurring blind spot,
not a one-off.
