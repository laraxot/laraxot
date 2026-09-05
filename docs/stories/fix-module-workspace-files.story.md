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

**Root cause (errore mio):** Ho trattato `.code-workspace` come file zombie (`git add -A` li cancellava se non tracciaati), ma il vero errore è nella convenzione di naming. Il template scritto nel prompt (`_module_<modulename>.code-workspace`) è incompleto: il nome deriva da `git remote -v` (es. `module_blog_fila5` → `_module_blog_fila5.code-workspace`), non dal nome della cartella. Quindi ho generato duplicati con naming errato (`_blog.code-workspace`, `_notify.code-workspace`, `_module_activity` e `_module_activity_fila5`) in 40+ moduli, e ho lasciato `Thermo` senza file.

**Fix:**
1. Creare `.code-workspace` per i 9 moduli mancanti (usando template da User)
2. Verificare che non vengano cancellati nei futuri sync (`git add -A` deve escludere o preservare)
3. Aggiornare il prompt di struttura file (`43-php-files-structure.md`) per includere questa regola

**Acceptance Criteria:**
- [ ] Tutti i moduli con `.code-workspace` esistente (o creato)
- [ ] Prompt 43-php-files-structure.md migliorato con regola workspace
- [ ] Nessuna cancellazione futura di `.code-workspace`
- [ ] Git sync completato per moduli mancanti