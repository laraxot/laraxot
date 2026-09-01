---
title: Quality roadmap — Quotation
type: concept
tags: [quotation, quality, perfection, pest, phpstan, media]
created: 2026-08-31
updated: 2026-08-31
qmd: quotation quality roadmap perfection pest media voice
related:
  - ./import-status.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
  - ../../../docs/gestionale-technical-debt.md
---

# Quality roadmap — Quotation

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 produzione | **OK** |
| Pest unit | **OK** |
| Pest feature | **~37 fail** bootstrap DB + **14 fail** media Spatie |
| Traduzioni voice | Ripristinate; ~13 call site senza `quotation::` prefix |

---

## Azioni ordinate

1. **D17** — tabelle `customers`, `products`, `roles`, `media` su connessione test.
2. **Ciclo media dedicato** — adattare test allegati a semantica Spatie (non modello SRC custom).
3. **D7** — PDF export solo se richiesto (`composer require` umano).
4. Normalizzare `__('quotations.*')` → `quotation::` nei file applicativi.
5. Aggiungere `tests-migration.md` canonico (oggi solo import-status + bugfix doc).

[import-status.md](./import-status.md) — elenco test rossi e cause.
