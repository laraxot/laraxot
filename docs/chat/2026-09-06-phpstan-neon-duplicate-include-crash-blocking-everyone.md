---
title: "CRITICAL: phpstan.neon duplicate-include crash blocks ALL phpstan runs — fix pending (owner-only)"
type: coordination
tags: [phpstan, blocking, pest, larastan, phpstan-neon, critical]
created: 2026-09-06T21:22:00Z
status: blocked-pending-owner
agent: claude (sonnet 5) — this session
scope: laravel/phpstan.neon (owner-only file)
blocking: true
---

# CRITICAL — every `phpstan analyse` invocation is currently failing

## Symptom

Any `phpstan analyse ...` (any scope, any module) exits 1 immediately after printing:

```
These files are included multiple times:
- .../vendor/larastan/larastan/extension.neon
- .../vendor/nesbot/carbon/extension.neon
- .../vendor/pestphp/pest/extension.neon

It can lead to unexpected results. If you're using phpstan/extension-installer, make
sure you have removed corresponding neon files from your project config file.
```

No `[OK]`/`[ERROR] Found N errors` ever prints. It's a hard bootstrap failure, not a
normal analysis result. **If your scan right now says "0 errors" or hangs with no
output, distrust it — check the exit code / this exact message first.**

## Root cause (confirmed via throwaway `-c` config copy, no phpstan.neon touched)

Another agent ran `composer require pestphp/pest:^5.0 -W` this session (uncommitted,
`laravel/composer.json`), bumping `pestphp/pest` 4.7.8 → 5.1.3 (also pulled
`larastan/larastan` 3.11.0, `nesbot/carbon` 3.13.2). PHPStan 2.2.13 **natively**
auto-discovers `extra.phpstan.includes` from `vendor/composer/installed.json` for any
installed package that declares it — no `phpstan/extension-installer` package needed
(confirmed: that package isn't even installed here). `larastan/larastan`,
`nesbot/carbon`, and `pestphp/pest` all declare this hint. `phpstan.neon` **also**
manually lists these same 3 `extension.neon` files under `includes:` — so they load
twice, and this specific PHPStan version treats that duplication as fatal.

Confirmed fix (tested against a scratch copy of phpstan.neon via `-c`, never touched
the real file): remove these 3 lines from `phpstan.neon`'s `includes:` block —

```
- ./vendor/larastan/larastan/extension.neon
- ./vendor/nesbot/carbon/extension.neon
- ./vendor/pestphp/pest/extension.neon
```

(Keep `./vendor/phpstan/phpstan/conf/bleedingEdge.neon` and
`./vendor/pestphp/pest/phpstan-pest-extension.neon` — those aren't auto-discovered
duplicates.)

## Status

`phpstan.neon` is owner-only (`bashscripts/ai/wiki/rules/phpstan-neon-immutable` /
project memory). Asked the user directly; **they're making this edit themselves.**
Do not edit `phpstan.neon` yourselves in the meantime — wait for the owner's commit,
then re-baseline.

## Action for other agents right now

- **Every phpstan baseline captured after ~21:15 UTC today is invalid** (includes any
  numbers from this session's own earlier full-tree rescan attempt, and anyone else's
  concurrent runs). Re-baseline once this lands.
- If you need phpstan urgently before the owner's edit lands, you can reproduce a
  local, uncommitted throwaway `-c custom.neon` (copy of phpstan.neon minus those 3
  lines, in `laravel/`) for your own verification — **do not commit that file**, and
  don't treat it as a substitute for the real fix.
- Also note: `pestphp/pest` is now `5.1.3` (was `4.7.8`) — if you see Pest-related
  test failures unrelated to your change, this upgrade is why; check
  `git diff laravel/composer.json` / `composer.lock` before assuming your code broke
  something.

## Related

- Same-session fix already landed and verified before this regression hit:
  `docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md` — pushed
  to `Modules/Xot` before vendor/ got churned by the Pest upgrade, unaffected by this.
