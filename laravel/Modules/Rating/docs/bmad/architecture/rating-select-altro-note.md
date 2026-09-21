---
title: "Architecture — Select + Textarea (value/note) con altro"
type: architecture
module: Rating
status: decided
updated: 2026-09-16
qmd: "architecture HasRatingsTrait select textarea value note altro empty selectIsOther null"
related:
  - ../brainstorming/rating-select-altro-note-obbligatoria.md
  - ../epics/rating-select-altro.md
  - ../stories/5.99-select-altro-note-obbligatoria.story.md
  - rating-select-altro-implementation-spec.md
  - ../../stories/rating-altro-option-conditional-textarea-design.story.md
  - ../stories/5.141-has-rating-values-filter-note.story.md
---

# Architecture — Select + Textarea su rating con figli

## Contratto di business (canon utente)

Fonte primaria verificabile: [`../../prompts/01.md`](../../prompts/01.md) —
*«chiave "" o null»*; raffinato: **option `'other'` ≠ placeholder `null`**.

Quando un criterio (`rating`) **ha figli**:

1. Schema: **sempre** `Select` + `Textarea` (`Fieldset`, `columnSpan(2)`).
2. Select → `rating_morph.value`.
3. Textarea → `rating_morph.note`.
4. Textarea **required** solo se Select = option «altro» (`=== 'other'` via `selectIsOther`).
5. «altro» **non è un figlio**; lo aggiunge il trait.
6. Chiave option = **`'other'`** (`OTHER_OPTION_KEY`, stringa non vuota — NON `''`). Placeholder Select →
   stato **`null`**. Label option =
   `trans('rating::fields.altro')` (non `->label()` sul componente).

> **Anti-drift:** agenti paralleli hanno rivendicato «utente ha confermato `ALTRO_KEY='altro'`».
> Quella rivendicazione è **circolare** (si citano a vicenda) e **contraddetta** dal prompt
> scritto + commenti GH #57/#58 più recenti (`selectIsOther`, null ≠ altro).  
> `ALTRO_KEY='altro'` + dehydrate stringa = **D-7 superseded** (resta).  
> Anche la sentinella `''` è **superseded** (2026-09-16): `blank('')` è true
> (select.js `OptionStateCast` → `null`) quindi `selectIsOther('')` non scatta mai a runtime;
> fix: sentinella non-blank `'other'` (`OTHER_OPTION_KEY`).

## Perché `'other'` (non `''`) + placeholder `null`

| | Option `'other'` + placeholder null | Sentinella `''` (vecchia) |
|--|--------------------------------------|---------------------------|
| Persistenza `value` | `'other'` → `null` in DB + nota obbligatoria; placeholder null → `null` | `blank('')` true → `''` arriva a server come `null` via Livewire (`OptionStateCast`), senza sentinella testuale |
| «Non scelto» vs «altro» | Distinti: `null` vs `'other'` + `selectIsOther === 'other'` only | Collassati: `''` → `null`, `selectIsOther` non scatta MAI |
| Collisione figli | Chiavi reali = id interi → nessuna collisione con `'other'` | Id interi → nessuna collisione, ma permane il bug runtime |
| Rischio `blank()` | Assente: `'other'` è non-blank, resta distinguibile | Fatale: `blank('')` true |

**Decisione:** `required` note = `Get(select) === 'other'` — **mai** `blank()`.

## UI

```text
rating con children
  → Fieldset(columnSpan(2), markAsRequired)   // setUp: columns(2)
       Select(options = figli + 'other' => trans('rating::fields.altro'),
              placeholder → null, ->required(), no ->nullable(), ->live(),
              ->rules([Rule::in(keys stringati)]),  // MAI RuleEnum numeric
              ->afterStateUpdated(...))
       Textarea(note) sempre visibile
         ->required(fn(Get $get) => selectIsOther($get($select)))  // Component!
```

Senza figli: `TextInput` numerico (+ RuleEnum). Readonly: `TextEntry`.

> **5.152:** RuleEnum `numeric|…` sul Select con figli faceva fallire `'other'` prima
> della note required. Test deboli (`instanceof Closure`) non lo catturavano.
>
> **5.156:** `$get($field, isAbsolute: true)` toglieva `data.` del form Compila → note
> mai required. Fix: `$get($select)` sul Component Select.

## Nomi campo

```php
ratingFieldName($rating)           // …pivot.value
ratingFieldName($rating, 'note')   // …pivot.note
```

## Persistenza

| Select | `value` (DB) | `note` |
|--------|--------------|--------|
| id figlio | id | opzionale |
| «altro» (`'other'`) | `null` | **obbligatoria** |
| non scelto (`null`) | `null` | non required |

Hydrate: `value===null` + `note` filled → Select state **`'other'`**.  
Sync: `''`/`null`/non-numerico → pivot `value` = `null`, mai `0`.

## Consumer IR / D-8

- IR fill/save: story [5.140](../../../IndennitaResponsabilita/docs/bmad/stories/5.140-compila-altro-note-smoke.story.md) · IR#36
- Filtro «valutata»: [#60](https://github.com/laraxot/module_rating_fila5/issues/60) · story [5.141](../stories/5.141-has-rating-values-filter-note.story.md)

## Spec codice

[rating-select-altro-implementation-spec.md](rating-select-altro-implementation-spec.md)
