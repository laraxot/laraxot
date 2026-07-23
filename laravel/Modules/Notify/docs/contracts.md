---
title: "Contracts"
type: concept
tags: [contracts]
created: 2026-07-14
updated: 2026-07-14
qmd: "contracts contracts"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Contracts

Questo documento contiene i collegamenti a tutte le interfacce principali del sistema.

## User Module

- [[HasTeamsContract]] - Interfaccia per la gestione dei team
  - Definisce i metodi per la gestione dei team
  - Utilizzata da [[BaseUser]]

- [[UserContract]] - Interfaccia base per gli utenti
  - Definisce i metodi base per gli utenti
  - Utilizzata da [[BaseUser]]

- [[HasTeamsAndUserContract]] - Interfaccia che combina HasTeamsContract e UserContract
  - Fornisce un'unica interfaccia per la gestione di team e utenti
  - Estende [[HasTeamsContract]] e [[UserContract]]

## Collegamenti Correlati

- [[Models]] - Modelli che implementano queste interfacce
- [[BaseUser]] - Implementazione principale delle interfacce 
