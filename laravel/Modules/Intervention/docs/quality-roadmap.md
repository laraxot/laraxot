---
title: Quality roadmap — Intervention
type: concept
tags: [intervention, quality, perfection, pest, phpstan, uuid]
created: 2026-08-31
updated: 2026-08-31
qmd: intervention quality roadmap perfection pest uuid signature
related:
  - ./import-status.md
  - ./tests-migration.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Intervention

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest unit | **OK** (azioni pricing, minuti, materiali) |
| Pest feature | **Parziale** — D17 + test voice/PDF |
| UUID vs `int` | **Debito** — cast `(int)` su `performed_by_user_id`, firme |

---

## Azioni ordinate

1. **UUID pipeline** — allargare `StoreSignatureForModelAction` e `InterventionResource` (no cast a `int` su UUID).
2. **D17** — schema test per feature Filament/calendario.
3. Integrare `intervention-calendar.js` nella build Vite (TODO in import-status).
4. Voice/PDF feature: skip onesti fino a D7 soft-dep o fixture complete.

Debito firme: [import-status.md](./import-status.md) § “Debito registrato”.
