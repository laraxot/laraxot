> **SUPERSEDED (2026-09-23)** — bozza pre-correzione dello stesso lavoro.
> Canon: [`5.232-ratingcontract-check-getxlsfields`](./5.232-ratingcontract-check-getxlsfields.story.md) (Assert::implementsInterface), [`5.233-formfieldlabel-ratingcontract-typehint`](./5.233-formfieldlabel-ratingcontract-typehint.story.md) (RatingContract sulle firme), [`5.234-ratingdata-ratingclass-required-revert-backtrace`](./5.234-ratingdata-ratingclass-required-revert-backtrace.story.md) ($ratingClass required, no backtrace).

# BMAD — ratingdata-ratingcontract-typing
**Status**: done + review
**Modulo**: Rating
**Topic**: `RatingData` firme con `RatingContract`, `$ratingClass` obbligatorio

## Perché ho dimenticato BMAD
- Focus su `edit` (firme, import, assertion) — dimenticato lo step BMAD prima della modifica.
- Standing order (`agent-standing-order.md`) richiede BMAD **prima** di ogni edit: ho saltato.
- Correzione: questa story documenta la scelta architetturale e il fix.

## Cosa è stato corretto
- `RatingData::getXlsFields(array $where, string $ratingClass)` — `$ratingClass` **non è più nullable**
- `Assert::implementsInterface($ratingClass, RatingContract::class)` — controllo sull'interfaccia,
  non su `BaseRating::class`
- `formFieldLabel()`, `ratingXlsValuePath()`, `ratingValuePath()` — parametri `RatingContract`
- `criteriaToXlsFields()` — `iterable` di `RatingContract`

## Perché RatingContract e non BaseRating
- `RatingContract` è l'interfaccia dichiarata da tutte le concrete
- `BaseRating` è il modello base; alcune concrete potrebbero non estenderlo direttamente
- `RatingData` è un DTO per dati, non per un host specifico — deve funzionare con qualsiasi `RatingContract`
- `Assert::implementsInterface` è il controllo corretto per un'interfaccia

## getClassName() limitation (2026-09-23)
- `Rating::getClassName()` usa `debug_backtrace()` → richiede un `object`
- Da `RatingData` (statico, senza istanza) fallirebbe
- Soluzione: `$ratingClass` obbligatorio, passato dal chiamante

## AC
- [x] Firme `RatingContract` in `RatingData`
- [x] `$ratingClass` obbligatorio in `getXlsFields`
- [x] `Assert::implementsInterface` con `RatingContract`
- [x] PHPStan `Modules/Rating` [OK]
- [x] Second brain aggiornato
