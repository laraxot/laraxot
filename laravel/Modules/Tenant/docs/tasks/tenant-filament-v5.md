---
title: "Task: Tenant Filament v5 Alignment (Clusters)"
module: "Tenant"
type: concept
tags: [tenant, filament, v5]
created: 2026-07-14
updated: 2026-07-14
qmd: "tenant filament v5"
related:
  - "./phpstan-corrections-january.md"
---
# Task: Tenant Filament v5 Alignment (Clusters)

## 📋 Obiettivo
Riorganizzare le risorse di gestione dei tenant in un Cluster dedicato per facilitare il lavoro del SuperAdmin.

## 🏗️ Struttura Proposta
- **TenancyCluster**:
    - **TenantResource**: Gestione anagrafica e domini dei tenant.
    - **TenantConfigResource**: Configurazione dinamica dei permessi e moduli attivi.
    - **TenantStatusWidget**: Dashboard di salute dei vari database/tenants.

## ✅ Checklist
- [ ] Registrazione del `TenancyCluster`.
- [ ] Spostamento delle risorse `TenantResource` nel cluster.
- [ ] Ottimizzazione della query di caricamento della lista Tenants per grandi volumi.

## 🔗 Riferimenti
- [Roadmap Tenant](../roadmap.md)
