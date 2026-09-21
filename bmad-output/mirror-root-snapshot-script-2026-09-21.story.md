---
name: mirror-root-snapshot-script-2026-09-21
description: Build and run a script that mirrors already-synced nested module/theme repos into the root superproject, closing a near-repo-wide drift gap
metadata:
  type: story
  date: 2026-09-21
---

# Mirror root snapshot script

## BMAD phase

Build to Deploy (script written, dry-run verified, executed for real, pushed, self-documented).

## Context

Earlier in this session, five nested repos (Notify, User, Activity, Rating, Themes) were found clean and pushed to their own `laraxot` remote while the ROOT repo's tracked copy of the same paths was stale, and were mirrored to root by hand (`git add -A -- <path>` + commit + push, one commit per module). User asked to turn that manual routine into a script and place it in `bashscripts/git/subtrees/`.

## What was built

`bashscripts/git/subtrees/mirror_root_snapshot.sh`:
- Iterates every `path` in `gitmodules.ini`.
- Skips a path if: missing repo, wrong git toplevel, lock file present (`bashscripts/lock/check.sh`), nested repo mid-rebase/merge, nested repo dirty, or nested repo not `ahead=0/behind=0` vs its own `laraxot` remote (override with `--allow-unpushed`).
- Otherwise stages and commits ONLY that path at root (`git add -A -- <path>`, `git commit -m … -- <path>`), never a blanket `add -A`/`-a`.
- Pushes root once at the end (fast-forward only, no `--force`) if any commit was made.
- Flags: `--report-only`, `--only <name>`, `--no-push`, `--allow-unpushed`.

## Verification

1. `--report-only` dry run against all 51 paths: 44 `WOULD_MIRROR`, 2 `CLEAN` (Themes), 2 `LOCKED` (Activity, Rating — stale lock, ~768min old, respected not stolen), 2 `SKIP_DIRTY_NESTED` (Notify, bashscripts).
2. First real run hit a bug: `git commit -- "$path" -m "$msg"` — `--` ends option parsing so `-m` is read as a literal pathspec. Git refused safely (`pathspec '-m' did not match any file(s)`), nothing partially committed. Fixed to `git commit -m "$msg" -- "$path"`.
3. Second real run: 44 modules `MIRRORED`, root pushed `laraxot/dev 31015fa07..e08d820c5`. Same 2 locked + 2 dirty-skip as the dry run (consistent, no drift during the run beyond expected live-peer noise on Compliance/Rating doc files).
4. `git status --porcelain` at root afterward: only pre-existing, out-of-scope items remain (Notify's own known 1 dirty file, Rating's peer-owned live edit, unrelated untracked docs/scratch files from other agents) — none touched.
5. Script itself committed in `bashscripts`' own separate git repo (`60c103cbb`) and pushed, per [[feedback_bashscripts_own_git_repo]].

## Second brain

`project_root_mirror_gap_44_modules_fixed_2026_09_21.md` written, indexed in `MEMORY.md`.

## Follow-up (not done here)

- Re-run `mirror_root_snapshot.sh` once Activity/Rating's lock is confirmed genuinely stale or released, to close the last 2 paths.
- Notify's 1 dirty file in the nested repo was pre-existing and out of this story's scope.
