---
title: "formFieldLabel / ratingXlsValuePath / RatingData::getXlsFields — verdetto"
type: architecture
module: Rating
status: accepted
created: 2026-09-23
updated: 2026-09-23
qmd: "formFieldLabel ratingXlsValuePath RatingData getXlsFields trait BaseRating debate swarm"
related:
  - ./rating-xls-fields-reusable.md
  - ../stories/5.228-form-field-label-and-rating-xls-value-path-location.md
  - ../stories/rating-data-export-methods.story.md
  - ../stories/18.60-rating-xls-fields-catalog.story.md
  - ../../../../IndennitaResponsabilita/docs/bmad/stories/5.179-rating-xls-fields-reusable-component.story.md
  - ../../../../IndennitaResponsabilita/docs/prompts/04.md
---

> **AMENDED 2026-09-23** — il verdetto sotto (~78%, "restano nel trait") è stato
> **ribaltato** da correzione esplicita dell'utente in `04.md` righe 66-79:
> chiamare `TraitName::staticMethod()` va evitato "come la peste" a prescindere dal
> dominio del metodo — i due helper sono spostati in `RatingData` con type hint
> `RatingContract` (non `BaseRating`). Vedi
> [story 5.232](../stories/5.234-ratingdata-ratingclass-required-revert-backtrace.story.md)
> per il verdetto finale e la contro-analisi dei punti 1-3 sotto. Documento
> mantenuto come storico del dibattito, non più normativo.

# Verdetto — dove vivono label, path e catalogo XLS (storico, vedi nota sopra)

Prompt utente (`04.md` 35–64): spostare `formFieldLabel` + `ratingXlsValuePath`
in `RatingData`; Resource con `...RatingData::getXlsFields($where)`.

## Premessa: mettere tutto in discussione

| Claim | Verifica |
|-------|----------|
| «RatingData è il posto naturale» | Debole: file già **due** concetti (blocco UI + migration); docblock lo ammette |
| «Il trait è solo relazioni» | Falso oggi: path, label, form schema, sync, export — god-object |
| «Spostare i 2 helper risolve il god-object» | Parziale: 2 metodi su ~700 righe; non tocca la radice |
| «User API `RatingData::getXlsFields`» | Valida come **facade catalogo**; invalida se include colonne host IR |
| «getClassName torna alias» (5.228) | **Falso** — torna FQCN `Modules\<Mod>\Models\Rating` |

## Debate (agent paralleli + story 5.179)

| Sede | formFieldLabel | ratingXlsValuePath | Catalogo colonne rating |
|------|----------------|--------------------|-------------------------|
| RatingData (raw move) | **22%** | **15%** | **35%** se solo rating+`$ratingClass` |
| HasRatingsTrait | **55%** | **75%** | **62%** (già 18.60) |
| BaseRating accessor | **70%** (futuro) | 20% | 25% |
| Support/Export helper | 45% | 50% | 48% |
| Action Spatie | 30% | 25% | 48% |

### Perché NON spostare i 2 helper in RatingData

1. **`formFieldLabel`** — presentazione entità *e* form Filament (validationAttribute,
   DecoratesRatingFormFields). Non è “dato export”. Miglior futuro: accessor
   `BaseRating` (`form_field_label`), non DTO Spatie.
2. **`ratingXlsValuePath`** — codifica `ratings_by_id.*`, layout **host** (hydration
   trait). Un DTO entità che conosce la shape array dell’host = leak inverso.
3. Spostare i dipendenti di `ratingXlsFields` (trait) in Data → dipendenza
   trait↔Data o duplicazione.
4. Story `rating-data-export-methods` che sposta i 2 helper → **superseded**.

### Cosa fare di `RatingData::getXlsFields` (già nel worktree, difettoso)

Diffetti rilevati (5.179 §9):

1. Query su `Modules\Rating\Models\Rating` senza FQCN modulo → tabella/connection sbagliata per IR
2. **Leak**: colonne host IR (`matr`, `stabi_txt`, …) dentro modulo Rating
3. Duplicato quasi identico `getXlsExportFields`
4. Nome collide col Resource (tollerabile se FQCN diverso; semantica deve essere chiara)

### Decisione accettata (allinea preferenza UX utente + confini)

```php
// Resource IR — API preferita utente
$where = ['anno' => $anno, 'type' => $type];
return [
    ...$fields,                          // SOLO colonne host (Resource)
    ...RatingData::getXlsFields($where, Rating::class), // SOLO catalogo rating
];
```

`RatingData::getXlsFields` = **facade sottile** → delega a
`HasRatingsTrait::ratingXlsFields($where, $ratingClass)`.

- Nessun campo host nel Data.
- `$ratingClass` obbligatorio in pratica (default `Rating::getClassName()` fragile da static).
- `formFieldLabel` / `ratingXlsValuePath` **restano nel trait** (finché non si fa
  accessor BaseRating in story dedicata).
- Rimuovere `getXlsExportFields` duplicato.

Confidenza decisione complessiva: **~78%**.

## Fuori scope

- Split `RatingBlockData` / `RatingData` entity
- Accessor `form_field_label` su BaseRating (story futura, ~70% come sede label)
