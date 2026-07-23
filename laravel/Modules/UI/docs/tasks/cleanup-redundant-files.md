---
title: "Task: Cleanup Redundant Files (UI Module)"
type: concept
tags: [cleanup, redundant, files]
created: 2026-07-14
updated: 2026-07-14
qmd: "cleanup-redundant-files task: cleanup redundant files (ui module)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./001-design-system-components.md"
  - "./filament-v5-alignment.md"
  - "./increase-test-coverage.md"
  - "./refactor-complex-components.md"
  - "./tasks-index.md"
  - "./ui-cleanup-docs.md"
  - "./ui-filament-v5.md"
---

# Task: Cleanup Redundant Files (UI Module)

## 📋 Obiettivo
Ripulire il modulo UI da file di backup, duplicati e file temporanei che creano rumore e potenziali conflitti.

## 🚨 Problemi Identificati
- File con estensione `.bak`, `.old`, `.disabled`, `.to_geo`.
- File che differiscono solo per il case (es. `TimeclockWidget.php` vs `TimeClockWidget.php`).
- Documentazione duplicata o obsoleta in `docs/archived/`.

## ✅ Checklist
- [ ] Identificare tutti i file `.bak`, `.old`, `.disabled`, `.to_geo` nel modulo UI.
- [ ] Verificare se il contenuto di questi file è già presente nelle versioni attive.
- [ ] Rimuovere i file ridondanti.
- [ ] Identificare conflitti di naming case-sensitive tramite script.
- [ ] Risolvere i conflitti mantenendo le versioni conformi a PascalCase.
- [ ] Verificare che il sistema continui a funzionare dopo la pulizia.

## 🔗 Riferimenti
- [Roadmap UI](../roadmap.md)
- [Filosofia UI](../filosofia-modulo-ui.md)
