---
title: "Task: Lang Filament v5 Alignment (Clusters)"
module: "Lang"
type: concept
tags: [phpstan, level10, fixes, 1]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan level10 fixes 1"
related:
  - "./italian-text-refined-audit-report.md"
---
# Task: Lang Filament v5 Alignment (Clusters)

## 📋 Obiettivo
Spostare la gestione delle traduzioni in un Cluster dedicato per migliorare la manutenibilità.

## 🏗️ Struttura Proposta
- **LanguageCluster**:
    - **LanguageResource**: Gestione delle lingue attive e configurazione.
    - **TranslationResource**: Interfaccia per la modifica dei file di traduzione.
    - **TranslationAuditResource**: Report sulle chiavi mancanti o obsolete.

## ✅ Checklist
- [ ] Definizione del `LanguageCluster`.
- [ ] Aggiornamento della navigazione per puntare al nuovo cluster.
- [ ] Ottimizzazione della vista "Edit Translation" per Filament v5.

## 🔗 Riferimenti
- [Roadmap Lang](../roadmap.md)
