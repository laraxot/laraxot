---
title: "Task: Aumentare Copertura Test User"
type: concept
tags: [aumentare, copertura, test, user]
created: 2026-07-14
updated: 2026-07-14
qmd: "aumentare-copertura-test-user task: aumentare copertura test user"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-user-management-system.md"
  - "./audit-dipendenze-user.md"
  - "./auditipendenze-user.md"
  - "./fix-doc-merge-markers.md"
  - "./fixoc-merge-kers.md"
  - "./query-optimization-user.md"
  - "./spostamento-widget-violante.md"
  - "./tasks-index.md"
---

# Task: Aumentare Copertura Test User

**Modulo**: User  
**Fase**: 2 - Testing e Qualità  
**Priorità**: Media  
**Stima**: 10-15 ore

## Obiettivo

Portare la copertura test del modulo User da ~85% a > 95%.

## Sottotask

- [ ] Test unitari per tutti i Models
- [ ] Test feature per Actions
- [ ] Test integration per Resources Filament
- [ ] Test widget authentication (Login, Register, Logout)
- [ ] Test team management
- [ ] Test permission system

## Dipendenze

Fase 1 (Correzione violazioni architetturali) completata.

## Note

- Non usare RefreshDatabase; usare DatabaseTransactions o .env.testing con MySQL _test.
- Usare XotData::make()->getUserClass() per modello User nei test.

## Collegamenti

- [Roadmap User](../roadmap.md)
- [Indice task User](tasks-index.md)
- [Testing Guidelines](../testing-guidelines.md)
