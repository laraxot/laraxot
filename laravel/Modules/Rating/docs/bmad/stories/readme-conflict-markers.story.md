---
id: "rating-readme-conflict-markers"
title: "Rating: marker nidificati in README"
status: review
scope: module:Rating
created: 2026-09-22
updated: 2026-09-22
qmd: "rating readme nested conflict markers laravel 13 not 12"
related:
  - ../../../../Xot/docs/bmad/stories/cleanup-all-modules.story.md
---

# Rating — README con marker nidificati

**Perché.** HEAD README aveva hunk vuoti su HEAD e `<<<<<<<` dentro theirs (badge Laravel 12 vs 13).

## Scelta

Strip marker; togliere badge Laravel 12 (stack = Laravel 13).

**Non toccato:** `tests/Unit/HasRatingsTraitRatingsByIdTest.php` e `.gitattributes` (staged, altra sessione — PHPStan 9 errori lì).

## Gate

README senza `<<<<<<<`. PHPStan modulo intero rosso solo sul test WIP altrui. Commit deferred.
