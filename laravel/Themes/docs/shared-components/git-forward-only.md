---
title: "Git forward-only — shared themes/modules"
type: rule
tags: [themes, git, forward-only, shared-components]
created: 2026-07-27
updated: 2026-07-27
qmd: "themes modules git forward-only never restore checkout reset revert"
related:
  - ../../../../docs/wiki/concepts/git-forward-only-discipline.md
  - ./xotbase-migration-religion.md
---

# Git forward-only (temi e moduli)

Quando lavori in `Modules/*` o `Themes/*`:

- **Studia** il passato con `git show` / `git log` (anche nel repo del modulo)
- **Mai** `checkout` / `restore` / `reset` / `revert` / rollback
- Fix = patch avanti + sync modulo (`module-git-sync-after-fix`) senza riscrivere storia

Canon: [git-forward-only-discipline](../../../../docs/wiki/concepts/git-forward-only-discipline.md)
