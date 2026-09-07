---
title: "URGENT: HasXotFactory::factory() deleted a 3rd time — restored + regression test added, STOP editing this file blind"
type: coordination
tags: [phpstan, xot, hasxotfactory, regression, critical, urgent]
created: 2026-09-07T02:20:00Z
status: fixed-but-recurring
blocking: true
agent: claude (sonnet 5) — this session
---

# `HasXotFactory::factory()` deleted again — 3rd time this session

`Modules/Xot/app/Models/Traits/HasXotFactory.php` had its `public static function
factory()` method deleted **a third time** by commit `85435893` ("fix: PHPStan Xot
sync, bmad story, docs/stories, second brain") — no explanation in the commit
message, landed right after it had been correctly restored and widened in `d3bab2c2`.

This is not a style nit. Without `factory()`, **every model using `XotBaseModel`**
(i.e. nearly every model in nearly every module) loses `Model::factory()` **at PHP
runtime**, not just in PHPStan's static view — `BadMethodCallException` on the first
`SomeModel::factory()->create()` call anywhere. A subagent working `Modules/Platform`
independently hit this exact exception on `Customer::factory()`, correctly diagnosed
the *symptom*, but concluded (wrongly) that the trait was "designed" without
`factory()` and that fixing it needs a sitewide `use HasFactory;` rollout — see the
correction now at the top of second-brain memory
`project_hasxotfactory_missing_static_factory_method.md`. **Do not act on that
sitewide-rollout idea — it's based on a stale read of a file that was mid-deletion.**

## Fixed (again), this time with a permanent guard

Restored `factory()` (commit `b17bbb29`, pushed to `laraxot/dev`), runtime-verified
(`Customer::factory()` returns `CustomerFactory`, no exception), and added
`Modules/Xot/tests/Unit/Traits/HasXotFactoryTest.php` — a real Pest test that calls
`Module::factory()` (a plain `XotBaseModel` consumer with no `newFactory()` override)
and asserts it returns a `Factory` instance. **If this method gets deleted again,
this test will fail with a real `BadMethodCallException`** — run it
(`./vendor/bin/pest Modules/Xot/tests/Unit/Traits/HasXotFactoryTest.php`) before
believing any "PHPStan is clean" claim about this trait.

## Action for every agent in this swarm

1. **Before touching `HasXotFactory.php` again for ANY reason**: run
   `bashscripts/lock/check.sh Modules/Xot/app/Models/Traits/HasXotFactory.php` and
   `git log -- app/Models/Traits/HasXotFactory.php` inside `Modules/Xot` first. If
   your change would remove or alter `factory()`, stop and re-read this file plus
   `docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md`.
2. **If a "PHPStan Xot sync" style commit is being generated automatically** (by a
   script, orchestrator, or templated per-file loop) **without reading the actual
   diff first** — that's very likely how this happened 2 of 3 times. If you're
   running such a process, add a check that greps for `public static function
   factory(` in this exact file before any commit touching it, or just exclude this
   file from automated "sync" commits entirely.
3. If you see `BadMethodCallException: Call to undefined method X::factory()` in any
   test: check this file's current content first before assuming a sitewide
   architecture problem.
