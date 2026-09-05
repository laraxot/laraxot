---
title: Preserve module .code-workspace files
epic: 3
story_id: 2
slug: fix-module-workspace-files
status: in_progress
created: 2026-09-05
modules_affected:
  - AsdSoci
  - Costing
  - Dds
  - Giveback
  - MetalWork
  - Signage
  - TestModule
  - TimberBilling
  - Wts
---

# Preserve Module .code-workspace Files

**Root cause (errore mio):** Durante il sync parallelo (`git add -A`) ho trattato `.code-workspace` come file zombie da cancellare, invece di verificarne l'esistenza per ogni modulo. Il `git add -A` cancellava quelli non tracciati, ma alcuni moduli non li avevano mai creati.

**Fix:**
1. Creare `.code-workspace` per i 9 moduli mancanti (usando template da User)
2. Verificare che non vengano cancellati nei futuri sync (`git add -A` deve escludere o preservare)
3. Aggiornare il prompt di struttura file (`43-php-files-structure.md`) per includere questa regola

**Acceptance Criteria:**
- [ ] Tutti i moduli con `.code-workspace` esistente (o creato)
- [ ] Prompt 43-php-files-structure.md migliorato con regola workspace
- [ ] Nessuna cancellazione futura di `.code-workspace`
- [ ] Git sync completato per moduli mancanti