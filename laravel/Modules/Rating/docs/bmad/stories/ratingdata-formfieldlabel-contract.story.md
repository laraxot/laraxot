> **SUPERSEDED (2026-09-23)** — bozza pre-correzione dello stesso lavoro.
> Canon: [`5.232-ratingcontract-check-getxlsfields`](./5.232-ratingcontract-check-getxlsfields.story.md) (Assert::implementsInterface), [`5.233-formfieldlabel-ratingcontract-typehint`](./5.233-formfieldlabel-ratingcontract-typehint.story.md) (RatingContract sulle firme), [`5.234-ratingdata-ratingclass-required-revert-backtrace`](./5.234-ratingdata-ratingclass-required-revert-backtrace.story.md) ($ratingClass required, no backtrace).

# BMAD — ratingdata-formfieldlabel-contract
**Status**: done + review
**Modulo**: Rating
**Perché ho dimenticato BMAD**: focus su `getXlsFields` e `ratingClass` — non ho
esteso il ragionamento alle firme dei metodi helper (`formFieldLabel`,
`ratingXlsValuePath`, `ratingValuePath`). Corretto con questa story.

## Perché `RatingContract` invece di `BaseRating`
- `BaseRating` è il modello base; `RatingContract` (`interface`) è il contratto
  che tutte le concrete implementano (`Ptv\Models\Rating`, `Progressioni`...)
- `RatingData` è un DTO per dati, non per un host specifico — deve funzionare
  con qualsiasi `RatingContract`, non solo `BaseRating`.
- `formFieldLabel()` non usa niente di specifico a `BaseRating` — solo `txt` e
  `title`, che sono parte del contratto (`RatingContract`).

## AC
- [x] Firme `formFieldLabel()`, `ratingXlsValuePath()`, `ratingValuePath()`
      aggiornate da `BaseRating` a `RatingContract`
- [x] `RatingData` non dipende più dal concreto `BaseRating` per le firme
- [x] PHPStan passa con `RatingContract` come tipo parametro
