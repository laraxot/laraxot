---
title: "Task: Notify Filament v5 Alignment (Clusters)"
type: concept
tags: [filament, notify]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-v5-notify task: notify filament v5 alignment (clusters)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-notification-system.md"
  - "./cleanup-notify-docs.md"
  - "./notification-system.md"
  - "./notify-cleanup-docs.md"
  - "./notify-filament-v5.md"
  - "./notify-test-coverage.md"
  - "./tasks-index.md"
---

# Task: Notify Filament v5 Alignment (Clusters)

## 📋 Obiettivo
Riorganizzare le risorse del modulo Notify in Clusters per migliorare la navigazione e la gestione nel nuovo Admin Panel di Filament v5.

## 🏗️ Struttura Cluster Proposta
- **CommunicationsCluster**: Dashboard, Logs, Active Notifications.
- **TemplatesCluster**: Email Templates, SMS Templates, Seasonal Templates.
- **SettingsCluster**: Provider Configurations (SMTP, Twilio/Netfun, Firebase).

## ✅ Checklist
- [ ] Definizione delle classi Cluster in `app/Filament/Clusters/`.
- [ ] Spostamento delle risorse esistenti nei rispettivi Cluster.
- [ ] Aggiornamento dei link nel `00-index.md` per riflettere la nuova organizzazione UI.
- [ ] Verifica che le azioni bulk continuino a funzionare nella nuova struttura.

## 🔗 Riferimenti
- [Roadmap Notify](../roadmap.md)
