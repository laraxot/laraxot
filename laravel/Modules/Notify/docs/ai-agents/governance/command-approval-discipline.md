---
title: "Command Approval Discipline"
type: concept
tags: [command, approval, discipline]
created: 2026-07-14
updated: 2026-07-14
qmd: "command-approval-discipline command approval discipline"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./reusable-components-and-indexes.md"
---

# Command Approval Discipline

> Indice: [./00-index-1.md](./00-index-1.md)
> Governance correlata: [./reusable-components-and-indexes.md](./reusable-components-and-indexes.md)

## Regola operativa

Se l'utente ha gia approvato in modo persistente un pattern di discovery o ha espresso esplicitamente fastidio per richieste ripetute, l'agente deve evitare nuove approvazioni per lo stesso scopo e preferire strumenti gia consentiti o strategie equivalenti non invasive.

## Applicazione pratica

- non ripetere richieste di allow per discovery innocua tipo ricerca file, listing o ispezione testo
- preferire `rg`, `cat`, strumenti MCP e script locali ai comandi che richiedono nuove approvazioni
- se un comando innocuo continua a generare attrito, cambiare strategia invece di chiedere ancora
- trattare le approvazioni utente come memoria persistente di progetto, non come contesto usa e getta
