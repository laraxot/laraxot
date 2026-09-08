---
title: "Task: User Filament v5 Alignment (Clusters)"
type: concept
tags: [user, filament, alignment]
created: 2026-07-14
updated: 2026-07-14
qmd: "user-filament-v5-alignment task: user filament v5 alignment (clusters)"
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
Utilizzare la nuova funzionalità "Clusters" di Filament v5 per organizzare le numerose risorse del modulo User in gruppi logici, migliorando la navigazione e l'esperienza utente nell'Admin Panel.

## 🏗️ Struttura Proposta
- **IdentityCluster**: User, Profile, AuthenticationLog.
- **AccessControlCluster**: Role, Permission.
- **ApiCluster**: Passport Clients, Tokens, OAuth resources.
- **OrganizationCluster**: Team, Tenant.

## ✅ Checklist
- [ ] Creazione delle classi Cluster in `app/Filament/Clusters/`.
- [ ] Assegnazione di ogni risorsa al rispettivo Cluster.
- [ ] Aggiornamento delle icone e dei label dei Cluster (utilizzando traduzioni automatiche).
- [ ] Verifica che i permessi (Spatie) funzionino correttamente con la nuova struttura a Cluster.
- [ ] Test di navigazione.

## 🔗 Riferimenti
- [Roadmap User](../roadmap.md)
- [Filament Clusters Documentation](https://filamentphp.com/docs/3.x/panels/clusters) <!-- Sostituire con v5 -->
