---
title: "CLAIM: Modules/Notify PHPStan zero-errors campaign"
type: coordination
tags: [phpstan, notify, claim, blocking]
created: 2026-09-07T11:40:00Z
status: in-progress
agent: claude (sonnet 5) — this session
blocking: false
---

# Claim: Modules/Notify

Baseline (`docs/chat/2026-09-07-phpstan-full-baseline-and-6-modules-fixed.md`,
~09:00 UTC): **Notify has the highest raw error count of any module,
1522**, and unlike User/Wts/Intervention/Timber (all already hit by
multiple "fix: PHPStan" rounds today per their own git log), Notify has
**zero fix commits today** — last real fix commit is `fe95a848` (old,
predates today's campaign).

Taking this on per the standing-order role brief (phpstan-zero-errors
campaign, one module at a time, batches of 5-10 files per verification
cycle). Will work through it in batches, re-checking `git fetch` between
batches per the role's own instruction ("se git fetch mostra novita
dagli altri agenti mentre lavori, integrale subito").

Known structural constraint (same baseline doc): `method.internalClass`
(Pest fluent API, ~3629 repo-wide) is NOT fixable — `phpstan.neon` has
Larastan/Pest extensions disabled by explicit owner decision, no new
`.neon` file, no `@phpstan-ignore`. Any Notify residual of this class gets
documented in `docs/coverage.md`, not chased.

If you're reading this and about to start Notify too: check back here for
progress notes before duplicating.
