---
title: "Dove mettere le colonne XLS dei rating (catalogo)"
type: architecture
module: Rating
status: superseded
created: 2026-09-23
updated: 2026-09-23
qmd: "getXlsFields ratingXlsFields withExtraAttributes RatingData HasRatingsTrait getClassName reusable export"
related:
  - ./rating-entity-helpers-home-in-ratingdata.md
  - ./ratingdata-getxlsfields-caller-resolve.md
  - ../stories/5.237-ratingdata-export-ssot-architecture-reconcile.story.md
  - ../stories/18.60-rating-xls-fields-catalog.story.md
  - ../stories/18.58-xls-export-value-selected-child.story.md
  - ../stories/5.149-compila-vs-trait-reusable-boundary.story.md
  - ../stories/rating-query-with-extra-attributes-scope.story.md
  - ../../../../IndennitaResponsabilita/docs/bmad/stories/5.182-delegate-rating-xls-fields.story.md
  - ../../app/Datas/RatingData.php
  - ../../app/Models/Traits/HasRatingsTrait.php
---

> **Superseded (2026-09-23)** — le opzioni qui scelte (catalogo in
> `HasRatingsTrait`, «non RatingData») sono state ribaltate dal refactor:
> il catalogo e le helper (label/path/fieldName) vivono in `RatingData`
> (`getXlsFields(array $where, string $ratingClass)` con `$ratingClass`
> required). Canon unica: `rating-entity-helpers-home-in-ratingdata.md`
> (reconcile 5.237, revert 5.234). Questo file resta come storico del debate.

# Architettura — catalogo colonne XLS dai rating

## Perché (business)

L’export Excel delle schede IR deve aggiungere **una colonna per criterio**
(e `note` se ha figli). Oggi la logica vive in
`IndennitaResponsabilitaResource::getXlsFields()`: utile, ma **non è dominio
Filament Resource** — è “dato il filtro schemaless, mappa i rating a path→label”.
Lo stesso pezzo servirà a ogni modulo che esporta voti (IR oggi; altri STI
domani). Tenere il pezzo nel Resource = copia/incolla e drift (già successo con
path `pivot.value` vs `xls_export_value`).

## Stato osservato (2026-09-23)

| Fatto | Evidenza |
|-------|----------|
| Schemaless **già usato** in IR Resource | `Rating::withExtraAttributes(['anno'=>…,'type'=>…])` riga ~141 |
| Scope canonico | `BaseRating::scopeWithExtraAttributes` (Spatie package in lock) |
| Path/label riusabili già nel trait | `ratingXlsValuePath`, `formFieldLabel`, `ratingValuePath` |
| `syncRatingsWhere` già fa `Rating::getClassName()` + `withExtraAttributes` | `HasRatingsTrait` ~274–280 |
| `RatingData` è **già dual-purpose** (blocco UI + colonne migration) | docblock in `RatingData.php` |
| Story schemaless `ready-for-dev` **stale** | `rating-query-with-extra-attributes-scope.story.md` — AC già soddisfatti in codice |

## Perché non me ne ero accorto prima (autopsia)

1. **Pressione sul sintomo export** (colonne vuote / id pivot) → fix path in Resource,
   non estrazione catalogo.
2. **`getXlsFields` è contratto Resource** (XotBaseExporter) → bias “tutto resta nel Resource”.
3. **Confinamento 5.149** già diceva “generale vs particolare”, ma il catalogo XLS
   non era ancora nominato come pezzo piattaforma.
4. Schemaless: preferenza utente già in memoria/story, ma implementazione e story
   non erano allineate (story ancora open con codice già migrate).

## Opzioni (scoring)

Scala: **idoneità %** = quanto il posto rispetta DRY + KISS + religione Laraxot
(Action/trait, confini modulo, no debito nel DTO già sovraccarico).

### A — `HasRatingsTrait::ratingXlsFields(array $where, ?class-string $ratingClass = null): array`

