---
title: "Task: Lang Docs Cleanup"
module: "Lang"
type: concept
tags: [phpstan, level10, fixes, 1]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan level10 fixes 1"
related:
  - "./italian-text-refined-audit-report.md"
---
# Task: Lang Docs Cleanup

## 📋 Obiettivo
Riorganizzare la cartella docs del modulo Lang, rimuovendo 260+ file obsoleti e duplicati.

## 🚨 Problemi Identificati
- File duplicati con suffissi `-1.md`.
- File di log della ridenominazione (`docs_rename_log.txt`).
- Archivi non strutturati che confondono la ricerca.

## ✅ Checklist
- [ ] Spostare definitivamente i file rilevanti in `archive/` se non già fatto.
- [ ] Rimuovere file `.env.example` e `.gitignore` se duplicati o inutili nella cartella docs.
- [ ] Consolidare le guide all'integrazione di `mcamara/laravel-localization`.
- [ ] Uniformare il naming in lowercase per coerenza con il framework.

## 🔗 Riferimenti
- [Index Documentazione](../00-index.md)
