---
id: Rating/phpstan-baserating-getvaluehtml
title: "Parse error getValueHtml su BaseRating"
status: done
module: Rating
priority: P0
related:
  - 5.157-phpstan-rating-pivot-get-test.story.md
---

# Story — PHPStan parse `getValueHtml`

## Problema

Residuo merge: `if()` vuoto in `BaseRating::getValueHtml()` → parse error PHPStan fleet.

## Fix

- Rimosso `use Illuminate\Support\Arr` inutilizzato.
- Ripristinato corpo `getValueHtml()` (Money per etichette «Importo», altrimenti stringa pivot).
- Argomento `money()`: `(int) round((float) value * 100)` — il helper Cknow interpreta i `float` come euro interi, non centesimi.
- Test unitari in `BaseRatingModelTest` con pivot `RatingMorph` in memoria (no DB).

## Gate

- `php -l` su `BaseRating.php`
- `phpstan analyse Modules/Rating`
- Pest scope `BaseRatingModelTest`

## Gate finale
- PHPStan Modules/Rating: 0
- Pest BaseRatingModelTest: see session
- hasChildRatings() per evitare query in unit test

