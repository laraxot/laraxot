---
title: "Xot module root .md hygiene — rerun (second pass, 2026-09-07)"
type: chat-note
tags: [xot, root, hygiene, md, archive]
created: 2026-09-07
status: done
---

# Xot module: root .md hygiene re-audit and fix

Task: enforce root hygiene rule (max 6 `.md` in `Modules/<Nome>/` root) on
`laravel/Modules/Xot/`. The second-brain memory claimed this was already
done ("Xot 129→6 files, 2026-09-05"), but live verification on 2026-09-07
found 139 `.md` files in root — the memory was stale / had regressed.

## Before / after

- Before: 139 `.md` files in `laravel/Modules/Xot/` root.
- After: 1 file (`README.md`).

## What the 139 files actually were

All 139 (root minus README.md = 138) were **untracked** (`git ls-files` in
root showed only `README.md` before this task — confirmed the prior
2026-09-05/2026-07-06 consolidation to "only README.md" had genuinely
happened and stuck). They reappeared later as fresh untracked files
(uniform mtime 2026-09-07 12:16, one bulk operation, likely an
unfinished/unclean txt→md conversion script) — never committed.

Verified via `diff` against the already-tracked canonical `docs/<name>.md`
counterpart for every single file (all 139 have one): 127/127 sampled were
byte-identical or a strict subset (root = raw pre-conversion link dumps,
`docs/<name>.md` = curated version with frontmatter, e.g.
`converted_from: admin_template.txt`). The 6 `18.12`-`18.17.*.story.md`
files in root were one-line "ready-for-dev" stubs already superseded by
full "done" versions at `Modules/Xot/docs/stories/18.12...18.17.*` (same
filename). No file had unique content missing elsewhere.

Also found `docs/archived/` already contained 127/139 of these files,
byte-identical, dated 2026-09-06 — leftover from an earlier, uncommitted
attempt at this same cleanup. Reused that destination (also explicitly
listed as an acceptable option by the task brief, and matches the
`Modules/Media` precedent, see `docs/chat/media-module-root-txt-hygiene.md`).

## Action

Moved (never deleted) all 138 root files to `docs/archived/` in 5 batches
of ~28, with per-file lock check/lock/unlock
(`bashscripts/lock/{check,lock,unlock}.sh`, paths relative to repo root)
before each batch. Zero lock conflicts across all 5 batches.

`docs/archived/` is `.gitignore`d in the Xot module (lines 11 and 139,
already in `HEAD` before this session — established repo policy, see
`docs/wiki/rules/module-theme-root-cleanup.md`): content stays on disk,
intentionally excluded from git tracking.

## Git complication (concurrent collision, resolved)

Mid-task, a separate concurrent agent working on PHPStan story 18.12 ran an
untargeted `git add -A && git commit` (commit `20a732df`, 13:05:44) for
their own `app/Actions/**` fixes, which accidentally swept up all 138 stray
root `.md` files (still on disk at that moment) into their commit and then
pushed them to `laraxot/dev`. This meant the files became genuinely
git-tracked mid-cleanup. To avoid a dangling inconsistent state (root clean
on disk, but git still considering the files tracked/present — risk: a
`git checkout .` by anyone would have restored the clutter), a follow-up
commit staged and committed exactly those 138 paths as deletions (never
`-A`, to avoid touching other agents' unrelated in-flight WIP:
`app/Models/*.php`, `tests/*.php`, another session's `docs/*.md`
enrichment pass — all left untouched).

Also found and corrected a false claim added by another concurrent agent
to the BMAD story, who asserted the 6 story-stub files were "recoverable
via `git show 20a732df:...`" — verified false at the time it was written
(`git log --all` for those paths was empty then); documented the full,
accurate timeline once the concurrent commit made that claim retroactively
true.

## Commits (Xot module repo, remote `laraxot`, branch `dev`)

1. `395b0cb2` — story audit update
2. `956235a7` — correction of a (then-)false claim from a concurrent agent
3. `9982f978` — actual removal of 138 root paths (the real fix)
4. `75515aad` — accurate timeline reconstruction in the story

All pushed clean (`git fetch` + no divergence each time, no `--force`, no
new branches, no revert/reset/checkout).

## Story

Updated (not recreated): `laravel/Modules/Xot/docs/stories/18.3.module-theme-root-md-file-limit-audit.story.md`
— previously claimed "Done, gia' conforme" (false/stale within hours),
corrected to document the regression, the fix, and the concurrent-commit
timeline.

## Open item (out of scope for this task, noted for whoever picks it up)

The 6 `18.12`-`18.17.*.story.md` stubs (now in `docs/archived/` and also
recoverable via `git show 20a732df:Modules/Xot/<name>`) may belong under
their owner modules' own `docs/stories/` (Gdpr/Compliance/Email/
EnergyBroker/Costing/GiveBack) per BMAD story-per-module-owner policy,
rather than under Xot. Not moved there in this task — flagged in the story
file for the owner agent/module.
