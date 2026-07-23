---
title: "Task: Cleanup Lang Docs"
module: "Lang"
type: concept
tags: [phpstan, level10, fixes, 1]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan level10 fixes 1"
related:
  - "./italian-text-refined-audit-report.md"
---
# Task: Cleanup Lang Docs

## 📋 Obiettivo
Ripulire la directory `docs/` del modulo Lang, eliminando file duplicati, versioni obsolete e consolidando le informazioni sparse.

## 🚨 Problemi Identificati
- 96 file nella directory `archive/`.
- File duplicati con suffissi `-1.md`.
- File di integrazione sparsi che possono essere raggruppati.

## ✅ Checklist
- [ ] Identificare e rimuovere file con suffisso `-1.md` se già presenti nella versione principale.
- [ ] Sfoltire la directory `archive/` mantenendo solo ciò che ha valore storico reale.
- [ ] Unificare i piccoli file di "link" in un unico documento di riferimenti.
- [ ] Verificare che tutti i link in `index.md` siano corretti dopo la pulizia.

## 🔗 Riferimenti
- [Roadmap Lang](../roadmap.md)
