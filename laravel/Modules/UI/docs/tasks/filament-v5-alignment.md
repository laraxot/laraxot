---
title: "Task: Filament v5 Alignment (UI Module)"
type: concept
tags: [filament, alignment]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-v5-alignment task: filament v5 alignment (ui module)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-design-system-components.md"
  - "./cleanup-redundant-files.md"
  - "./increase-test-coverage.md"
  - "./refactor-complex-components.md"
  - "./tasks-index.md"
  - "./ui-cleanup-docs.md"
  - "./ui-filament-v5.md"
---

# Task: Filament v5 Alignment (UI Module)

## 📋 Obiettivo
Verificare e aggiornare tutti i componenti custom del modulo UI per garantire la piena compatibilità e sfruttare le nuove funzionalità di Filament v5.

## 🚨 Azioni Richieste
- Verificare i cambiamenti nelle classi base di Filament (Forms, Tables, Widgets).
- Aggiornare i componenti che usano metodi deprecati o rimossi in v5.
- Sfruttare le nuove API di v5 (es. nuove modalità di rendering, miglioramenti nelle azioni).
- Verificare il sistema di temi e asset con il nuovo sistema di build di v5.

## ✅ Checklist
- [ ] Audit dei 56 componenti Filament vs v5 docs.
    - [ ] Columns
    - [ ] Form Components
    - [ ] Widgets
    - [ ] Actions
- [ ] Aggiornamento di `TableLayoutEnum` per integrazione ottimale con v5.
- [ ] Verifica compatibilità con il nuovo sistema di notifiche e modal di v5.
- [ ] Test di regressione su tutti i moduli che consumano UI.

## 🔗 Riferimenti
- [Roadmap UI](../roadmap.md)
- [Upgrade Guide Filament v5](https://filamentphp.com/docs/3.x/upgrade-guide) <!-- Sostituire con link v5 quando disponibile -->
