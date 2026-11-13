---
title: "Rating: Brainstorming BMAD (indice e decisioni)"
type: note
module: Rating
tags:
  - bmad
  - rating
  - brainstorming
  - decisioni
created: 2026-09-28
updated: 2026-09-28
qmd: "rating brainstorming decisioni aperte scartate select altro export"
related:
  - architecture.md
  - architecture/rating-select-altro-note.md
  - brainstorming/rating-select-altro-note-obbligatoria.md
  - epics/rating-export-ssot.md
status: draft
---

# Rating: Brainstorming

> **SUMMARY**: Decisioni prese, questioni aperte e opzioni scartate del modulo `Rating`,
> ancorate a file e simboli reali. Il brainstorming di dettaglio per area e' negli shard.

## Shard

| Shard | Path |
|-------|------|
| Nota obbligatoria «altro» | [brainstorming/rating-select-altro-note-obbligatoria.md](brainstorming/rating-select-altro-note-obbligatoria.md) |
| Metodi export | [brainstorming/rating-data-export-methods.md](brainstorming/rating-data-export-methods.md) |
| Campi export riusabili | [brainstorming/rating-export-fields-reusable.md](brainstorming/rating-export-fields-reusable.md) |
| Componente tabella | [brainstorming/rating-table-component.md](brainstorming/rating-table-component.md) |
| Home campi XLS | [brainstorming/rating-xls-fields-home.md](brainstorming/rating-xls-fields-home.md) |
| Opportunita di modulo | [brainstorming/module-opportunities.md](brainstorming/module-opportunities.md) |

## Decisioni prese (verificate nel codice)

| Decisione | Dove e verificata |
|----------|-------------------|
| Il contratto del criterio vive in `app/Models/Contracts/` e non in `app/Contracts/` | `app/Models/Contracts/RatingContract.php`: porta `@phpstan-require-extends Model` |
| Chi consuma un criterio dipende dal contratto, non dalla concreta | `RatingContract.php` docblock: `Rating` e' consumato da piu' moduli con concrete diverse e connessioni diverse |
| L'albero fa parte del contratto | `RatingContract extends HasRecursiveRelationshipsContract` |
| L'etichetta leggibile e' metodo del contratto | `RatingContract::getLabel()` |
| L'export ha una sola sorgente statica | `app/Datas/RatingData.php` con `ratingValuePath()`, `ratingXlsValuePath()`, `ratingFieldName()`, `formFieldLabel()`, `getXlsFields()`, `criteriaToXlsFields()` |
| La chiave dell'opzione «altro» e' la stringa `'other'` | `app/Models/Traits/HasRatingsTrait.php`, costante `private const string OTHER_OPTION_KEY = 'other'` |
| Il fill/save del pivot generico sta nel trait, non nel form host | `HasRatingsTrait::hydrateRatingsFormData()` e `syncRatingsFormData()` |
| La decorazione di default del campo e' centralizzata | `app/Filament/Concerns/DecoratesRatingFormFields::applyDefaultRatingFieldDecoration()` |
| Il blocco form estende la base Xot | `app/Filament/Forms/Components/RatingsSection.php`: `class RatingsSection extends XotBaseSection` |
| Il calcolo "gia valutato" non e' duplicato | docblock di `RatingsSection.php` (riga 18): evita doppio calcolo con `RatingsColumn::isRated()` |

## Questioni aperte

| Questione | Evidenza |
|-----------|----------|
| Il modulo non dichiara rotte: dove finisce l'API dei rating? | `routes/web.php` e `routes/api.php` contengono solo `declare(strict_types=1);` |
| Esistono 8 migrazioni attive sulla stessa tabella `ratings` / `rating_morph` | `database/migrations/` con duplicati in `_archive_redundant/` e `_bak/` |
| `config/config.php` non espone nulla oltre a navigazione | `config/config.php`: solo `name`, `icon`, `navigation_sort` |
| `RatingPhpstanTraitProbe.php` vive fra i modelli | `app/Models/RatingPhpstanTraitProbe.php` |
| Restano file non canonici nella stessa directory | `app/Models/BaseModel.php.backup-20251015-092511`, `app/Models/Traits/RatingTrait.php.old` |
| `app/Http/Livewire/` contiene solo `_components.json` | nessun componente Livewire del modulo |
| La directory `app/Filament/Resources/HasRatingResource/` non contiene alcuna classe Resource | contiene solo `RelationManagers/RatingsRelationManager.php` e `Widgets/StatsOverview.php` |

## Opzioni scartate

| Opzione | Motivo dello scarto (dal codice/storia) |
|---------|---------------------------------------------|
| Sentinella `''` per l'opzione «altro» | collassa a `null` e `selectIsOther()` non scatta piu': canone `'other'` + placeholder `null` (vedi [architecture/rating-select-altro-note.md](architecture/rating-select-altro-note.md)) |
| Validazione `RuleEnum` numerica sulla select con figli | `'other'` fallirebbe prima che la nota venga marcata obbligatoria: usata `Rule::in(chiavi options)` |
| Logica export dentro i modelli | accentrata in `app/Datas/RatingData.php` come SSoT |
| Fill/save del pivot duplicato nel form host | pass-through generico in `HasRatingsTrait` |
