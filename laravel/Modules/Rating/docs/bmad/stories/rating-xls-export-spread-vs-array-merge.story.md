---
title: "rating-xls-export-spread-vs-array-merge"
type: story
module: Rating
status: superseded
story_id: "rating-xls-export-spread"
created: 2026-09-23
updated: 2026-09-23
superseded_by: ./5.238-rating-export-early-stories-supersede.story.md
qmd: "spread vs array_merge RatingData getXlsFields"
---

> **SUPERSEDED** — IR Resource già usa `$where` + spread +
> `RatingData::getXlsFields($where, Rating::class)`. Chiusura: `5.238`.
> Testo sotto = debate storico (parti obsolete su helper nel trait).

# BMAD — rating-xls-export-spread-vs-array-merge (storico)
**Status**: superseded (implementato)
**Epic**: Rating / IndennitaResponsabilita — spostamento `getXlsFields()` in `RatingData`

---

## 1. Perché spread (`...`) invece di `array_merge`

| Aspetto | `array_merge($fields, HasRatingsTrait::ratingXlsFields(...))` | `[...$fields, ...RatingData::getXlsFields($where)]` |
|---|---|---|
| Leggibilità | 2 chiamate, 1 array intermedio implicito | esplicito: "i campi base + i campi rating" |
| PHP version | qualsiasi | 8.1+ (progetto: 8.3 ✓) |
| Source of truth | doppio: `HasRatingsTrait::ratingXlsFields()` + `RatingData` | unico: `RatingData::getXlsFields()` |
| Performance | `array_merge` crea un array intermedio | spread: single pass (ottimizzato da opcache) |

**Verdetto (70% spread, 30% merge)**: spread è più esplicito, moderno, e rende chiaro che i campi export vengono da `RatingData`. Non c'è motivo tecnico per preferire `array_merge`.

---

## 2. Dove vive la logica: `RatingData` vs `HasRatingsTrait`

### Stato attuale (prima di questa sessione)
- `HasRatingsTrait::ratingXlsFields(array $where, ?string $ratingClass = null)` — già esiste, usa `Rating::getClassName()` (richiede `object` nel backtrace)
- `HasRatingsTrait::formFieldLabel()` — usato in `buildRatingComponent()`, `DecoratesRatingFormFields`, `IndennitaResponsabilitaResource`
- `HasRatingsTrait::ratingXlsValuePath()` — usato ovunque per i path `data_get`

### Problema con `getClassName()` da `RatingData`
`XotBaseModel::getClassName()` cerca `debug_backtrace()` un `object` con `Models\` o `Filament\Resources\` nel namespace. `RatingData` (statico, senza istanza) non ha questo oggetto: la chiamata fallirebbe con `RuntimeException('Unable to resolve caller object...')`. **Questo conferma** che `RatingData::getXlsFields()` NON può chiamare `Rating::getClassName()` senza un `object` chiamante.

### Soluzione architetturale
- `RatingData::getXlsFields(array $where)` — usa `Rating::withExtraAttributes($where)` (hardcoded sul modello Rating base, con `type` passato nel `$where`). Questo è corretto perché `$where['type']` contiene già l'alias (`dip`, `po`, ...) del modulo chiamante.
- `HasRatingsTrait::ratingXlsFields(array $where, ?string $ratingClass = null)` — resta per chi ha bisogno di passare esplicitamente la classe Rating del modulo (es. `Rating::class` da `IndennitaResponsabilita`), delega a `RatingData::getXlsFields()` per la logica principale.
- `formFieldLabel` e `ratingXlsValuePath` — RESTANO nel trait perché usati da `buildRatingComponent()`, `DecoratesRatingFormFields`, e `CompilaIndennitaResponsabilita`. Non possono essere spostati senza rompere il form.

### Componenti riutilizzabili individuati
1. **RatingData::getXlsFields()** — nuovo componente riutilizzabile per export XLS (prima era solo in `HasRatingsTrait`)
2. **RatingData::columns() / updateColumns()** — già riutilizzabile per migrazioni
3. **HasRatingsTrait::ratingXlsFields()** — delega a `RatingData`, mantiene compatibilità con il parametro `$ratingClass`

---

## 3. Dubbi e perplessità (30% di incertezza)

- **`RatingData` porta già 2 concetti** (UI block DTO + colonne tabella). Aggiungere `getXlsFields()` ne aggiunge un terzo. Rischio di "god object". **Mitigazione**: il metodo è statico, non modifica lo stato del DTO, quindi non viola l'immutabilità.
- **`HasRatingsTrait::ratingXlsFields()` è ancora necessario?** Sì, perché accetta `$ratingClass` esplicito. Se un modulo ha un `Rating` custom (es. `IndennitaResponsabilita\Models\Rating`), il trait lo supporta. `RatingData` non può dedurre questa classe senza un `object` chiamante.
- **Test duplicati?** `RatingData` dovrebbe avere `RatingDataGetXlsFieldsTest`, separato da `HasRatingsTraitRatingsFormDataTest`. Non duplicare la logica, ma duplicare i test è accettabile (test isolati = più affidabili).

---

## 4. Punti di forza (70% certezza)

- **Single source of truth per export**: `RatingData::getXlsFields()` è il punto unico.
- **Spread syntax**: più chiaro, moderno, e rende immediato che i campi base (`$fields`) e i campi rating vengono da `RatingData`.
- **`formFieldLabel` resta nel trait**: non rompe il form (`DecoratesRatingFormFields`, `CompilaIndennitaResponsabilita`).
- **`ratingXlsValuePath` resta nel trait**: il path `data_get` (`ratings_by_id.{id}.xls_export_value`) è una convenzione condivisa tra form ed export; spostarlo romperebbe i test esistenti.

---

## 5. Coordinamento con altri agenti

- `agent-codemonkey` (lock `IndennitaResponsabilitaResource.php`, task `bmad-export-xls-ratings`): il lock è vivo (23h). Non rubato. Quando rilascia, applico `...RatingData::getXlsFields()`.
- `agent-codemonkey` sta lavorando su `getXlsFields` — potrebbe aver già implementato `array_merge` con `HasRatingsTrait::ratingXlsFields()`. Quando il lock si libera, verifico il contenuto e applico lo spread.

---

## 6. AC (Acceptance Criteria) — stato
- [x] BMAD `rating-getxlsfields-relocation.story.md` creato
- [x] Second brain `hasratings-trait-vs-rating-data.md` creato
- [x] `RatingData::getXlsFields()` creato (rinominato da `getXlsExportFields`)
- [x] `HasRatingsTrait::ratingXlsFields()` delega a `RatingData::getXlsFields()`
- [ ] Lock `IndennitaResponsabilitaResource.php` rilasciato → applicare spread
- [ ] PHPStan `Modules/Rating` [OK]
- [ ] `RatingDataGetXlsFieldsTest` creato

---

## 7. Note per il prossimo turno

Quando `agent-codemonkey` rilascia il lock su `IndennitaResponsabilitaResource.php`:
```php
// Sostituire in IndennitaResponsabilitaResource::getXlsFields():
$where = [
    'anno' => $anno,
    'type' => (new $modelClass)->classToAlias($modelClass),
];
return [
    ...$fields,
    ...RatingData::getXlsFields($where),
];
```
E aggiungere `use Modules\Rating\Datas\RatingData;` al file.
