---
title: "Rating: Quick Reference"
type: note
module: Rating
tags:
  - bmad
  - rating
  - quick-reference
created: 2026-09-28
updated: 2026-10-07
qmd: "rating quick reference trait ratingdata risorse filament test"
related:
  - README.md
  - architecture.md
  - setup-guide.md
status: draft
---

# Rating: Quick Reference

> **SUMMARY**: Riferimento rapido del modulo `Rating`: dove sta cosa, quali simboli usare,
> quali test leggere prima di toccare il codice. Paths relativi a `laravel/Modules/Rating`.

## SSoT e contratti

| Simbolo | Path |
|---------|------|
| `RatingData` (SSoT export/path/label) | `app/Datas/RatingData.php` |
| `RatingContract` | `app/Models/Contracts/RatingContract.php` |
| `HasRatingContract` | `app/Models/Contracts/HasRatingContract.php` |
| `RatingsFormCallerContract` | `app/Contracts/RatingsFormCallerContract.php` |
| `HasLikeContract` | `app/Contracts/HasLikeContract.php` |
| `RuleEnum` | `app/Enums/RuleEnum.php` |
| `SupportedLocale` | `app/Enums/SupportedLocale.php` |

Metodi chiave di `RatingData`: `ratingValuePath()`, `ratingXlsValuePath()`, `ratingFieldName()`,
`formFieldLabel()`, `getXlsFields()`, `criteriaToXlsFields()`, `updateColumns()`.

## Modelli

| File | Ruolo |
|------|-------|
| `app/Models/BaseRating.php` | base astratta del criterio (media, slug, sortabile, albero) |
| `app/Models/Rating.php` | concreta del catalogo |
| `app/Models/BaseRatingMorph.php` | base del pivot con `rating()`, `user()`, `profile()`, `model()` |
| `app/Models/RatingMorph.php` | concreta del pivot |
| `app/Models/BaseMorphPivot.php` | base pivot |
| `app/Models/AbstractRatingsHost.php` | base host |
| `app/Models/Like.php` | like |

## Trait host

| Metodo di `HasRatingsTrait` | Path |
|----------------------------|------|
| `ratingMorphs()`, `ratings()`, `ratingObjectives()`, `myRatings()` | `app/Models/Traits/HasRatingsTrait.php` |
| `ratingFormFields()`, `getRatingsFormSchema()` | idem |
| `hydrateRatingsFormData()`, `syncRatingsFormData()`, `clearRatingsFormData()` | idem |
| `getRatingsRules()`, `getRatingsValidationAttributes()` | idem |
| `ratingsById`, `ratingsAvg`, `ratingsCount`, `myRating` (accessor) | idem |
| `OTHER_OPTION_KEY = 'other'` (costante privata) | idem |

## Action

| Azione | Path |
|--------|------|
| Opzioni rating per host | `app/Actions/HasRating/GetRatingOptsByModelAction.php` |
| Conteggio host + rating | `app/Actions/HasRating/GetCountByModelRatingIdAction.php` |
| Somma host + rating | `app/Actions/HasRating/GetSumByModelRatingIdAction.php` |

## Filament

| Simbolo | Path |
|---------|------|
| Catalogo rating | `app/Filament/Resources/RatingResource.php` |
| Pivot rating-host | `app/Filament/Resources/RatingMorphResource.php` |
| Vista lato host | `app/Filament/Resources/HasRatingResource/` (directory: nessuna classe Resource, solo RelationManagers e Widgets) |
| Sezione form ratings | `app/Filament/Forms/Components/RatingsSection.php` |
| Decorazione campi | `app/Filament/Concerns/DecoratesRatingFormFields.php` |
| Colonna ratings | `app/Filament/Tables/Columns/RatingsColumn.php` |
| Filtro valori | `app/Filament/Tables/Filters/HasRatingValuesFilter.php` |
| Blocchi/pagine/widget | `app/Filament/Blocks/Rating.php`, `app/Filament/Pages/Dashboard.php`, `app/Filament/Widgets/StatsOverview.php` |

## Config e provider

- `config/config.php`: solo `name`, `icon`, `navigation_sort`.
- Provider: `app/Providers/RatingServiceProvider.php`, `app/Providers/Filament/AdminPanelProvider.php`,
  `app/Providers/RouteServiceProvider.php`, `app/Providers/EventServiceProvider.php`.
- `routes/web.php` e `routes/api.php` sono vuoti di rotte.

## Traduzioni

`lang/it/fields.php` (etichette campo, incluso `altro`), `lang/it/rating.php`, `lang/it/ratings.php`,
`lang/it/rating_form.php`, `lang/it/rating_infolist.php`, `lang/it/rating_morph.php`,
`lang/it/ratings_section.php`, `lang/it/child.php`, `lang/it/enums.php`, `lang/it/rule_enum.php`,
`lang/it/has_rating_values.php`, `lang/it/favorites.php`.

