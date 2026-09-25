---
title: "rating-getxlsfields-relocation"
type: story
module: Rating
status: superseded
story_id: "rating-getxlsfields-relocation"
updated: 2026-09-23
superseded_by: ./5.238-rating-export-early-stories-supersede.story.md
---

> **SUPERSEDED** — implementato come `RatingData::getXlsFields` (5.229 / move). Vedi `5.238`.

# Story: rating-getxlsfields-relocation (storico)
**Status**: superseded
**Modulo**: Rating / IndennitaResponsabilita
**Epic**: spostare catalogo export rating in `RatingData`

## Analisi architetturale (BMAD)

### Dove era
`IndennitaResponsabilitaResource::getXlsFields(array $data)` — static method sul Resource Filament.

### Dove va
`Modules\Rating\Datas\RatingData::getXlsFields(array $where)` — static method sul Data class del modulo Rating.

### Perché non HasRatingsTrait (motivazione tecnica)
- `HasRatingsTrait` è un **trait sui modelli host** (`IndennitaResponsabilita`, `SchedaDip`, …): tutti i suoi metodi usano `$this` (`$this->ratings()`, `$this->ratingMorphs()`).
- `getXlsFields()` è **puro**: non ha bisogno di `$this`, solo di `Rating::withExtraAttributes($where)`.
- Chiamare `IndennitaResponsabilitaResource::getXlsFields()` è statico → non c'è istanza host. Metterlo nel trait forzerebbe un fake `$this` o un `static::` che rompe il template `TModel`.
- **Verdetto**: 70% HasRatingsTrait è sbagliato, 30% è accettabile (i helper `ratingXlsValuePath`/`formFieldLabel` sono già lì e restano).

### Perché RatingData (punti di forza)
- già esistente, già usato per `columns()` (statico, tabella)
- already has `fromArray()` + Spatie LaravelData casting
- Stesso pattern di `SchedaData` → `schede`
- DRY: un solo posto per query rating + colonne export

### Punti deboli / dubbi (45% incertezza)
1. `RatingData` porta **due concetti** già (UI block + colonne tabella); aggiungerne un terzo (export fields) rischia di diventare un "god object"
2. Spatie Data class sono DTO immutabili — query builder statico è fuori dal suo scope originale
3. `classToAlias()` del host non è disponibile in `RatingData`: va passato come `$where['type']` (soluzione già accettata dall'utente)
4. PHPStan dovrà verificare i generics del trait `Data` di Spatie

### Punti di forza (55% certezza)
- Un solo punto per `Rating::withExtraAttributes()` + `getXlsExportValueAttribute()` + `ratingXlsValuePath()`
- Resource diventa thin: `RatingData::getXlsFields(['anno' => $anno, 'type' => $type])`
- Testabile senza Filament (come `RatingDataTest`)

## AC
- [ ] `RatingData::getXlsFields(array $where): array` statico
- [ ] Usa `Rating::withExtraAttributes($where)->ordered()->get()` (spatie schemaless)
- [ ] Fila `parent_id !== null` (solo criteri, non opzioni Select)
- [ ] `loadMissing('children')`
- [ ] Per ogni rating: `HasRatingsTrait::ratingXlsValuePath($rating)` come chiave, `HasRatingsTrait::formFieldLabel($rating)` come label
- [ ] Se `$rating->children->isNotEmpty()`: aggiungi `HasRatingsTrait::ratingValuePath($rating, 'note')` col label `note_for`
- [ ] Fallback senza `$where` → solo campi base (12 colonne, mai `[]`)
- [ ] `IndennitaResponsabilitaResource::getXlsFields()` delega a `RatingData::getXlsFields()`
- [ ] PHPStan Modules/Rating [OK]
- [ ] Test `RatingDataGetXlsFieldsTest` (nessun DB, solo logica)

## Note
- `Rating::getClassName()` risolve la classe rating corretta nel contesto del modulo chiamante (es. IndennitaResponsabilita) tramite `debug_backtrace` in `XotBaseModel::getClassName()`
- Non usare `migrate:fresh`, `--force`, `RefreshDatabase`
- Host 10.100.200.15: nessun test