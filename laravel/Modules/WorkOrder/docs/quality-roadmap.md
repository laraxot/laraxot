---
title: Quality roadmap — WorkOrder
type: concept
tags: [workorder, quality, perfection, pest, phpstan]
created: 2026-08-31
updated: 2026-08-31
qmd: workorder quality roadmap perfection pest phpstan
related:
  - ./import-status.md
  - ./tests-migration.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — WorkOrder

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest | **50 passed, 16 skipped** |
| Skip residue | Toolchain Pest/Livewire `mixed` (debito condiviso) |
| Export PDF | Soft-dep D7 se usato |

---

## Azioni ordinate

1. Chiudere **D17** per i 16 skipped legati a DB incompleto (se non solo `mixed`).
2. Verificare `DocumentExportBridge` con soft-dep PDF ([debt-soft-deps-export.md](../../../docs/debt-soft-deps-export.md)).
3. Job costing / margini: mantenere test `JobCostingUiTest` verdi dopo delta Quotation.

[import-status.md](./import-status.md)
