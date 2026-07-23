---
title: "No AI/tool scaffold directories in module tree — UI"
module: "UI"
type: concept
tags: [hygiene, gitignore, ai-scaffold, module-root]
created: 2026-07-16
updated: 2026-07-16
related:
  - "../../../../docs/wiki/rules/module-theme-root-cleanup.md"
---

# Perché queste cartelle non devono esistere qui

Estende la regola canonica [module-theme-root-cleanup.md — Rule 5](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

## Rimosse in questo modulo (2026-07-16)

- `_docs/` (24 file `.txt` — appunti sciolti, superati da `docs/`)
- `scripts/ci/` — script CI duplicati della root del monorepo
- `.claude-audit/` — cache di audit di un agente
- `docs/archive/` (151 file) e le archivi annidate `docs/filament/archive/`,
  `docs/components/{archive,legacy}/`, `docs/roadmap/{archive,legacy}/`,
  `docs/_integration/archive/`.

Tutte contenevano duplicati (spesso coppie `nome.md` + `nome-1.md`) di documentazione
già viva: es. `docs/_integration/archive/page-builder.md` → `docs/page-builder.md`,
`docs/roadmap/archive/theme-system.md` → `docs/roadmap/theme-system.md`. Nessun
contenuto unico da migrare.

Tutti i pattern sono stati aggiunti/consolidati nel `.gitignore` del modulo, con le
varianti annidate `docs/**/archive/` ecc. per intercettare anche le sottocartelle.

## Nota: `resources/views/components/blocks/stats/archived/`

Esiste ancora una cartella `archived/` di blocchi Blade non referenziati. **Non rimossa**:
è codice applicativo (non uno scaffold AI e non sotto `docs/`), e i blocchi del page
builder possono essere risolti dinamicamente per nome da config/DB. Va valutata a parte
prima di eliminarla — vedi handoff.

## Perché ricompaiono — le quattro cause

1. **Default dei tool AI**: la versione vecchia di un doc viene spostata in una `archive/`
   accanto invece di essere cancellata (la storia è già in Git).
2. **Scratch space degli agenti**: `.claude-audit/`, `_bmad-output/`, `scripts/` nascono
   come spazio di lavoro locale scritto nella root che l'agente vede.
3. **Template CI copia-incolla**: `scripts/ci/`, `.circleci/`.
4. **Leakage dell'IDE**: `.vscode/`, `.cursor/`, `.devcontainer/`.

Causa strutturale: questo modulo vive anche come **repo Git indipendente** (multi-repo);
ogni tool che gira in quella root ignora che è un sotto-albero del monorepo con le sue
convenzioni.

## Zen

Una sola fonte di verità per categoria: `docs/` per la conoscenza (mai `archive/`/`legacy/`
parallele), la root `bashscripts/` del monorepo per l'automazione, `build/` per gli
artefatti. Ogni duplicato è entropia, non struttura. Boy scout rule: cancella **e**
aggiorna il `.gitignore` (deduplicando) così il tool smette di rigenerarli nel tracking.
