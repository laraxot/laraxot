---
title: "Task: Query Optimization User"
type: concept
tags: [query, optimization, user]
created: 2026-07-14
updated: 2026-07-14
qmd: "query-optimization-user task: query optimization user"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-user-management-system.md"
  - "./audit-dipendenze-user.md"
  - "./auditipendenze-user.md"
  - "./aumentare-copertura-test-user.md"
  - "./fix-doc-merge-markers.md"
  - "./fixoc-merge-kers.md"
  - "./spostamento-widget-violante.md"
  - "./tasks-index.md"
---

# Task: Query Optimization User

**Modulo**: User  
**Fase**: 3 - Performance e Ottimizzazioni  
**Priorità**: Media  
**Stima**: 8-12 ore

## Obiettivo

Eliminare N+1 queries e ottimizzare le query per large datasets (User, Profile, Roles, Teams).

## Sottotask

- [ ] Analizzare query con Laravel Debugbar
- [ ] Aggiungere eager loading dove necessario
- [ ] Ottimizzare relazioni User → Profile → Roles
- [ ] Ottimizzare Team queries
- [ ] Benchmark performance prima/dopo

## Dipendenze

Fase 2 (Testing e qualità) completata.

## Collegamenti

- [Roadmap User](../roadmap.md)
- [Indice task User](tasks-index.md)
