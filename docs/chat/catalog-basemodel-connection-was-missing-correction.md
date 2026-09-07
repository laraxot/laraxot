---
title: "CORRECTION: Catalog\\Models\\BaseModel was missing \\$connection, prior claim was false"
type: coordination
tags: [catalog, architecture, basemodel, connection, correction]
created: 2026-09-07T10:55:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
supersedes: 2026-09-07-catalog-basemodel-added-already-done.md
---

# Correction to the prior "already done" note

`2026-09-07-catalog-basemodel-added-already-done.md` (commit `9a67667`)
claimed `Modules\Catalog\Models\BaseModel` was complete with "no dedicated
DB connection — none configured for catalog, not guessed". **That was
false.** Don't trust it if you still see it referenced anywhere.

Checked all other 28 modules' `app/Models/BaseModel.php`: every single one
sets `protected $connection = <module_snake_name>` (e.g. `costing`,
`customer`, `xot`...), dynamically backed by
`Modules\Tenant\Providers\TenantServiceProvider::mergeModuleConnections()`
which clones the default connection per enabled module at runtime, keyed by
`$module->getSnakeName()`. Catalog was the only module missing it.

Fixed in commit on `laraxot/dev` (Catalog module repo): added
`protected $connection = 'catalog';` to `BaseModel.php`. Verified with
PHPStan (0 errors), PHPMD (0 violations), PHPInsights (all >70), and a
before/after Pest run (6 failed/5 passed -> 5 failed/6 passed, net
improvement, no regression — remaining 5 failures are pre-existing missing
migrations on `workorder_data_test` for the `catalog` connection, unrelated
to this fix). Full detail: `Modules/Catalog/docs/stories/catalog-basemodel-missing-connection-fix.story.md`
and `Modules/Catalog/docs/coverage.md`.

**If you're about to touch `Modules/<X>/app/Models/BaseModel.php` in any
module:** don't just check it compiles or matches a literal ask — diff it
against a couple of sibling modules' `BaseModel.php` first. This is the
second time in one day a superficial check on this exact file missed the
`$connection` convention.
