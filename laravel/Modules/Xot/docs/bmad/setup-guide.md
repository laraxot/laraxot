---
<<<<<<< HEAD
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "setup guide"
issues: []
discussions: []
title: "Xot: BMAD Setup Guide"
=======
title: "Xot — BMAD Setup Guide"
>>>>>>> 77b1e2748 (first)
description: "Setup e configurazione BMAD per il modulo Xot"
module: "Xot"
alias: "xot"
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
<<<<<<< HEAD
status: draft
---

# Xot: BMAD Setup Guide
=======
---

# Xot — BMAD Setup Guide
>>>>>>> 77b1e2748 (first)

## Scopo

Rendere ripetibile e verificabile l'uso del BMAD Method per il modulo Xot.

## Cosa è "BMAD" qui (Business Logic)

In questo modulo, BMAD serve a:
- **Stabilire le regole architetturali**: le classi base, le convenzioni, i pattern
- **Abilitare l'audit trail**: ogni modulo che estende Xot deve seguire le regole
- **Supportare la qualità**: PHPStan level 10, Pest, code review garantiscono qualità
- **Facilitare la manutenzione**: un fix in Xot si propaga a tutti i 46 moduli

## Struttura Directory (Canonical)

<<<<<<< HEAD
- **`docs/bmad/`**: questa documentazione
- **`_bmad-output/`**: artefatti generati (repo root: `_bmad-output/implementation-artifacts/`)
- **`_bmad/`**: framework BMAD esterno (non incluso nel repo; installato via BMAD CLI)

## Configurazione Lingua e Output

- **`_bmad/config.yaml`**: lingua output documenti + cartella output (esterno al repo)
- **`_bmad/config.user.yaml`**: preferenze utente (lingua comunicazione, nome) (esterno al repo)
=======
- **`_bmad/`**: moduli/agent/skills + configurazione
- **`_bmad-output/`**: artefatti generati (contesto, prd, architettura, ui spec, ecc.)
- **`docs/bmad/`**: questa documentazione

## Configurazione Lingua e Output

- **`_bmad/config.yaml`**: lingua output documenti + cartella output
- **`_bmad/config.user.yaml`**: preferenze utente (lingua comunicazione, nome)
>>>>>>> 77b1e2748 (first)

## Verifica Minima ("Funziona")

La verifica pratica è: gli artefatti vanno dove devono andare, e le skill risultano invocabili.

<<<<<<< HEAD
- **skills disponibili**: BMAD CLI installato e configurato (`_bmad/` esterno al repo)
- **output**: la cartella `_bmad-output/` (repo root) contiene `implementation-artifacts/`
=======
- **skills disponibili**: cartella `_bmad/` presente e popolata
- **output**: la cartella `_bmad-output/` contiene almeno `project-context.md`
>>>>>>> 77b1e2748 (first)
- **lingua**: le config utente/progetto non si resettano dopo update

## Check Post-Update (Anti-Regressione)

Dopo un update, ricontrollare che non si sia "spaccata" la coerenza tra moduli:
- PHPStan level 10 passa per tutti i moduli che estendono Xot
- Test suite passa per tutti i moduli
- `XotBaseServiceProvider` boot() funziona per tutti i moduli

## Manutenzione (DRY + KISS)

- mantenere **un'unica fonte** per:
  - setup: questo file
  - comandi rapidi: `quick-reference.md`
- evitare duplicati in altre cartelle docs: negli altri indici usare link relativi a questi due file

## Vedi Anche

- [quick-reference](quick-reference.md)
<<<<<<< HEAD
=======
- [Project Context](../../_bmad-output/project-context.md)
>>>>>>> 77b1e2748 (first)

---

*Xot · BMAD Setup Guide · data 2026-05-27*