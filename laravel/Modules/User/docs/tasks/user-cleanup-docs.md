---
title: "Task: User Docs Cleanup"
type: concept
tags: [user, cleanup, docs]
created: 2026-07-14
updated: 2026-07-14
qmd: "user-cleanup-docs task: user docs cleanup"
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

# Task: User Docs Cleanup

## 📋 Obiettivo
Riorganizzare la mastodontica cartella docs del modulo User (550+ file) che contiene anni di analisi sovrapposte.

## 🚨 Problemi Identificati
- Ripetizioni infinite di analisi (es. `logout-blade-analysis-1.md`, `logout-blade-analysis.md`, `logout-blade-corrected-analysis.md`).
- File duplicati per conflitti Git non risolti (`dry-kiss-analysis-conflict-018b09.md`).
- File temporanei e log di coverage enormi (`coverage_full.txt` da 600kb).

## ✅ Checklist
- [ ] Eliminazione di tutti i file `.txt` di log pesanti (se non necessari per auditing storico).
- [ ] Consolidamento delle 10+ guide sul logout in un unico "Logout Architecture Guide".
- [ ] Rimozione sistematica dei duplicati `-1.md` e `-2.md`.
- [ ] Spostamento massivo in `archive/` di analisi risalenti al 2024 o precedenti.

## 🔗 Riferimenti
- [Index Documentazione](../00-index.md)
