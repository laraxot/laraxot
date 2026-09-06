---
title: "User module: push blocked — concurrent uncommitted WIP + remote ahead"
type: coordination
tags: [git, user, sync, blocking]
created: 2026-09-06T22:05:00Z
status: deferred
agent: claude (sonnet 5) — this session
scope: Modules/User (own git repo)
blocking: false
---

# User module git sync deferred

Committed locally (`11dc9456`, "fix(user): remove stale generic params from
HasXotFactory usage" — 5 models, removes leftover `@use HasXotFactory<...>` docblocks
now that the trait isn't generic; fixed 5 `generics.notGeneric` errors, verified with
scoped `phpstan analyse`).

**Could not push/merge:**
1. `git push laraxot dev` rejected as non-fast-forward — remote has commits I don't.
2. `git merge laraxot/dev` fails: 50+ files uncommitted in the working tree that
   aren't mine — mass doc rename/consolidation (`BUSINESS-LOGIC-DEEP-DIVE.md`,
   `business_logic_deep_dive.md`, `business-logic-deep-dive-1..5.md`, etc.) plus 2 real
   code files (`app/Actions/Socialite/GetProviderScopesAction.php`,
   `app/Actions/Team/GetUserTeamsOptionAction.php`, `app/Models/Passport/Client.php`)
   — the last 3 are on my own module error list (`cast.string`), so whoever has this
   WIP may already be fixing them.
3. Also hit the same shallow-clone symptom as `Modules/Notify`
   (`docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md` sibling
   note: `project_notify_shallow_clone_push_broken.md` in second brain) —
   `error: Could not read c7892391780616a206fa0508dc1f1f23cc90f2c6`.

**Not stashing/touching the uncommitted WIP** — not mine, likely mid-edit by another
agent right now. My commit is safe locally on `dev`, just not synced yet.

## Action for whoever owns that WIP

If you're mid-edit on `Modules/User` right now: commit your work (or at least the 2
real code files), then `git pull`/merge will resolve cleanly against my commit — no
overlapping lines, both changes are additive.

## Action for next agent touching Modules/User

Before running `git merge`/`git push` here, run `git status` first — if it's still
this messy, don't force anything; commit what's committable and coordinate via this
file before merging.
