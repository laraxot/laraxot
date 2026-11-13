---
title: "Epic 5.249: Rating: export XLS e path come SSoT in RatingData"
type: epic
module: Rating
status: active
tags:
  - bmad
  - epic
  - rating
  - export
  - xls
created: 2026-09-28
updated: 2026-09-28
qmd: "rating epic export xls ssot ratingdata path label"
related:
  - ../architecture/rating-data-export-methods.md
  - ../architecture/ratingdata-getxlsfields-caller-resolve.md
  - ../architecture/rating-export-fields-reusable.md
  - ../brainstorming.md
---

# Epic 5.249: Rating: export XLS e path come SSoT in `RatingData`

> **SUMMARY**: Epic di rifinitura del lato export del modulo `Rating`: tutti i path di
> valore, le label di form e i campi XLS devono derivare da `app/Datas/RatingData.php`,
> senza copie nei modelli, nelle colonne Filament o nei form host.

## Perche ora

`app/Datas/RatingData.php` raccoglie gia' i metodi canonici
(`ratingValuePath()`, `ratingXlsValuePath()`, `ratingFieldName()`, `formFieldLabel()`,
`getXlsFields()`, `criteriaToXlsFields()`), ma restano nel modulo copie di logica equivalente
e residui da allineare: il modello ha ancora accessor di export
(`BaseRating::getXlsExportValueAttribute()`), la colonna e la sezione form decorano i campi
per conto proprio, e `tests/` contiene sia test del SSoT sia test che ne reimplementano le regole.

## Scope

In scope:
- verifica che ogni consumer di path/label XLS chiami `RatingData`;
- allineamento di `BaseRating` (`getXlsExportValueAttribute()`, `getValueHtml()`, `getNoteHtml()`)
  al SSoT senza breaking change sul contratto;
- riuso di `DecoratesRatingFormFields::applyDefaultRatingFieldDecoration()` da tutte le form;
- copertura Pest dei casi ancora scoperti sui metodi `RatingData`.

Out of scope:
- modifiche a concrete di `Rating` in altri moduli (es. `Ptv\Models\Rating`);
- cambio del formato XLS o del naming delle colonne;
- UI nuova.

## Acceptance criteria

| # | AC | Verifica |
|---|----|----------|
| 1 | `RatingData::ratingValuePath()` e' l'unico produttore di path di valore | `grep` su `ratingValuePath` in `app/` |
| 2 | `RatingData::formFieldLabel()` e' l'unico produttore di label di campo | `grep` su `formFieldLabel` in `app/` |
| 3 | `RatingData::criteriaToXlsFields()` e' usato da tutti gli export | `grep` su `criteriaToXlsFields` in `app/` |
| 4 | `DecoratesRatingFormFields` e' il solo punto di decorazione | `grep` su `applyDefaultRatingFieldDecoration` |
| 5 | Il caller di `RatingData::getXlsFields()` e' risolto come da `architecture/ratingdata-getxlsfields-caller-resolve.md` | test dedicato |
| 6 | `tests/Unit/RatingDataCriteriaToXlsFieldsTest.php` e `tests/Unit/BaseRatingXlsExportValueTest.php` restano verdi | Pest |

## Rischio

`getXlsFields()` richiede `string $ratingClass`: un caller sbagliato produce campi vuoti senza
errore. Il rischio e' silenzioso, quindi la AC 5 va coperta da test che falliscono su classe errata.
