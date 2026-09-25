---
title: "Continuazione BMAD — Domani (post prompt 04)"
type: module-fix
scope: Rating
epic: "5"
updated_at: "2026-09-23"
status: ready-for-dev
related:
  - ./5.244-continuazione-domani-post-prompt-04-pack.story.md
  - ./5.242-close-ratingcontract-closures-and-align-index.story.md
  - ./5.243-sprint-architecture-post-ratingdata-residual.story.md
  - ./5.245-ratings-by-id-hoststub-harness-verify.story.md
  - ../../../IndennitaResponsabilita/docs/bmad/stories/5.235-ir-prompt-04-traceability-index.story.md
  - ../../../IndennitaResponsabilita/docs/prompts/04.md
---

# Rating — Continuazione Domani

**SSoT pack:** [`5.244-continuazione-domani-post-prompt-04-pack.story.md`](./5.244-continuazione-domani-post-prompt-04-pack.story.md)

## Stato (2026-09-23)

- Prompt `04.md` refactor RatingData / RatingContract: **coperto** (indice IR `5.235`)
- Codice: `RatingData::getXlsFields` corpo reale; helper su `RatingContract`; no `TraitName::static`

## Domani (ordine)

1. **P0** [`5.242`](./5.242-close-ratingcontract-closures-and-align-index.story.md) — chiudi formalità 5.240
2. **P1** [`5.243`](./5.243-sprint-architecture-post-ratingdata-residual.story.md) — sprint/architecture stale
3. **P2** [`5.245`](./5.245-ratings-by-id-hoststub-harness-verify.story.md) — 18.59 harness
4. Coordinare Xot [`5.247`](../../../Xot/docs/bmad/stories/5.247-tomorrow-pack-export-lazy-and-trigger-map.story.md) (lazy + TRIGGER_MAP)

## Non rifare

Mossa helper → RatingData, facade, backtrace-resolve, Assert RatingContract (già done).
