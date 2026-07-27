---
title: root hygiene modulo e tema
type: concept
module: Xot
tags: [module, theme, root, hygiene, workspace, lowercase, gitignore]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./module-filament-panel-triad.md
  - ../../../../Themes/docs/shared-components/module-theme-root-hygiene-religion.md
  - ../../../../../docs/wiki/memories/module-theme-root-hygiene.md
---

# Root hygiene — moduli e temi

Regole per `laravel/Modules/{M}/` e `laravel/Themes/{T}/` **solo depth 1**.

## 1. Cartelle — solo lowercase

Vietato in root: `Main_files/`, `Resources/`, `Config/`, `Helpers/`, …

| Legacy | Canon |
|--------|--------|
| `Main_files/` (tema) | `docs/Main_files/` |
| `Resources/` se esiste `resources/` | eliminare `Resources/` |
| `Config/` se esiste `config/` | eliminare `Config/` |

Il nome modulo/tema (`WorkOrder`, `Sixteen`) in PascalCase **non** è una violazione.

## 2. `.code-workspace` — esattamente uno

Derivato da repo Git (`gitmodules.ini` o `git remote get-url origin`):

```
theme_zero_fila5  →  _theme_zero.code-workspace
module_activity_fila5  →  _module_activity.code-workspace
```

Moduli solo monorepo (remote `base_*`): fallback `_module_{alias}` da `module.json`.

Fix: `bash bashscripts/tools/fix-module-theme-workspaces.sh`

## 3. IDE folders — vietate in root

`.cursor/`, `.devcontainer/`, `.vscode/`, `.windsurf/` — cancellare e ignorare in `.gitignore` del modulo/tema.

Fix: `bash bashscripts/tools/ensure-module-theme-ide-gitignore.sh`

## Audit

```bash
bash bashscripts/tools/audit-module-theme-root-hygiene.sh
bash bashscripts/tools/audit-module-root-hygiene.sh
```

## Incidente (2026-07-27)

- `Sixteen/Main_files`, `TwentyOne/Main_files` → spostati in `docs/Main_files/`
- `Job/Config`, `UI/Config` duplicati rimossi
- IDE folders rimosse da Activity, Job, Lang, Media, UI, Zero
- Workspace duplicati/errati normalizzati su 41 moduli/temi

**Bug strutturale negli script di audit/fix stessi**: sia
`audit-module-theme-root-hygiene.sh` sia `ensure-module-theme-ide-gitignore.sh`
richiedevano `theme.json` per processare un tema. `Themes/Barthelemy` e `Themes/Meetup`
(entrambi temi reali, già conformi, ma condivisi sul remote del monorepo — nessun
`theme.json`, nessun remote dedicato) risultavano quindi invisibili a entrambi gli
script — non "falliti", proprio mai controllati, silenziosamente. Corretto: rimosso il
requisito `theme.json` dal loop di discovery dei temi in entrambi gli script (basta
essere una directory sotto `Themes/`, esclusa `docs/`); aggiunto fallback finale su nome
directory lowercased (`theme_barthelemy`, `theme_meetup`) quando mancano sia remote
dedicato sia manifest. Verificato: `Checked: 43 | Failed: 0` (38 moduli + 5 temi — prima
solo 41 risultavano visibili all'audit).
