# Story — `Assert::subclassOf(BaseRating)` → `Assert::implementsInterface(RatingContract)`

**Status**: done
**Modulo**: Rating
**Epic**: rating-entity-helpers-home-in-ratingdata
**Related**: `rating-statics-to-ratingdata-consolidation.story.md` · `5.232` · `5.233-formfieldlabel-ratingcontract-typehint`

## Ordine utente

> Non fare `Assert::subclassOf($ratingClass, BaseRating::class)` — usa il check
> su `RatingContract`, che serve proprio a questo.

## Perché (il perché autonomo)

- `subclassOf(BaseRating)` chiede «discendi da questa gerarchia concreta» —
  lega il vincolo al dettaglio implementativo.
- `implementsInterface(RatingContract)` chiede «sei un criterio» — è la
  domanda semantica giusta: `RatingContract` esiste apposta per essere
  consumato da sei moduli su connessioni diverse, senza conoscere la concreta.
- Il docblock del contratto lo dichiara già: «ha bisogno di sapere che cosa un
  criterio dichiara di se', non quale concreta ha in mano».
- `@phpstan-require-extends Model` sul contratto garantisce che
  `class-string<RatingContract>` resti compatibile con `hasMany` /
  `morphToManyX` / `new` (Eloquent), quindi il check più debole
  semanticamente non perde garanzie statiche.

## Fatto

- `HasRatingsTrait`: 5 occorrenze `Assert::subclassOf($x, BaseRating::class)`
  → `Assert::implementsInterface($x, RatingContract::class)`
  (`ratings()`, fallback `ratings_by_id`, `ratingObjectives()`,
  `userRatingsMorph()`, `syncRatingsWhere()`).
- Docblock `@var class-string<BaseRating>` mantenuti: l'intersezione
  `BaseRating & RatingContract` conserva l'informazione concreta per i generics
  Eloquent senza restringere il check.
- Import `Modules\Rating\Models\Contracts\RatingContract` aggiunto al trait.
- `RatingData::getXlsFields` già conforme (`implementsInterface`, story 5.232).

## Gate

- `php -l`, PHPStan sui file toccati, Pest scope Rating+IR, `pint`.
