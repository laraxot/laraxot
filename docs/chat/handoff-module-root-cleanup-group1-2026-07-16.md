# Handoff — Module Root Cleanup (Group 1: Activity, AI, Blog, Cms, Comment)

Date: 2026-07-16
Scope: remove forbidden scaffold/scratch folders from module roots, harden each
module `.gitignore`, and document the "why" per the canonical rule
[`docs/wiki/rules/module-theme-root-cleanup.md`](../wiki/rules/module-theme-root-cleanup.md).

## Repo topology discovered

- **Activity** and **Cms** are independent git submodules (own remotes:
  `module_activity_fila5`, `module_cms_fila5`), branch `dev` tracking `laraxot`.
- **AI**, **Blog**, **Comment** are **not** submodules — they live inside the
  parent repo `base_techplanner_fila5` (branch `dev`, remote `origin`). Their
  commits landed in the parent repo.

All work was staged **surgically** (explicit paths) because every module also had
unrelated in-flight changes from other agents (the app/Support → QueueableAction
refactor). `git add -A` was deliberately avoided.

## Per-module result

### Activity (submodule → pushed to laraxot/dev, commit 234b3177)
- **Found/removed:** `docs/archive/` (86 stale `.old.md`), `bashscripts/`
  (generic `composer_init.sh` + scaffold docs), `scripts/`. All were already
  deleted in the working tree by a prior interrupted run; this commit records the
  deletions.
- **Migrated:** nothing unique — `composer_init.sh` is generic boilerplate,
  identical across modules and unreferenced.
- **.gitignore:** kept the existing "AI/TOOL SCAFFOLD" block, deduped.
- **Doc:** `docs/module-root-hygiene.md`.

### AI (parent repo → pushed to origin/dev, commit 1b852d53 + follow-up)
- **Found:** `scripts/` containing real, referenced content.
- **Migrated:**
  - `scripts/fine_tuning.py` + `scripts/test_fine_tuning.py` (a real Python/Flask
    fine-tuning sidecar backing `app/Filament/Pages/FineTuning.php` and documented
    in `docs/fine-tuning.md`) → repo-root `bashscripts/tools/ai-fine-tuning/` (the
    designated local toolbox; note repo-root `bashscripts/` is git-ignored, but the
    files' full history is preserved in the parent repo). Added a README there and
    updated `docs/fine-tuning.md` to point to the new path.
  - `scripts/ci/contributor-lines-report.mjs` → `.github/ci/`, and
    `.github/workflows/contributor-lines-report.yml` updated. (`.github/scripts/`
    was avoided because the `scripts/` ignore pattern also matches it.)
- **.gitignore:** rewritten into clear sections; folded in `scripts/.gitignore`
  patterns (`venv/`, `docs/phpstan/`, `_docs/`, `agentdb.rvf`); added scaffold block.
- **Doc:** `docs/module-root-hygiene.md`.

### Blog (parent repo → pushed to origin/dev, commit 0e74624e)
- **Found:** `scripts/ci/contributor-lines-report.mjs` (byte-identical to AI/Comment).
- **Migrated:** → `.github/ci/`, workflow path updated.
- **.gitignore:** rewritten into sections + scaffold block.
- **Doc:** `docs/module-root-hygiene.md`.

### Cms (submodule → pushed to laraxot/dev, commit dff00ec)
- **Found/removed (no unique value):**
  - `.circleci/config.yml` — **zero bytes**; module already uses a 20+ workflow
    `.github/workflows/` suite. Dead legacy.
  - `bashscripts/` — placeholder templates with literal `<nome progetto>` paths;
    `organize.sh` and `organize_bashscripts.sh` were byte-identical duplicates.
  - `docs/archive/` (131 stale files; git history is the archive).
  - `tests/.claude-audit/` audit-report artifacts.
- **Migrated:** `scripts/ci/contributor-lines-report.mjs` → `.github/ci/`, workflow
  path updated.
- **.gitignore:** consolidated/deduped the large existing file, added scaffold block.
- **Doc:** `docs/module-root-hygiene.md`.

### Comment (parent repo → pushed to origin/dev, commit 517f4b8d)
- **Found:** `scripts/ci/contributor-lines-report.mjs` (byte-identical to AI/Blog).
- **Migrated:** → `.github/ci/`, workflow path updated.
- **.gitignore:** rewritten into sections + scaffold block.
- **Doc:** `docs/module-root-hygiene.md`.

## The shared CI helper — a note

`contributor-lines-report.mjs` (blob `24aab386`, 227 lines, no npm deps) was
present byte-for-byte in AI, Blog, Cms, and Comment — a clear template-drift
artifact. It is actively wired into each module's `contributor-lines-report.yml`
workflow, so it is **real infrastructure**, not scratch. Rather than delete it, it
was moved to `.github/ci/` in each module and the workflow `run:` step re-pointed.
(A future improvement would be to host a single copy at the org level and stop
copying it per module.)

## Philosophy (written into every module's `docs/module-root-hygiene.md`)

These folders reappear because tools **deposit** them: AI/audit runs carve scratch
space next to code (`.claude-audit/`, `_bmad-output/`, `.ralph/`), new modules are
cloned from siblings and inherit cruft (`.circleci/`, `scripts/`), stale docs get
"archived" instead of trusting git, and personal IDE config (`.devcontainer/`)
leaks in. Each need has a proper home elsewhere (repo-root tooling, `.github/ci/`,
git history, an ignored temp dir). A module root should read like a table of
contents describing *what the module is*, not *what tools ran over it*. Every
module `.gitignore` now blocks all forbidden patterns so they cannot silently return.

## Verification

- No forbidden folders remain on disk in any of the 5 modules (node_modules excluded).
- PHPStan sanity: run on Activity; the 23 reported errors are pre-existing and
  belong to the unrelated in-flight refactor and test fixtures — the hygiene work
  touched zero PHP, so it cannot have introduced them.
- All 5 commits pushed (Activity + Cms to their submodule `dev`; AI/Blog/Comment via
  the parent repo `origin/dev`).
