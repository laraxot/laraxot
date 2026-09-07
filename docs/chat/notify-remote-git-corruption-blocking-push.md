---
title: "URGENT: laraxot/module_notify_fila5 remote is missing a commit object — blocks ALL pushes"
type: incident
tags: [incident, urgent, notify, git, corruption, blocking]
created: 2026-09-07T12:35:00Z
status: blocked
agent: claude (sonnet 5) — this session
blocking: true
---

# `git push laraxot dev` fails on Modules/Notify — remote-side object corruption

Tried to push a clean, verified commit (`51a68e21`, "fix(notify):
Theme/EmailTemplate/NotificationType extend BaseModel") on top of the
current `laraxot/dev` tip (`a876ae18`, no divergence, plain fast-forward).
Push fails:

```
error: Could not read 2c641c73c5ab1282c044f3436ac2573cb73d28e0
fatal: revision walk setup failed
remote: fatal: early EOF
error: remote unpack failed: index-pack failed
```

## This is not just local corruption

`git fsck --full` shows the same missing object locally, referenced as a
parent of commit `84e1d920` (`chore(gitattributes): sync dal prototipo
canonico...`, 2026-09-06 21:51, authored by `Marco Xot`). That commit's
own message already flags it: `[graft: broken parent 2c641c73 missing
upstream, treated as root - local-only replace, not pushed]` — a prior
session already found this yesterday and worked around it locally with
`git replace` (visible in `git replace -l`), explicitly noting it as a
local-only fix, not pushed.

**Confirmed today it's also broken on the remote itself:**
`git fetch laraxot 2c641c73c5ab1282c044f3436ac2573cb73d28e0` (a direct,
targeted fetch for exactly that object) returns:

```
fatal: bad object 2c641c73c5ab1282c044f3436ac2573cb73d28e0
error: github.com:laraxot/module_notify_fila5.git did not send all
necessary objects
```

If the remote had the object, this fetch would have healed the local
clone (fetch is additive/safe, I tried it precisely because it can't make
things worse). It didn't — the object is genuinely gone from
`github.com:laraxot/module_notify_fila5.git`, not just from this local
checkout. Every clone of this repo is affected; this isn't specific to my
working tree.

## What I'm NOT doing

Not attempting any repair — this needs remote-side surgery (force-pushing
a corrected history, or restoring the missing object from another intact
clone/mirror if one exists) which is both outside what's safe for me to
do unilaterally and explicitly against this session's git constraints
(no force-push, no history rewriting). This needs the repo owner.

## Current state

My commit `51a68e21` (Theme/EmailTemplate/NotificationType `extends
BaseModel` fix, verified `phpstan analyse Modules/Notify` 0 errors) is
safe locally, just unpushed. Nothing is lost. Anyone else trying to push
to this repo right now will hit the same wall.

## For the owner

Likely fix: find any OTHER local clone of `module_notify_fila5` that
still has `2c641c73c5ab1282c044f3436ac2573cb73d28e0` (an earlier pull,
before whatever bad push/interrupt on 2026-09-06 evening dropped it), and
push that clone's objects to the remote to backfill the gap, or use
GitHub support if no clean clone exists anywhere. Until then, `git push`
to this repo will keep failing for everyone.
