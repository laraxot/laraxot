# Module Root Hygiene — Why the Cms Module Root Stays Clean

Extends the canonical rule: [`docs/wiki/rules/module-theme-root-cleanup.md`](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

## The rule in one line

No scaffold/scratch directories in the module tree. Forbidden:
`_docs/`, `scripts/`, `bashscripts/`, `docs/archive/`, `docs/archived/`,
`docs/legacy/`, `docs/workbench/`, `.circleci/`, `.claude-audit/`,
`tests/.claude-audit/`, `_bmad-output/`, `test-results/`, `.devcontainer/`,
`.kilocode/`, `.kiro/`, `.ralph/`.

## Why these folders keep reappearing

Cms is the clearest case study in this group — every failure mode is visible here:

- **Dead CI config that outlived its tooling.** `.circleci/config.yml` was a
  *zero-byte* file. The module long ago moved to GitHub Actions (there are 20+
  workflows under `.github/workflows/`), yet the empty CircleCI husk lingered
  because nobody deletes what nobody reads.
- **Broken scaffold scripts, copied and never fixed.** `bashscripts/` held
  `deploy.sh`, `organize.sh`, `organize_bashscripts.sh`, `update.sh`,
  `composer_init.sh` — all generic templates still containing literal
  `<nome progetto>` placeholders and `/var/www/html/...` paths that were never
  wired to this module. `organize.sh` and `organize_bashscripts.sh` were even
  byte-identical duplicates. This is template drift, not tooling.
- **AI/audit artifacts written next to code.** `.claude-audit/` and
  `tests/.claude-audit/` held generated `audit-report.html/md` — machine output
  that belongs in an ephemeral, git-ignored location, never committed.
- **"Archive instead of delete" reflex.** `docs/archive/` accumulated 131 stale
  files. Git history already preserves every past version; a committed archive is
  just noise that hides the living docs.
- **Copy-paste bootstrapping.** `scripts/ci/contributor-lines-report.mjs` was the
  same file shipped byte-for-byte into AI, Blog, and Comment.

## The real need — and its proper home

The underlying needs are legitimate; the module root is just the wrong home:

| Real need | Proper home |
|---|---|
| CI configuration | one workflow suite under `.github/workflows/` (not `.circleci/`) |
| CI helper scripts | `.github/ci/`, referenced from the workflow |
| Reusable shell/service tooling | repo-root `bashscripts/tools/` (real, working scripts only) |
| Historical versions of a doc | git history (`git log --follow <file>`) |
| Agent/audit output | ephemeral git-ignored temp dir, never committed |
| Personal IDE/devcontainer setup | developer's machine + `.gitignore` |

### What was done (2026-07-16)

- **Migrated:** `scripts/ci/contributor-lines-report.mjs` → `.github/ci/`, with
  `.github/workflows/contributor-lines-report.yml` updated to the new path.
- **Deleted (no unique value):** empty `.circleci/config.yml`; the placeholder
  `bashscripts/` templates; `docs/archive/` (preserved in git history);
  `tests/.claude-audit/` report artifacts. Nothing reusable was lost.

## The zen of a clean root

A module root should read like a table of contents: `app/`, `config/`,
`database/`, `resources/`, `routes/`, `tests/`, `docs/`, `composer.json`,
`README.md`. A newcomer scanning it should learn *what the module is*, not *what
tools happened to run over it, once, years ago*. The `.gitignore` now blocks every
forbidden pattern, so the folders cannot silently return. When a tool insists on a
scratch directory, point it outside the module tree — the answer is never to commit
the mess.
