---
title: Quality roadmap — Signature
type: concept
tags: [signature, quality, perfection, pest, phpstan]
created: 2026-08-31
updated: 2026-08-31
qmd: signature quality roadmap perfection pest
related:
  - ./import-status.md
  - ../Intervention/docs/quality-roadmap.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Signature

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest | **Smoke aperto** |
| Accoppiamento Intervention | UUID `int` cast su firme pubbliche |

---

## Azioni ordinate

1. Allineare tipi firma a UUID (con Intervention `StoreSignatureForModelAction`).
2. Pest feature: report pubblico + storage firma dopo D17.
3. Test unit azioni signature isolate.

[import-status.md](./import-status.md)
