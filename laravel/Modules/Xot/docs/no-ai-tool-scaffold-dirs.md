---
title: "No AI/tool scaffold directories in module tree — Xot (base module)"
module: "Xot"
type: concept
tags: [hygiene, gitignore, ai-scaffold, module-root, base-module]
created: 2026-07-16
updated: 2026-07-16
related:
  - "../../../../docs/wiki/rules/module-theme-root-cleanup.md"
---

# Lo zen di una root di modulo pulita

Estende la regola canonica [module-theme-root-cleanup.md — Rule 5](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

Xot è il **modulo base**: le convenzioni che valgono qui vengono copiate da ogni altro
modulo. Per questo il documento è più esteso — deve fissare l'esempio.

## Rimosse in questo modulo (2026-07-16)

- Root scaffold: `_docs/`, `scripts/`, `bashscripts/`, `.claude-audit/`.
- Sotto `docs/`: `docs/scripts/`, `docs/bashscripts/`, `docs/actions/archive/`,
  `docs/filament/archive/`, `docs/consolidated/archive/` (602 file),
  `docs/consolidated/phpstan/archive/`.

Tutte le archivi erano **duplicati** di documentazione già viva (verificato: 40/40 file
campionati in `consolidated/archive/` avevano l'equivalente vivo). Essendo tutto
git-tracked, la storia resta recuperabile: **Git è l'archivio**, non una cartella
`archive/` parallela.

## Reperto: lo script che generava il problema

`docs/scripts/cleanup-docs.sh` era la **causa meccanica** del ripresentarsi di
`docs/archive/`. Faceva esattamente questo:

- creava `docs/archive/{historical,duplicates,uppercase}/`;
- ci spostava dentro i file con data nel nome, i duplicati `snake_case`/`kebab-case`
  e le versioni UPPERCASE.

In più aveva un path hardcoded verso un altro repo (`base_ptvx_fila5_mono`). È l'esempio
perfetto dell'anti-pattern: uno strumento che "pulisce" spostando invece di cancellare,
producendo proprio le cartelle vietate che poi qualcun altro deve ri-rimuovere. Rimosso —
non migrato. La deduplica corretta si fa con `git mv`/`git rm` (la storia basta), non
creando un cimitero `archive/`.

## Perché queste cartelle ricompaiono — le quattro cause

1. **Default dei tool AI**: un agente che "riorganizza" i doc sposta la versione vecchia in
   `archive/` accanto invece di cancellarla. Due risposte alla stessa domanda "qual è la
   versione giusta?".
2. **Scratch space degli agenti**: `.claude-audit/`, `_bmad-output/`, `.ralph/`,
   `test-results/`, `bashscripts/` nascono come spazio di lavoro locale scritto nella root
   che l'agente vede.
3. **Template CI copia-incolla**: `scripts/ci/`, `.circleci/` da template importati.
4. **Leakage dell'IDE**: `.vscode/`, `.cursor/`, `.devcontainer/`, config locali di
   sviluppatore.

**Causa strutturale comune**: ogni modulo è convertito in **repo Git indipendente**
(multi-repo). Ogni tool che gira dentro quel repo scrive la sua cache/scaffold nella root
locale, ignorando che quella root è un sotto-albero del monorepo con le sue convenzioni.

## Lo zen: una sola fonte di verità per categoria

| Categoria | Casa corretta | Anti-pattern |
|---|---|---|
| Conoscenza riusabile | `docs/` (una pagina viva) | `_docs/`, `docs/**/archive/`, `docs/**/legacy/` |
| Automazione/script | root `bashscripts/` del **monorepo** | `scripts/`, `bashscripts/` per modulo, `docs/scripts/` |
| Artefatti generati | `build/` | `.claude-audit/`, `test-results/`, `_bmad-output/` |
| Storia di un documento | `git log --follow` | cartelle `archive/`/`historical/` |
| Config IDE | locale (mai committata) | `.vscode/`, `.cursor/`, `.devcontainer/` |

Ogni duplicato è un secondo posto dove rispondere alla stessa domanda — **entropia, non
struttura**.

## Se il bisogno è reale

- Storia → già in Git, non serve `archive/`.
- Script utile e riusabile → `bashscripts/tools/` alla root del monorepo, non `docs/scripts/`.
- Nota di lavoro → una pagina viva in `docs/` o `docs/wiki/`, non uno stub in `archive/`.

## Boy scout rule

Quando trovi queste cartelle: **cancella e** aggiorna il `.gitignore` — sezione
`AI/TOOL SCAFFOLD`, con pattern annidati `docs/**/archive/`, `docs/**/legacy/`,
`docs/**/scripts/` ecc. per intercettare anche le occorrenze in sottocartelle — e
**deduplica** le righe già presenti. Così il tool che le rigenera smette di inquinare il
tracking a ogni sessione futura.
