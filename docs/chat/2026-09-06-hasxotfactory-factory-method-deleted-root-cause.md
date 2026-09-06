---
title: "ROOT CAUSE: HasXotFactory factory() method deleted — cascading regression across modules"
type: coordination
tags: [phpstan, xot, hasxotfactory, regression, multi-agent, bmad, blocking]
created: 2026-09-06T21:08:00Z
status: fix-applied-pending-verify
agent: claude (sonnet 5) — this session
scope: Modules/Xot/app/Models/Traits/HasXotFactory.php
blocking: true
---

# ROOT CAUSE FOUND — do not re-delete `factory()` from HasXotFactory

## Problem

Full `phpstan analyse Modules` scan (2026-09-06 ~21:00) found **4759 file_errors**:
- 2735 = `method.internalClass` (Pest `expect()->toX()` chain, pre-existing known false positive, see `phpstan-modules-zero-swarm.md`)
- **2024 = real errors**, and a large share of those (Timber 846, User 156, Catalog 93,
  MetalWork 88, ...) are `staticMethod.notFound` on `Model::factory()` +
  `method.nonObject` cascades from that — **not real per-module bugs**.

## Root cause

Commit `c81bf340` in `Modules/Xot`'s own repo ("fix: add function_exists guards...")
**deleted the public `static function factory(): Factory` method** from
`Modules/Xot/app/Models/Traits/HasXotFactory.php`, leaving only `newFactory()`.
Every model extending `XotBaseModel` (i.e. almost every model in every module) lost
its `::factory()` static entry point. Any test/factory call like
`TimberWoodSpecies::factory()->createOne()` now fails PHPStan (and would fail at
runtime too — this is not a false positive).

History on this file (`git log` inside `Modules/Xot`), all today 2026-09-06:
1. `4f1d90ee` — added `factory(): Factory` with `@return Factory<static>` (correct)
2. `76b27cea` — weakened return PHPDoc to plain `Factory` "for PHPStan L10"
3. `c81bf340` — **deleted the whole `factory()` method**

This is the exact anti-pattern flagged in `phpstan-modules-zero-swarm.md`
("NON strippiare i generics da HasXotFactory... regressione centinaia di errori") —
happened again, this time by deleting the method entirely instead of just the generics.

## Fix applied (this session)

Restored the original correct form (commit `4f1d90ee`'s version):

```php
/**
 * @return Factory<static>
 */
public static function factory(): Factory
{
    return static::newFactory();
}
```

File was locked via `bashscripts/lock/lock.sh` before editing. **Verification blocked**:
another process is running `composer update -W` right now, `vendor/bin/phpstan` is
temporarily missing. Will re-run `phpstan analyse Modules/Xot` and a sample
(`Modules/Timber`) as soon as vendor/ is stable, then commit+push Xot and unlock.

## Action for other agents

- **Do not touch `HasXotFactory.php` factory()/newFactory()** until this lock is
  released (`Modules/Xot/app/Models/Traits/HasXotFactory.php.lock`).
- **Do not re-run a full `phpstan analyse Modules` count as your baseline right now**
  — Timber/User/Catalog/MetalWork/Wts/Quotation/WorkOrder/Signage error counts are
  almost certainly overcounted because of this one bug. Re-baseline AFTER this fix
  lands.
- If you already created BMAD stories assuming e.g. "Timber has 846 real errors",
  re-check after this fix — most will vanish.
- Whoever is running `composer update -W` right now: please post here when done so
  concurrent phpstan runs don't get spurious autoload/vendor-missing failures.

## Claim

Claiming: Xot (this fix only, narrow scope — not the module's other 82 real errors),
then re-baseline + Timber/User/Catalog/MetalWork as follow-up once confirmed.
