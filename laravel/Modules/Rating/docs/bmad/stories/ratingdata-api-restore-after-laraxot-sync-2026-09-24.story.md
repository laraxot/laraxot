---
title: "Story — ripristino API RatingData/HasRatingsTrait dopo il sync laraxot/dev (2026-09-24)"
type: story
module: Rating
epic: quality
story_id: "ratingdata-api-restore-after-laraxot-sync-2026-09-24"
status: done
qmd: "RatingData ratingFieldName formFieldLabel getXlsFields criteriaToXlsFields regressione sync laraxot/dev merge_remote_repo_2 clearEvaluation ratings_by_id"
related:
  - ./rating-statics-to-ratingdata-consolidation.story.md
  - ./5.240-ratingcontract-closures-not-baserating.story.md
  - ./phpstan-post-sync-restore-2026-09-24.story.md
---
# ratingdata-api-restore-after-laraxot-sync-2026-09-24

## Perché

54 errori PHPStan (Rating + IndennitaResponsabilita) con una sola causa: il sync da
`laraxot/dev` (remote stale) aveva riportato Rating a una versione precedente alla
consolidazione `rating-statics-to-ratingdata-consolidation` e a 5.240.

Perso dal sync, ripristinato dal parent HEAD:

- `RatingData::ratingValuePath/ratingXlsValuePath/ratingFieldName/formFieldLabel/getXlsFields/criteriaToXlsFields`
- `HasRatingsTrait::getRatingsByIdAttribute()` (`ratings_by_id`), `clearEvaluation()`, `clearRatingsFormData()`,
  `syncRatingsFormData()` via `ratingMorphs()` (scope `model_id` + alias|FQCN), `syncWithoutDetaching`
- `BaseRating::getTxtHtml/resolveSelectedChild/getXlsExportValueAttribute/getValueHtml/getNoteHtml`
- firme `RatingContract` (non `BaseRating`) in `RatingsFormCallerContract`, `DecoratesRatingFormFields`
- i test Rating che coprono le API sopra

Cosa del sync è rimasto: i docblock di `RatingData` (spiegazione dei due concetti, `fromArray`,
`updateColumns`), `HasRatingValuesFilter extends XotBaseTernaryFilter`, la pulizia degli spazi in `BaseModel`.

Scartato: `HasRatingsTraitFormFieldLabelTest.php` (duplica `RatingDataFormFieldLabelTest` sulla vecchia API
`HasRatingsTrait::formFieldLabel`, chiamata statica di un trait vietata dalla story di consolidamento).

`RatingPhpstanTraitProbe` (un model finto in `app/Models`) → spostato in `tests/Fixtures/HasRatingHostStub.php`:
serve solo a far analizzare a PHPStan il trait `HasRating` e non deve comparire nel discovery dei model.
Fix vero in `HasRating::getArrayRatingsWithImage()`: si accumula come lista (`[] =`), l'icona si sceglie con
`count($ratings_array)`, niente più chiave della collection.

## Incidente durante il lavoro

`bashscripts/git/subtrees/merge_remote_repo_2.sh laraxot` (deprecato, lanciato dal terminale
interattivo) è girato due volte mentre il lavoro era in corso: `git add -A && commit "."`, merge
`--allow-unrelated-histories` → marker `<<<<<<<` committati in circa 60 file di Rating, rebase
interattivo lasciato aperto nel repo annidato. Il working tree è stato ripristinato file per file
(dal commit `4b36fd7` del repo annidato, cioè lo snapshot pre-merge, e dal parent HEAD). Non è
stata fatta nessuna operazione git. Finché lo script gira, il working tree può essere di nuovo
sovrascritto.

## Gate

```text
phpstan analyse Modules/Rating Modules/IndennitaResponsabilita → 0 errori
Pest: DB_CONNECTION=sqlite DB_DATABASE=:memory: (MySQL 10.100.200.53 irraggiungibile, con .env.testing si blocca in connect())
```
