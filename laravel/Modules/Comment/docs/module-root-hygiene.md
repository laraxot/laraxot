# Module Root Hygiene — Why the Comment Module Root Stays Clean

Extends the canonical rule: [`docs/wiki/rules/module-theme-root-cleanup.md`](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

## The rule in one line

No scaffold/scratch directories in the module tree. Forbidden:
`_docs/`, `scripts/`, `bashscripts/`, `docs/archive/`, `docs/archived/`,
`docs/legacy/`, `docs/workbench/`, `.circleci/`, `.claude-audit/`,
`tests/.claude-audit/`, `_bmad-output/`, `test-results/`, `.devcontainer/`,
`.kilocode/`, `.kiro/`, `.ralph/`.

## Why these folders keep reappearing

They are not authored on purpose — they are *deposited* by tools and habits:

- **AI agents carving scratch space.** Audit runs (`.claude-audit/`), planning
  runs (`_bmad-output/`, `.ralph/`), and IDE-agent configs (`.kilocode/`,
  `.kiro/`) all want a place to dump intermediate artifacts, and "next to the
  code" is the path of least resistance.
- **Copy-paste module bootstrapping.** New modules are cloned from a sibling and
  inherit its cruft. The `scripts/ci/contributor-lines-report.mjs` in this module
  was the exact same file (byte-for-byte) shipped into AI and Blog — textbook
  template drift, not a Comment-specific need.
- **"Archive instead of delete" reflex.** Stale docs get moved to `docs/archive/`
  rather than trusting git history. Git already *is* the archive.
- **Local-only concerns leaking in.** `.devcontainer/` and per-developer tool
  configs are personal environment, not shared source.

## The real need — and its proper home

The underlying needs are legitimate; the module root is just the wrong home:

| Real need | Proper home |
|---|---|
| CI helper scripts | `.github/ci/`, referenced from the workflow |
| Reusable shell/service tooling | repo-root `bashscripts/tools/` |
| Historical versions of a doc | git history (`git log --follow <file>`) |
| Agent/audit scratch output | ephemeral git-ignored temp dir, never committed |
| Personal IDE/devcontainer setup | developer's machine + `.gitignore` |

### What was migrated (2026-07-16)

- `scripts/ci/contributor-lines-report.mjs` → `.github/ci/`, and
  `.github/workflows/contributor-lines-report.yml` updated to the new path. The
  Contributor Lines Report workflow keeps working; only the location moved out of
  the forbidden `scripts/` folder.

## The zen of a clean root

A module root should read like a table of contents: `app/`, `config/`,
`database/`, `resources/`, `routes/`, `tests/`, `docs/`, `composer.json`,
`README.md`. A newcomer scanning it should learn *what the module is*, not *what
tools happened to run over it*. The `.gitignore` now blocks every forbidden
pattern, so the folders cannot silently return. When a tool insists on a scratch
directory, point it outside the module tree — the answer is never to commit the mess.
