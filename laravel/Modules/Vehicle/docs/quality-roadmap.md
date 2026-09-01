---
title: Quality roadmap — Vehicle
type: concept
tags: [vehicle, quality, perfection, pest, phpstan, media]
created: 2026-08-31
updated: 2026-08-31
qmd: vehicle quality roadmap perfection media document
related:
  - ./import-status.md
  - ../../../docs/debt-vehicle-document-media.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Quality roadmap — Vehicle

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Pest smoke | **Aperto** |
| Media FK | **Debito D2** — `vehicle_documents.media_id` vs Spatie `media` |

---

## Azioni ordinate

1. **D1/D2** prima di feature documenti mezzo ([debt-vehicle-document-media.md](../../../docs/debt-vehicle-document-media.md)).
2. Pest CRUD smoke dopo D17.
3. Non enable Document senza bridge media risolto.

[import-status.md](./import-status.md)
