---
title: "Task: Spostamento Widget Violante"
type: concept
tags: [spostamento, widget, violante]
created: 2026-07-14
updated: 2026-07-14
qmd: "spostamento-widget-violante task: spostamento widget violante"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-user-management-system.md"
  - "./audit-dipendenze-user.md"
  - "./auditipendenze-user.md"
  - "./aumentare-copertura-test-user.md"
  - "./fix-doc-merge-markers.md"
  - "./fixoc-merge-kers.md"
  - "./query-optimization-user.md"
  - "./tasks-index.md"
---

# Task: Spostamento Widget Violante

**Modulo**: User  
**Fase**: 1 - Correzione Violazioni Architetturali  
**Priorità**: Critica  
**Stima**: 2-3 ore

## Obiettivo

Spostare `UserTypeRegistrationsChartWidget` dal modulo User al modulo appropriato (es. ExternalProject). User non può dipendere da moduli business specifici.

## Sottotask

- [ ] Identificare widget `UserTypeRegistrationsChartWidget` e sue dipendenze
- [ ] Analizzare dove collocarlo (ExternalProject o altro modulo)
- [ ] Spostare widget e aggiornare namespace
- [ ] Rimuovere file originale da User
- [ ] Verificare con script controllo dipendenze
- [ ] Test di regressione
- [ ] Aggiornare documentazione

## Dipendenze

Nessuna.

## Collegamenti

- [Roadmap User](../roadmap.md)
- [Indice task User](tasks-index.md)
- [Modular Architecture Dependency Rules](../../cms/docs/modular-architecture-dependency-rules.md)
