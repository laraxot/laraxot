---
title: Quality roadmap — AiAssistant
type: concept
tags: [aiassistant, quality, perfection, pest, phpstan]
created: 2026-08-31
updated: 2026-08-31
qmd: aiassistant quality roadmap perfection pest neuron
related:
  - ./import-status.md
  - ../../Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
  - ../../../docs/gestionale-technical-debt.md
---

# Quality roadmap — AiAssistant

## Stato perfezione (2026-08-31)

| Dimensione | Stato |
|------------|-------|
| PHPStan L10 | **OK** |
| Coesistenza con `AI` | **By design** (replica singolare) |
| Pest smoke | **Aperto** — suite non strutturata come GC core |
| Docs testing | **Manca** `tests-migration.md` |

---

## Azioni ordinate

1. Pest smoke minimo: lookup company + stream controller (già fixati in story 2.3).
2. Creare `tests-migration.md` quando si portano test SRC voice/lookup.
3. Non merge con modulo `AI` senza mandato BMAD.

[import-status.md](./import-status.md)