| | |
|--|--|
| **Idoneità** | **62%** |
| **Punti di forza** | Stesso posto di `formFieldLabel` / `ratingXlsValuePath` / `syncRatingsWhere`; statico, chiamabile dal Resource; parallelismo mentale con sync; Pest già sul trait |
| **Punti deboli** | Trait pensato per *host* (scheda), qui è catalogo *Rating*; rischio di trait “god object” |
| **Dubbi** | `getClassName()` da call statico Resource **non** vede IR Rating → serve `$ratingClass` esplicito o default `Rating::getClassName()` |
| **Perplessità** | Nome `getXlsFields` sul trait **collide** col contratto Resource → meglio `ratingXlsFields` / `appendRatingXlsFields` |

### B — `RatingData::xlsFields(array $where, …)`

| | |
|--|--|
| **Idoneità** | **28%** |
| **Punti di forza** | Preferenza utente; già conosce `extra_attributes` in `updateColumns`; Data layer “puro” |
| **Punti deboli** | File già **due concetti** (blocco UI + migration) dichiarati nel docblock; terzo concetto = peggioramento debito esplicito; non è Spatie Data semantics (niente instance) |
| **Dubbi** | Rinominare/split `RatingBlockData` vs `RatingData` prima? Fuori scope export |
| **Perplessità** | Mettere query Eloquent in un Data object viola il “DTO sottile” |

### C — Spatie Queueable Action `BuildRatingXlsFieldsAction`

| | |
|--|--|
| **Idoneità** | **48%** |
| **Punti di forza** | Religione Actions-over-Services; testabile in isolamento; non ingrossa trait |
| **Punti deboli** | Un file in più per ~20 righe; i path helper restano sul trait → due hop |
| **Dubbi** | Overkill KISS se il solo consumer è IR oggi |
| **Perplessità** | Action vs static trait: quando c’è il 2° consumer Performance-style, Action scala meglio |

### D — Lasciare tutto nel Resource IR

| | |
|--|--|
| **Idoneità** | **12%** |
| **Punti di forza** | Zero move; già funziona |
| **Punti deboli** | Anti-DRY; ogni nuovo modulo copia; non usa `getClassName()` |
| **Dubbi** | — |
| **Perplessità** | Contradice 5.149 / 18.58 |

### E — `BaseRating::catalogXlsFields(array $where)` static sul modello

| | |
|--|--|
| **Idoneità** | **40%** |
| **Punti di forza** | Vive col modello interrogato; subclass IR Rating naturale |
| **Punti deboli** | Modello più grasso; meno allineato agli helper path già sul trait |

## Decisione proposta (raccomandazione)

**A con naming non collidente + parametro classe opzionale**, confidenza **~70%**:

```php
/**
 * @param  array<string, mixed>  $where  es. ['anno' => 2026, 'type' => 'dip']
 * @param  class-string<\Modules\Rating\Models\BaseRating>|null  $ratingClass
 * @return array<string, string>  path data_get => label
 */
public static function ratingXlsFields(array $where, ?string $ratingClass = null): array
```

Resource IR resta owner di:

- colonne **host** (matr, cognome, …)
- risoluzione `anno` / `type` dai `tableFilters`
- merge: `array_merge($base, HasRatingsTrait::ratingXlsFields($where, Rating::class))`

Query **solo** via `withExtraAttributes($where)` (mai `extra_attributes->` raw).

### Follow-up opzionale (se nasce 2° consumer non-IR)

Promuovere A → Action (opzione C) senza cambiare il contratto pubblico del trait
(wrapper thin).

## Cosa NON fare

- Non chiamare il metodo trait `getXlsFields` (shadow del contratto Resource/Xot).
- Non gonfiare `RatingData` finché non esiste lo split Block vs Entity (debito già documentato).
- Non hardcodare `Modules\IndennitaResponsabilita\Models\Rating` dentro Rating core
  senza parametro/getClassName.

## Gate

- Pest: unit sul nuovo static (mock/collection o SQLite no-DB se possibile)
- PHPStan Modules/Rating + IR
- Export IR invariante: stessi path/label di oggi
