---
title: "Module Root Cleanup — perché queste cartelle tornano"
module: "Tenant"
type: concept
tags: [hygiene, gitignore, ai-scaffold, module-root]
created: 2026-07-16
updated: 2026-07-16
related:
  - "../../../../docs/wiki/rules/module-theme-root-cleanup.md"
---

# Module Root Cleanup — Tenant

Estende la regola canonica [`module-theme-root-cleanup.md`](../../../../docs/wiki/rules/module-theme-root-cleanup.md).
Questo documento spiega **perché** cartelle come `docs/**/archive/`, `docs/**/legacy/`,
`scripts/`, `bashscripts/` continuano a ricomparire in questo modulo, e quale sia la
casa corretta per l'eventuale bisogno reale che le genera.

## Cosa è stato rimosso (2026-07-16)

`docs/it/archive/`, `docs/traits/archive/`, `docs/en/archive/`,
`docs/_integration/archive/`, `docs/it/config/archive/`.

Tutti contenevano stub quasi vuoti o **duplicati esatti** di documentazione già viva
nella cartella genitore (es. `docs/it/archive/project-configurations.md` era una copia di
`docs/it/project-configurations.md`; `docs/it/config/archive/morph-map.md` di
`docs/it/config/morph-map.md`). Nessun contenuto unico da migrare.

## Perché ricompaiono — le quattro cause

1. **Default dei tool AI**: un agente che "riorganizza" i doc tende a spostare la versione
   vecchia in una sottocartella `archive/` accanto invece di cancellarla (forward-only).
   Risultato: due risposte alla stessa domanda "qual è la versione giusta?".
2. **Scratch space degli agenti**: `scripts/`, `bashscripts/`, `_bmad-output/` nascono come
   spazio di lavoro temporaneo scritto nella root che l'agente vede — ignorando che quella
   root è un sottoalbero del monorepo con le sue convenzioni.
3. **Template CI copia-incolla**: `.circleci/` e simili arrivano da template importati.
4. **Leakage dell'IDE**: `.vscode/`, `.cursor/`, `.devcontainer/` sono config locali di
   sviluppatore che non vanno committate.

La causa strutturale comune: ogni modulo è un **repo Git indipendente** (multi-repo). Ogni
tool che gira dentro quel repo scrive la propria cache/scaffold nella root locale, senza
sapere che è un sottomodulo.

## Lo zen di una root pulita

Una sola fonte di verità per categoria:
- `docs/` per la conoscenza riusabile — **mai** copie `archive/`/`legacy/` parallele.
- La root `bashscripts/` del **monorepo** per l'automazione — mai una copia per modulo.
- `build/` per gli artefatti generati — mai `.claude-audit/`, `test-results/`.

Ogni duplicato è entropia, non struttura.

## Se il bisogno è reale

- Storia di un documento → è già in Git (`git log --follow`), non serve `archive/`.
- Script utile e riusabile → `bashscripts/tools/` alla root del monorepo.
- Nota di lavoro → `docs/wiki/` o una pagina viva in `docs/`, non uno stub in `archive/`.

## Boy scout rule

Quando trovi queste cartelle: cancella **e** aggiorna il `.gitignore` (sezione
`AI/TOOL SCAFFOLD`, con pattern `docs/**/archive/` ecc. per intercettare anche le
occorrenze annidate), deduplicando le righe già presenti. Così il tool che le rigenera
smette di inquinare il tracking a ogni sessione.
