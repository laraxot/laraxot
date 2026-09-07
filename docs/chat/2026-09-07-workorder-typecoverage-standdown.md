---
title: "PHPStan constantTypeCoverage — WorkOrder stand down, active WIP collision risk"
type: coordination
tags: [phpstan, typecoverage, workorder, story-2.8, standdown]
created: 2026-09-07T15:30:00Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/WorkOrder
blocking: false
---

# Modules/WorkOrder — constantTypeCoverage fix NOT attempted, standing down

Assigned target: 1 untyped constant in
`database/migrations/2026_07_27_111500_create_profiles_table.php`
(`private const DATA_COLUMNS = [...]` → needs `private const array DATA_COLUMNS`).

## Why I stopped

`git status` in `Modules/WorkOrder` shows a large uncommitted WIP spanning many
files not touched by me: `WorkOrderResource.php`,
`WorkOrderResource/Pages/ListWorkOrders.php`, 6 RelationManagers,
`WorkOrderStatusResource.php` + its `ListWorkOrderStatuses.php`,
`Widgets/WorkOrdersStatusChart.php`, 4 `lang/it/*.php` files (2 new untracked),
plus the target migration file itself. File mtimes confirm this is a live,
in-progress session (`WorkOrderResource.php` modified ~20 minutes before I
started looking; the migration file ~87 minutes before). Task instructions are
explicit: if WorkOrder shows changes that aren't mine, don't touch it.

The target file already has a partial, seemingly-broken edit from that other
session:

```diff
     protected ?string $model_class = Profile::class;

+    /** @var list<string> */
     /** @var list<string> */
     private const DATA_COLUMNS = [
```

The `@var list<string>` docblock was duplicated (added twice, identically) and
the constant itself still has no native type — so whatever that session is
doing here isn't finished, and isn't the same fix I'd apply (native
`const array DATA_COLUMNS` is what closes `constantTypeCoverage`, a doc-block
alone doesn't). Editing this file now would very likely collide with that
agent's in-flight work.

Related, same day: `docs/chat/2026-09-06-sonnet5-quotation-workorder-overlap-flag.md`
already flagged a prior WorkOrder overlap between two sessions, and
`docs/chat/2026-09-06-phpstan-workorder-claim.md` documents an earlier, separate,
already-completed WorkOrder PHPStan pass (75→0 errors, unrelated rule).

## What's still needed (for whoever picks this up once WorkOrder is free)

```php
/** @var list<string> */
private const array DATA_COLUMNS = [
    'user_id',
    // ...
];
```

Single docblock (remove the duplicate), plus native `array` type on the
`const` declaration. Re-run `phpstan analyse Modules/WorkOrder`,
`./tools/phpmd.sh`, `./tools/phpinsights.sh`, `./vendor/bin/pest
Modules/WorkOrder/tests` as usual before commit/push.

No files in WorkOrder were modified by this session.