## Test da leggere prima di toccare il codice

| Test | Path |
|------|------|
| Trait: accessors | `tests/Unit/HasRatingsTraitAccessorsTest.php` |
| Trait: form data pivot | `tests/Unit/HasRatingsTraitFormDataTest.php` |
| Trait: opzione «altro» | `tests/Unit/HasRatingsTraitOtherOptionTest.php` |
| Trait: label campo | `tests/Unit/HasRatingsTraitFormFieldLabelTest.php` |
| Trait: `ratingsById` | `tests/Unit/HasRatingsTraitRatingsByIdTest.php` |
| SSoT export | `tests/Unit/RatingDataCriteriaToXlsFieldsTest.php` |
| SSoT label | `tests/Unit/RatingDataFormFieldLabelTest.php` |
| Export su modello | `tests/Unit/BaseRatingXlsExportValueTest.php` |
| Action host | `tests/Unit/HasRatingActionsTest.php` |
| Filament | `tests/Unit/RatingFilamentSchemaTest.php`, `RatingFilamentExtendedTest.php`, `RatingFilamentRelationManagerTest.php` |
| Modelli | `tests/Unit/BaseRatingModelTest.php`, `RatingMorphModelTest.php` |
| API | `tests/Feature/RatingApiTest.php` |

## Enum

- `RuleEnum` (`app/Enums/RuleEnum.php`): regole di validazione Laravel, non opzioni. Casi: `Null` (`''`), `ZeroFour`, `ZeroFive`, `ZeroSix`, `ZeroOrMin4Max25`, `NullableNumericMin0Max25`.
- `SupportedLocale` (`app/Enums/SupportedLocale.php`): `IT` (`it`), `EN` (`en`).

## Altri simboli del modulo

- Trait in `app/Models/Traits/`: `HasRatingsTrait` (form pivot), `HasRating`, `HasLikes`, `RatingTrait`.
- Resource base in `app/Filament/Resources/`: `BaseRatingResource`, `BaseRatingMorphResource`, oltre a `RatingResource` e `RatingMorphResource`.
- `app/Aggregates/BettableAggregate.to_predict` e `BettableAggregate.to_rating`: due file non PHP (estensione `.to_*`) con una `AggregateRoot` Spatie EventSourcing vuota; non sono stati di un host e non sono autoloadati.

## Pattern del modulo

- Criterio con figli: Select e Textarea sempre visibili in un `Fieldset`; `Fieldset::setUp()` imposta gia `columns(2)`, quindi si usa `columnSpan(2)` sul wrapper (vedi `HasRatingsTrait`, blocco `Fieldset::make()`).
- Select scrive `pivot.value`; Textarea scrive `pivot.note` tramite `ratingFieldName(..., 'note')`.
- Opzione «altro» = chiave `'other'` (`OTHER_OPTION_KEY`), mai `''`: la stringa vuota collassa a `null` e la nota non diventa obbligatoria.
- Il gate e' `selectIsOther($value)`, cioe' `$value === 'other'`; mai `blank()`.
- Validazione della Select con figli: `Rule::in(chiavi delle options)`, non `RuleEnum`.
- Fill e save generici: `hydrateRatingsFormData()` e `syncRatingsFormData()`; gli host non duplicano il pivot.
- Nelle firme tipizzare su `RatingContract` (o `HasRatingContract`), mai su `BaseRating`.

## Verifica

```bash
cd laravel
php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules/Rating
./vendor/bin/pest Modules/Rating
./vendor/bin/pint
```

Sull'host `10.100.200.15` non si lanciano test Pest.

## Comandi BMAD per Rating

Flusso consigliato (help: `bmad-help`):

| Fase | Comandi |
|---|---|
| 1 Analisi | `bmad-domain-research` (valutazioni, opzioni, note), `bmad-technical-research` (attributi schemaless, morph pivot) |
| 2 Pianificazione | `bmad-create-prd`, `bmad-create-architecture` (BaseRating, BaseRatingMorph, host) |
| 3 Soluzione | `bmad-create-epics-and-stories`, `bmad-check-implementation-readiness` |
| 4 Implementazione | `bmad-sprint-planning`, `bmad-create-story`, `bmad-dev-story`, `bmad-code-review` |

Agenti: Mary (`bmad-agent-analyst`), John (`bmad-agent-pm`), Winston (`bmad-agent-architect`), Amelia (`bmad-agent-dev`), Quinn (`bmad-agent-qa`).
Scorciatoie: `bmad-quick-dev "<richiesta>"`, `bmad-quick-spec "<richiesta>"`.
Le story vanno in `docs/bmad/stories/` di questo modulo.
