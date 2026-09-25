# Architecture — spostamento formFieldLabel e ratingXlsValuePath in RatingData

## Scopo
Spostare i metodi statici `formFieldLabel` e `ratingXlsValuePath` da `HasRatingsTrait.php` a `RatingData.php`, perché sono utility di rating e non di relazioni.

## Design
`RatingData` diventa il punto unico per:
- Utility di rating: `formFieldLabel(BaseRating $rating): string`
- Utility di export rating: `ratingXlsValuePath(BaseRating $rating): string`
- Catalogo colonne export XLS/XLSX dai criteri rating (`getXlsFields`, `criteriaToXlsFields`)

## Flusso
1. `HasRatingsTrait` mantiene le relazioni (`ratings()`, `ratingMorphs()`, `myRatings()`, ecc.) ma perde `formFieldLabel` e `ratingXlsValuePath`.
2. `RatingData` guadagna i due metodi e li usa internamente (in `getXlsFields`, `criteriaToXlsFields`).
3. Tutti i callers chiamano `RatingData::formFieldLabel($rating)` e `RatingData::ratingXlsValuePath($rating)`.
4. Il resource `IndennitaResponsabilita` chiama `RatingData::getXlsFields($where)` e ottiene solo i campi rating (non i campi base dell'host).

## Dipendenze
- `BaseRating`
- `RatingData`
- `HasRatingsTrait` (per le relazioni, ma non più per queste utility)
- Nessun altro modulo

## Sicurezza
- Nessuna query SQL dinamica: si usa `withExtraAttributes` che è già parametrizzato e sicuro.
- I dati in input (`$data`) sono attenduti provenire da form validati (dal resource Filament).
- Le etichette vengono tradotte tramite Laravel Lang (safe).

## Testing
- Aggiornare i test esistenti (`HasRatingsTraitFormFieldLabelTest`) per chiamare `RatingData::formFieldLabel`.
- Verificare PHPStan su `Rating` e `IndennitaResponsabilita`.
- Verificare Pest su moduli toccati.

## Note
- Il refactoring è stato richiesto esplicitamente dall'utente.
- `RatingData` è già il DTO di rating; spostare qui le utility migliora la coesione del dominio rating.
- Non serve mantenere alias nel trait per evitare confusione e duplicazione.
