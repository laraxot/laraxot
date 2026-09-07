---
id: phpstan-WhatsApp-fix
slug: phpstan-WhatsApp
scope: [module:WhatsApp, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema
PHPStan errors in Modules/WhatsApp — 26 `cast.int`/`cast.string` errors across
9 files, all caused by casting `mixed` values (classifier result arrays,
`config()` defaults, `data_get()`/`getAttribute()` results, `DB::table()->value()`
results) directly instead of narrowing them first.

## Solution
1. Analyze with phpstan — DONE, 26 errors catalogued.
2. Fix pattern errors — DONE. Every cast site now narrows with `is_string()` /
   `is_numeric()` / `is_array()` before use. Consolidated shared narrowing into
   small private helpers (`intValue`/`stringValue`) where the same
   `array<string, mixed>` source flowed through multiple methods in one class
   (`ProcessWhatsAppMessageAction`, `ResolveWhatsAppMessageContextAction`,
   `MetaWhatsAppClient`); inlined narrowing where each class only had a single
   call site. No `@phpstan-ignore`, baseline entries, or bare widening casts.
3. Verify with phpmd + phpinsights + pest — DONE (see
   `Modules/WhatsApp/docs/coverage.md` for full detail). PHPStan: 26 → 0.
   PHPMD: no new violation categories (pre-existing debt confirmed via
   before/after diff on `ProcessWhatsAppMessageAction`). PHPInsights: fails
   with a pre-existing `ComposerNotFound` tooling limitation when scoped to a
   module subdirectory in this monorepo, unrelated to this fix. Pest: 38
   pre-existing failures from a stale/incomplete `workorder_data_test`
   database (missing tables), reproducible on untouched test files too — not
   fixed via `migrate:fresh` per "data is sacred" policy, documented instead.
4. Git sync — see module git log / claim file
   `docs/chat/2026-09-07-phpstan-whatsapp-claim.md` at repo root.
