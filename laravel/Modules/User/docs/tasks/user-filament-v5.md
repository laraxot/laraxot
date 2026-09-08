---
title: "Task: User Filament v5 Alignment (Clusters)"
type: concept
tags: [user, filament]
created: 2026-07-14
updated: 2026-07-14
qmd: "user-filament-v5 task: user filament v5 alignment (clusters)"
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
  - "./spostamento-widget-violante.md"
---

# Task: User Filament v5 Alignment (Clusters)

## 📋 Obiettivo
Organizzare la gestione degli utenti e della sicurezza in Cluster dedicati per una navigazione più pulita.

## 🏗️ Struttura Proposta
- **IdentityCluster**:
    - **UserResource**: Gestione anagrafica utenti.
    - **RoleResource**: Gestione ruoli (Spatie).
    - **PermissionResource**: Gestione permessi granulari.
- **SecurityCluster**:
    - **OauthClientResource**: Gestione client Passport.
    - **AuditLogResource**: Storico accessi e modifiche.
    - **SessionResource**: Gestione sessioni attive.

## ✅ Checklist
- [ ] Registrazione dei due nuovi Cluster.
- [ ] Migrazione delle risorse esistenti.
- [ ] Aggiornamento dei link nelle dashboard.

## 🔗 Riferimenti
- [Roadmap User](../roadmap.md)
