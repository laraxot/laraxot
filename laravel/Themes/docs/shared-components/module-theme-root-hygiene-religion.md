---
title: root hygiene moduli (bridge temi)
type: concept
tags: [theme, module, root, hygiene, workspace, shared-components]
updated: 2026-07-27
related:
  - ../../Modules/Xot/docs/wiki/concepts/module-theme-root-hygiene.md
  - ./runtime-config-religion-hub.md
  - ../gestionale-docs-index.md
---

# Root hygiene — cosa deve sapere chi lavora sui temi

I temi condividono le stesse regole root dei moduli (nwidart). Un agente che tocca `Themes/Sixteen/` deve rispettare:

1. **Niente cartelle maiuscole** in root (`Main_files` → `docs/Main_files`)
2. **Un solo** `.code-workspace` — es. `theme_sixteen_fila5` → `_theme_sixteen.code-workspace`
3. **Niente** `.cursor`/`.vscode`/`.windsurf`/`.devcontainer` in root — solo nel monorepo root

I temi **non** duplicano config IDE del monorepo dentro la propria root.

Audit: `bash bashscripts/tools/audit-module-theme-root-hygiene.sh Sixteen`

Canon: [module-theme-root-hygiene.md](../../Modules/Xot/docs/wiki/concepts/module-theme-root-hygiene.md)
