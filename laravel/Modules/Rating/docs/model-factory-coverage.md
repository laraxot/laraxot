---
title: "Rating — copertura model / migration / seeder / factory"
type: reference
tags: [rating, models, factory, migration, seeder, pivot, coverage]
created: 2026-07-24
updated: 2026-07-24
qmd: "Rating models migration seeder factory coverage pivot morph audit"
---

# Rating — copertura Model → Migration / Seeder / Factory

Obiettivo: ogni modello concreto (non-pivot / non-astratto) deve avere
migration + seeder + factory.

## Modelli in `app/Models/*.php`

| Modello | Tipo | Tabella | Concreto? | Factory |
|---|---|---|---|---|
| `Rating` | `BaseRating` → `BaseModel` → `XotBaseModel` | `ratings` | sì | **`RatingFactory` (definition realistica)** |
| `RatingMorph` | `BaseMorphPivot` → `MorphPivot` | `rating_morph` | **pivot → skip** | — |
| `BaseRating` | astratto | — | no (abstract) | — |
| `BaseRatingMorph` | astratto | — | no (abstract) | — |
| `BaseModel` | astratto | — | no (abstract) | — |
| `BaseMorphPivot` | astratto | — | no (abstract) | — |

## Lavoro svolto

- `database/factories/RatingFactory.php`: era uno **stub** (`definition()` restituiva
  `[]`). Riempita con `definition()` realistica basata su `BaseRating::$fillable` e sui
  cast: `title` (unique), `color` (hex), `txt`, `rule` (`RuleEnum::ZeroFive`),
  `is_disabled`/`is_readonly` (boolean), `order_column`. `slug` è generato
  automaticamente da `HasSlug` a partire da `title`. La factory è esposta via
  `HasXotFactory` (ereditato da `XotBaseModel`), come negli altri moduli.

## Skip motivati

- **`RatingMorph`** — è un **morph pivot** (`extends BaseMorphPivot extends
  Illuminate\Database\Eloquent\Relations\MorphPivot`). `MorphPivot` non usa il trait
  `HasFactory`/`HasXotFactory` e non supporta `Model::factory()`; una factory non è né
  possibile né appropriata per una tabella pivot di collegamento. **Skip da regola
  pivot.**
- I 6 modelli `Base*` sono **astratti** (o `BaseModel.php.backup-*`, non caricato) →
  nessuna factory.

## Analisi delle 6 migration (create vs alter)

Tutte usano `XotBaseMigration` (pattern `tableCreate` + `tableUpdate` idempotente:
crea-se-mancante, poi aggiunge/modifica colonne mancanti). Due sole tabelle fisiche:
`ratings` e `rating_morph`.

| Migration | Tabella | Natura effettiva |
|---|---|---|
| `2023_01_01_000000_create_ratings_table` | `ratings` | create + column-guard (create/alter ibrida) |
| `2023_01_01_000005_create_rating_morph_table` | `rating_morph` | create + column-guard (create/alter ibrida) |
| `2026_03_12_180000_create_ratings_table` | `ratings` | **alter** — re-run idempotente, aggiunge `slug` e colonne mancanti |
| `2026_03_27_000001_add_percentage_to_rating_morph_table` | `rating_morph` | **alter puro** (`Schema::table` add `percentage` + credit/count) |
| `2026_03_27_000002_add_percentage_..._on_rating_connection` | `rating_morph` (conn. `rating`) | **alter puro** — stessa alter sulla connessione `rating` |
| `2026_06_16_000003_create_ratings_table` | `ratings` | **alter** — aggiunge `txt`, `extra_attributes`, `is_disabled`, `is_readonly`, `order_column` |

Sintesi: 2 file "create" originari (comunque idempotenti/alter-safe) + 4 file di fatto
**alter** che estendono nel tempo le due tabelle. Il nome ripetuto `create_ratings_table`
è fuorviante: dal 2026 in poi quei file agiscono da migration incrementali sulla stessa
tabella `ratings`.
