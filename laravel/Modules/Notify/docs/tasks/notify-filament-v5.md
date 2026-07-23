---
title: "Task: Notify Filament v5 Alignment (Clusters)"
type: concept
tags: [notify, filament]
created: 2026-07-14
updated: 2026-07-14
qmd: "notify-filament-v5 task: notify filament v5 alignment (clusters)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-notification-system.md"
  - "./cleanup-notify-docs.md"
  - "./filament-v5-notify.md"
  - "./notification-system.md"
  - "./notify-cleanup-docs.md"
  - "./notify-test-coverage.md"
  - "./tasks-index.md"
---

# Task: Notify Filament v5 Alignment (Clusters)

## 📋 Obiettivo
Centralizzare la gestione delle comunicazioni in un Cluster dedicato per migliorare l'organizzazione in Filament v5.

## 🏗️ Struttura Proposta
- **CommunicationCluster**:
    - **MailTemplateResource**: Layout e contenuti email.
    - **NotificationLogResource**: Storico degli invii multi-canale.
    - **ChannelConfigResource**: Configurazione driver (NetFun, etc.).
    - **SeasonalAction**: Tool per l'invio di messaggi periodici.

## ✅ Checklist
- [ ] Registrazione del `CommunicationCluster`.
- [ ] Migrazione delle risorse esistenti nel nuovo raggruppamento.
- [ ] Testing dei permessi di accesso granulari per il cluster.

## 🔗 Riferimenti
- [Roadmap Notify](../roadmap.md)
