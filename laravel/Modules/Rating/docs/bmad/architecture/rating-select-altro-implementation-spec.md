---
title: "Spec implementazione — Select+Textarea value/note (HasRatingsTrait)"
type: architecture
module: Rating
status: ready-for-dev
phase: documentation-only
updated: 2026-09-16
qmd: "implementation spec HasRatingsTrait selectIsOther OTHER_OPTION_KEY empty note"
related:
  - rating-select-altro-note.md
  - ../stories/5.99-select-altro-note-obbligatoria.story.md
  - ../stories/5.141-has-rating-values-filter-note.story.md
  - ../../stories/rating-altro-option-conditional-textarea-design.story.md
---

# Spec implementazione — codice che si vuole scrivere

> **Docs-only.** Canon: option «altro» = `''`; placeholder = `null`; `selectIsOther === ''` only.  
> **Non** `ALTRO_KEY='altro'` (superseded). SSoT: [rating-select-altro-note.md](rating-select-altro-note.md).

## GitHub

| | URL |
|--|-----|
| Design | https://github.com/laraxot/module_rating_fila5/issues/57 |
| Impl | https://github.com/laraxot/module_rating_fila5/issues/58 |
| Discussion | https://github.com/laraxot/module_rating_fila5/discussions/59 |
| D-8 | https://github.com/laraxot/module_rating_fila5/issues/60 |
| IR | https://github.com/provtv/module_indennitaresponsabilita_fila5/issues/36 |

## As-is

`buildRatingComponent` (~343–373): con figli → solo `Select` (id figli). Nessuna Textarea, nessuna option «altro».

IR Compila: fill solo `pivot.value`; save `is_numeric ? value : 0` (altro/blank → **0**).

## To-be — helpers

```php
private const string OTHER_OPTION_KEY = '';

/** Solo option '' = altro. null = non scelto. Mai blank(). */
private static function selectIsOther(Get $get, string $selectField): bool
{
    return $get($selectField) === self::OTHER_OPTION_KEY;
}

public static function ratingFieldName(BaseRating $rating, string $pivotColumn = 'value'): string
{
    return 'ratings.'.$rating->id.'.pivot.'.$pivotColumn;
}
```

## To-be — `buildRatingComponent` (ramo figli)

```php
$options[self::OTHER_OPTION_KEY] = trans('rating::fields.altro');

$select = Select::make($field)
    ->options($options)
    ->live()
    ->nullable()
    ->placeholder(trans('rating::fields.scegli')) // stato null
    ->rules((string) ($rating->rule->value ?? ''))
    ->afterStateUpdated($afterStateUpdated);

$note = Textarea::make(self::ratingFieldName($rating, 'note'))
    ->rows(3)
    ->required(static fn (Get $get): bool => self::selectIsOther($get, $field));

// UI: Fieldset — https://filamentphp.com/docs/5.x/schemas/layouts#fieldset-component
// Host: decorateRatingField → label($label). Trait non setta label (D-1).
return Fieldset::make()
    ->columnSpan(2)
    ->schema([$select, $note]);
```

Coda `rules|nullable|live` sul **Select**, non sul Fieldset. Senza figli / readonly invariati.

## To-be — IR fill/save

```php
// fill — remap UI: null+note → ''
foreach ($record->ratings as $ratingRow) {
    $id = (string) $ratingRow->id;
    $value = $ratingRow->pivot->value;
    $note = $ratingRow->pivot->note;
    if ($value === null && filled($note)) {
        $value = ''; // OTHER_OPTION_KEY
    }
    $data['ratings'][$id]['pivot']['value'] = $value;
    $data['ratings'][$id]['pivot']['note'] = $note;
}

// save — ''/null → null; mai cast a 0
foreach ($ratingsData as $id => $rating) {
    $pivot = (array) ($rating['pivot'] ?? []);
    $value = $pivot['value'] ?? null;
    $pivot['value'] = ($value === '' || $value === null)
        ? null
        : (is_numeric($value) ? $value : null);
    $record->ratings()->updateExistingPivot($id, $pivot);
}
```

Preferenza KISS: remap in host fill **oppure** helper Rating `normalizePivotValueForForm` se nascano altri host.

## Pest (prima del trait)

1. adds empty-key altro option when children
2. returns Group with Select + Textarea
3. does not require note when child id selected
4. does not require note when select is null (placeholder) — **regressione blank()**
5. requires note when select is `''`
6. TextInput path unchanged without children
7. ratingFieldName accepts `note`
8. IR smoke: fill remap; save note; save `''`→null not 0

## Checklist file

| File | Azione |
|------|--------|
| `HasRatingsTrait.php` | Group + helpers |
| `lang/*/…` | `fields.altro` (+ scegli se serve) |
| Pest unit | lista sopra |
| `CompilaIndennitaResponsabilita.php` | fill/save |
| `HasRatingValuesFilter.php` | dopo #58 → story 5.141 / #60 |

## Rischi

| ID | Mitigazione |
|----|-------------|
| D-8 filtro | [#60](https://github.com/laraxot/module_rating_fila5/issues/60) + [5.141](../stories/5.141-has-rating-values-filter-note.story.md) |
| Blank vs altro | `selectIsOther` + Pest #4 |
| IR cast 0 | story 5.140 / IR#36 |
