---
title: remote GitHub tema Zero — mai base mono
type: memory
tags: [github, git, remote, theme, zero, grave]
theme: Zero
created: 2026-07-27
updated: 2026-07-27
qmd: "theme Zero git remote theme_zero_fila5 never base_techplanner base_<nome progetto>"
qmd: "theme Zero git remote theme_zero_fila5 never base_project base_<nome progetto>"
related:
  - ../../code-quality-improvement-report.md
  - ../../../../../../docs/wiki/memories/module-github-remote-discipline.md
  - ../../../../../../docs/wiki/skills/module-theme-git-remote-resolve.md
id: zero-docs-wiki-memories-github-remote-theme-resolve
slug: github-remote-theme-resolve
description: "In un conflitto su URL basetechplanner vs base, entrambe le parti possono essere false. Non scegliere a caso: rieseguire git remote -v."
document_type: memory
category: git-workflow
status: active
issues: []
discussions: []
---

# Remote GitHub — tema Zero

```bash
cd laravel/Themes/Zero && git remote -v
# laraxot → git@github.com:laraxot/theme_zero_fila5.git
```

In un conflitto su URL `base_techplanner_*` vs `base_<nome progetto>_*`, *entrambe* le parti possono essere false. Non scegliere a caso: rieseguire `git remote -v`.
In un conflitto su URL `base_project_*` vs `base_<nome progetto>_*`, *entrambe* le parti possono essere false. Non scegliere a caso: rieseguire `git remote -v`.

Canon: [module-github-remote-discipline](../../../../../../docs/wiki/memories/module-github-remote-discipline.md) · skill [module-theme-git-remote-resolve](../../../../../../docs/wiki/skills/module-theme-git-remote-resolve.md).
