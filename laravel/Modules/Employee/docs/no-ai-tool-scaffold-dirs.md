---
title: No AI/tool scaffold directories in module tree
---

# Perché queste cartelle non devono esistere qui

Regola canonica: [module-theme-root-cleanup.md](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

Vietate in questo modulo (e ignorate nel `.gitignore`): `_docs/`, `scripts/`, `bashscripts/`, `docs/archive|archived|legacy|workbench/`, `.circleci/`, `.claude-audit/`, `tests/.claude-audit/`, `_bmad-output/`, `test-results/`, `.devcontainer/`, `.kilocode/`, `.kiro/`, `.ralph/`, più le config IDE `.vscode/`, `.cursor/`, `.windsurf/`.

Alla verifica del 2026-07-16 la root di Employee era già pulita: nessuna di queste cartelle presente. Questo documento serve a fissare il *perché* e a evitare che tornino.

## Perché continuano a ricomparire

Il modulo vive come repo Git indipendente (submodule del monorepo). Ogni tool che gira nella sua root non "sa" di essere dentro un monorepo con convenzioni proprie, e scrive lì il suo scaffold locale:

- **AI agent / skill** → `.kiro/`, `.claude-audit/`, `.ralph/`, `_bmad-output/` (scratch space e stato di sessione).
- **CI template copiati modulo-per-modulo** → `.circleci/`, `test-results/`.
- **IDE** → `.vscode/`, `.cursor/`, `.windsurf/`, `.devcontainer/` (config per-sviluppatore, non condivisibile).
- **Script one-off** → `scripts/`, `bashscripts/` duplicano tooling che esiste già alla root del monorepo (`bashscripts/`, `bashscripts/tools/`).
- **Doc legacy** → `docs/archive|legacy|workbench/` sono neve accumulata: copie superate della conoscenza che vive nella wiki.

## Lo zen: una casa sola per ogni categoria

La radice pulita non è pignoleria: è entropia tenuta bassa. Ogni categoria di contenuto ha *un solo* posto canonico:

| Contenuto | Casa canonica |
|---|---|
| Conoscenza riusabile | `docs/wiki/` del monorepo |
| Tooling / script | `bashscripts/` + `bashscripts/tools/` alla root del monorepo |
| Artefatti generati | `build/` |
| Config IDE / tool locale | fuori dal tracking (`.gitignore`) |

Un secondo posto per la stessa cosa è un invito alla divergenza. Se un tool rigenera la cartella, il `.gitignore` aggiornato la tiene fuori dal versioning invece di costringere a ripulirla ogni sessione. La regola è preventiva, non solo curativa.
