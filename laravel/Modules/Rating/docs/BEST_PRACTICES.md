---
title: "Best Practices – Rating"
type: guide
tags: [best, practices, rating]
created: 2026-07-14
updated: 2026-07-14
qmd: "BEST PRACTICES"
related:
  - "./FALSE_FRIENDS.md"
---

# Best Practices – Rating

## Principi DRY/KISS
- **DRY**: Centralizza logica di scoring in `RatingService`. Usa attributi calcolati per azioni utente.
- **KISS**: Usa `star_rating` come integer 1-5, non float o stringhe.
- **Clean Code**: Segui il pattern `Builder` per recensioni complesse.

## Componenti
- Usa `RatingStar` per visualizzare icone vuote/piene.
- Usa `AverageRating` come campo calcolato (non memorizzato).

## Test
- Implementa test unitari per `RatingService::calculate()`.
- Copri casi limite come rating inversi.

## Documentazione
- Aggiorna `docs/index.md` con nuovi endpoint.
- Collega a moduli correlati come `Review` e `Auth`.
