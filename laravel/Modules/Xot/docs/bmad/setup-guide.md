---
title: "Xot — BMAD Setup Guide"
description: "Setup e configurazione BMAD per il modulo Xot"
module: "Xot"
alias: "xot"
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
---

# Xot — BMAD Setup Guide

## Scopo

Rendere ripetibile e verificabile l'uso del BMAD Method per il modulo Xot.

## Cosa è "BMAD" qui (Business Logic)

In questo modulo, BMAD serve a:
- **Stabilire le regole architetturali**: le classi base, le convenzioni, i pattern
- **Abilitare l'audit trail**: ogni modulo che estende Xot deve seguire le regole
- **Supportare la qualità**: PHPStan level 10, Pest, code review garantiscono qualità
- **Facilitare la manutenzione**: un fix in Xot si propaga a tutti i 46 moduli

## Struttura Directory (Canonical)

- **`_bmad/`**: moduli/agent/skills + configurazione
- **`_bmad-output/`**: artefatti generati (contesto, prd, architettura, ui spec, ecc.)
- **`docs/bmad/`**: questa documentazione

## Configurazione Lingua e Output

- **`_bmad/config.yaml`**: lingua output documenti + cartella output
- **`_bmad/config.user.yaml`**: preferenze utente (lingua comunicazione, nome)

## Verifica Minima ("Funziona")

La verifica pratica è: gli artefatti vanno dove devono andare, e le skill risultano invocabili.

- **skills disponibili**: cartella `_bmad/` presente e popolata
- **output**: la cartella `_bmad-output/` contiene almeno `project-context.md`
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
- [Project Context](../../_bmad-output/project-context.md)

---

*Xot · BMAD Setup Guide · data 2026-05-27*