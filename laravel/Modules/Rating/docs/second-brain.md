---
title: "second brain"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "second brain"
issues: []
discussions: []
module: theme
topic: second-brain
canonical: ../../../Themes/docs/shared-components/second-brain.md
status: active
tags: [rating, valuation, phpstan, contract, polymorphic]
updated: 2026-10-21
module: Rating

# Second brain (Rating: valutazione / rating polimorfico)

Modulo purpose (domain): sistema di valutazione riutilizzabile, polimorfico (morph `HasRatings`), contratti (`RatingContract`) preferiti su `BaseRating`. PHPStan L10 critico: tipizzazione contratti evita regressioni su `RatingData`, `ratingclass`, e query extra-attribute (`withExtraAttributes`).

**PHPStan status (2026-10-21):** swarm cluster attivo: storie: `5.232-ratingdata-ratingcontract-typing`, `5.241-prefer-contracts-over-abstract-classes-second-brain`, `cluster-3-rating-xot-phpstan-l10`. 93 storie BMAD nel modulo; 0 errori target level max. Vedi `docs/bmad/stories/`.

See canonical documentation: ../../../Themes/docs/shared-components/second-brain.md
