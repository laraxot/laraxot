<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
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

<<<<<<< HEAD
=======
>>>>>>> 60a2c9a9 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
# Task: Spostamento Widget Violante

**Modulo**: User  
**Fase**: 1 - Correzione Violazioni Architetturali  
**Priorità**: Critica  
**Stima**: 2-3 ore

## Obiettivo

<<<<<<< HEAD
<<<<<<< HEAD
Spostare `UserTypeRegistrationsChartWidget` dal modulo User al modulo appropriato (es. ExternalProject). User non può dipendere da moduli business specifici.
=======
Spostare `UserTypeRegistrationsChartWidget` dal modulo User al modulo appropriato (es. Quaeris). User non può dipendere da moduli business specifici.
>>>>>>> 60a2c9a9 (.)
=======
Spostare `UserTypeRegistrationsChartWidget` dal modulo User al modulo appropriato (es. Quaeris). User non può dipendere da moduli business specifici.
=======
Spostare `UserTypeRegistrationsChartWidget` dal modulo User al modulo appropriato (es. ExternalProject). User non può dipendere da moduli business specifici.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

## Sottotask

- [ ] Identificare widget `UserTypeRegistrationsChartWidget` e sue dipendenze
<<<<<<< HEAD
<<<<<<< HEAD
- [ ] Analizzare dove collocarlo (ExternalProject o altro modulo)
=======
- [ ] Analizzare dove collocarlo (Quaeris o altro modulo)
>>>>>>> 60a2c9a9 (.)
=======
- [ ] Analizzare dove collocarlo (Quaeris o altro modulo)
=======
- [ ] Analizzare dove collocarlo (ExternalProject o altro modulo)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
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
