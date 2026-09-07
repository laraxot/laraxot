---
title: "CRITICAL — module git history destroyed and force-pushed across ~40 module repos"
type: incident
tags: [git, incident, critical, data-loss, history-rewrite, force-push]
created: 2026-09-07T22:50:00+02:00
status: urgent-unresolved
agent: claude sonnet 5 (this session)
scope: nearly all Modules/* nested git repos (module_*_fila5 remotes)
blocking: true
severity: critical
---

# CRITICAL — nearly all module repos had their entire git history replaced by a single orphan commit

Found while committing an unrelated task (PHPStan `constantTypeCoverage` fix
for Quotation/Signage). This is **not** something this session did. Reporting
with maximum urgency because it affects the audit trail / recoverability of
work referenced by dozens of BMAD stories and `docs/chat/` entries across the
whole project.

## What happened

Every module in `laravel/Modules/*` has its own independent git repository
(`.git` inside the module dir, remote `laraxot/module_<name>_fila5`). At
least ~40 of these repos now have **exactly 1 commit** total, reachable from
`dev`, where previously they had dozens to hundreds (e.g. `Xot` still has 91,
`UI` still has 1598 — those two are intact, proving the normal history length
for an active module). The new sole commit:

- Has **no parent** (`git log --format=%P` is empty) — a true orphan root
  commit, not a squash-merge.
- Commit message is either a bare `"."` or (for a subset) a copy of the
  *previous* tip's subject line, which makes it look superficially normal at
  a glance — do not trust `git log -1` alone to rule this out, check
  `git rev-list --count HEAD` and `git log --format=%P -1` too.
- Contains a full `git add -A` style snapshot of whatever was on disk in the
  module directory at commit time, **including build/coordination
  artifacts that should never be tracked** (confirmed: `bashscripts/lock/*.lock`
  marker files from at least 3 different concurrent sessions ended up
  committed into `Quotation` and `Signage`; also visible in the `Quotation`
  diff: `README.md.bak`, `README.md.old` stray files).
- Has **already been force-pushed to the real remote** — confirmed
  independently via `git ls-remote laraxot dev` (not just local
  remote-tracking cache) for both `module_quotation_fila5` and
  `module_signage_fila5`. The old history is gone from the ref on GitHub,
  not just locally.

## Confirmed-affected modules (remote-verified or local rev-list=1 with a
suspicious pattern)

Quotation (`6fa0c88`, 2026-09-07 22:45:29+02:00, remote-confirmed), Signage
(`6e51066`, remote-confirmed), and by local `rev-list --count HEAD == 1`
pattern (not individually remote-verified, but the count plus the fact that
these modules are documented elsewhere in `docs/chat/`/BMAD stories as having
had many prior commits today makes natural short history implausible):
Activity, AI, AiAssistant, Billing, Blog, Catalog, Cms, Comment, Costing,
Customer, Dds, Document, Email, Employee, EnergyBroker, Fiscal, Gdpr, Geo,
Giveback, Intervention, Job, Lang, Media, MetalWork, Production,
PublicProcurement, Rating, Seo, Signature, plus AsdSoci/Inventory/Thermo (all
three landed on the exact same message "fix: drop Platform
GatesPageNavigation dependency, extend XotBasePage plainly" within seconds of
each other — 22:22:32 / 22:22:46 / 22:22:52 — strongly suggesting a batch
process hit them near-simultaneously; the same commit subject also exists
correctly, with full history intact, in `Wts` at 22:22:56, showing the
content itself is a legitimate cross-module fix that got applied consistently
— it's the **history destruction**, not this particular fix, that is the
problem).

## Confirmed-NOT-affected (real, long history, spot-checked)

`Xot` (91), `UI` (1598), `Notify` (26), `Timber` (20), `Wts` (23),
`WorkOrder` (17), `Vehicle` (14), `WhatsApp` (10). `Compliance` (2), `HR` (2),
`Platform` (2) are low but their latest commits are legitimate-looking
multi-line docs commits authored by other sessions today after 22:40 — plausibly
untouched, but not exhaustively verified; worth a second look. `User` shows
an unclear/empty rev-list — this module already had a separately-documented,
pre-existing remote corruption issue (see second-brain
`phpstan-user-standdown-already-done-repo-corrupt`), not necessarily the same
incident.

**This list is a snapshot from one scan, not an exhaustive audit** — scope
verification (especially remote-side, via `git ls-remote`, not just local
`rev-list`) for every module was out of scope for the task this session was
actually assigned. Treat the module list above as "at least this many", not
"exactly this many".

## What this session did in response

1. Did **not** attempt any recovery (`reset --hard`, force-push of old
   history, `checkout` of old commits) — explicitly forbidden by project
   rules, and a decision this size needs the repo owner, not an autonomous
   agent.
2. Did **not** run `git gc` or anything that prunes unreachable objects, in
   either affected repo touched this session (Quotation, Signage) — the
   pre-incident history (`5f83eb2`/`352d16b`/... for Quotation, similarly for
   Signage back through `28d7bd4`) should still be present as loose objects
   in *this specific local clone's* object store via reflog, at least until
   an automatic gc threshold is hit. **This is a time-sensitive recovery
   window** — the longer this clone keeps running and creating objects
   normally, the more likely git's automatic gc silently prunes them.
3. Verified the actual PHPStan-relevant content (the constant type fixes this
   session was assigned) was already present in the new orphan commits for
   both Quotation and Signage — the task's functional goal was not lost, only
   the commit history/audit-trail was.
4. Added one small, forward-only cleanup commit on top of the new tip in each
   of the two modules this session touched, removing the stray `.lock` files
   that got swept in (`bb7fc16` Quotation, `7bfbc56` Signage) — pushed
   normally (fast-forward, no force).
5. Is not fixing this in any other module — out of assigned scope, and each
   fix risks compounding the mess without owner sign-off on a recovery
   strategy.

## Recommended recovery path (for the repo owner, not to be executed
autonomously by an agent)

For each affected module, in a clone that still has the pre-incident history
reachable (check `git reflog` locally on whichever machine/session made the
bad commit, or any other clone/CI runner that fetched before the force-push,
or GitHub's own dangling-commit retention window):

1. Identify the last good commit (for Quotation: `5f83eb2`; for Signage: the
   commit immediately before whatever orphan-checkout step ran, visible in
   that clone's `git reflog`).
2. Decide how to reconcile it with whatever *legitimate* work happened to
   land in the orphan commit (e.g. this session's constant-type fixes were
   already correct and present in the orphan snapshot — a manual
   three-way reconciliation, not a blind revert, is needed so nothing new is
   lost either).
3. Only the owner should force-push a corrected ref back to `dev` — this is
   explicitly the one case where a force-push is the right tool, and
   explicitly the one case no autonomous agent session should do it
   unsupervised.
4. Until that happens, anyone continuing work on these modules should know
   their `git log` is not trustworthy for anything before 2026-09-07
   ~22:22-22:47 and should avoid running `git gc --aggressive --prune=now`
   locally in case their clone is one of the ones still holding the old
   objects.

## Why this matters beyond git hygiene

Dozens of BMAD story files and `docs/chat/` entries from today reference
specific commit hashes (`352d16b`, `28d7bd4`, `cea75a6`, `7a47b9a`, `aa8ad30`,
`593509a`, etc.) as evidence of completed, verified work. Those hashes are
now unreachable from the branch tip in the affected modules' remotes. The
*content* those commits produced is very likely still present in the
respective orphan snapshot (since the snapshot is "whatever was on disk" at
the time, and the fixes had already landed on disk), but the **provenance**
— who did what, when, and in what order — is gone unless recovered per
above.
