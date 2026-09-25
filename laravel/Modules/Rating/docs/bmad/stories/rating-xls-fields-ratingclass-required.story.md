# Story — `ratingXlsFields`/`RatingData::getXlsFields`: `$ratingClass` required

**Status**: done
**Modulo**: Rating (+ call site IR)
**Epic**: export-xls-ratings
**Related**: `../architecture/rating-xls-fields-reusable.md` · `18.60-rating-xls-fields-catalog.story.md` · `../../../../IndennitaResponsabilita/docs/bmad/stories/5.179-rating-xls-fields-reusable-component.story.md` (§11 — trappola segnalata da Devin)

## Bug/trappola (found by Devin, fix mandatorio per standing order)

Firma `?string $ratingClass = null` con `??= Rating::getClassName()`:

- `getClassName()` richiede `['object']` nel backtrace → da contesto **statico**
  (l'unico call site reale, Resource Filament) lancia `RuntimeException`.
- Il parametro **sembrava** opzionale ma era di fatto obbligatorio → firma mente:
  `RatingData::getXlsFields($where)` compila e **esplode a runtime**.

## Fix

`class-string<BaseRating> $ratingClass` **required** in entrambe le firme;
rimosso il fallback `??= Rating::getClassName()`. Chi chiama da contesto istanza
può passare `Rating::getClassName()` esplicito (lì funziona).

Callers verificati pre-fix: solo `RatingData` (pass-through) e
`IndennitaResponsabilitaResource` (passa `Rating::class`). Nessun test sul
default nullable → cambio sicuro.

## Futuro (fuori scope)

Abilitare davvero la firma a 1 argomento = estendere
`XotBaseModel::getClassName()` ai frame statici (`['class','type'=>'::']` in
`Models\`/`Filament\Resources\`): story dedicata, regression fleet.

## Gate

- `php -l` entrambi i file
- PHPStan `Modules/Rating` + `Modules/IndennitaResponsabilita` → 0 errori
- Pest `HasRatingsTraitCriteriaToXlsFieldsTest` +
  `IndennitaResponsabilitaResourceGetXlsFieldsTest` / `ResourceLegacySchemaTest`
