---
title: "Design: opzione 'altro' con textarea obbligatoria nel Select dei rating figli"
epic: "5"
slug: rating-altro-option-conditional-textarea-design
status: ready-for-dev
module: Rating
created: 2026-09-15
updated: 2026-09-16
related:
  - ../bmad/epics/rating-select-altro.md
  - ../bmad/architecture/rating-select-altro-note.md
  - ../bmad/architecture/rating-select-altro-implementation-spec.md
  - ../bmad/brainstorming/rating-select-altro-note-obbligatoria.md
  - ../bmad/stories/5.99-select-altro-note-obbligatoria.story.md
  - ../bmad/stories/5.141-has-rating-values-filter-note.story.md
  - 5.99-rating-select-altro-textarea-obbligatoria.story.md
github_issues:
  - https://github.com/laraxot/module_rating_fila5/issues/57
  - https://github.com/laraxot/module_rating_fila5/issues/58
  - https://github.com/laraxot/module_rating_fila5/issues/60
github_discussion: https://github.com/laraxot/module_rating_fila5/discussions/59
---

# Design — Select «altro» + note

## BMAD pack (2026-09-16)

**SSoT contratto + codice proposto:** pack in [`../bmad/`](../bmad/README.md), in particolare:

- [architecture/rating-select-altro-note.md](../bmad/architecture/rating-select-altro-note.md)
- [architecture/rating-select-altro-implementation-spec.md](../bmad/architecture/rating-select-altro-implementation-spec.md)

Questo file resta il design storico D-1..D-8 **riallineato** al canone utente.  
**Fase:** solo documentazione — nessun codice.

## Chiave option — cronologia (e perché batte le rivendicazioni parallele)

| Quando | Versione | Fonte |
|--------|----------|-------|
| 2026-09-15 | `ALTRO_KEY = 'altro'` (stringa) + dehydrate | bozza design iniziale |
| 2026-09-16 a.m. | chiave `''`/`null` (spesso collassate) | prompt utente in [`../prompts/01.md`](../prompts/01.md): *«chiave "" o null»* |
| 2026-09-16 | raffinamento **null ≠ `''`** | placeholder → `null`; option altro → `''`; `selectIsOther === ''` only; **mai** `blank()` |
| 2026-09-16 | alcuni agenti hanno ri-scritto «utente ha confermato `'altro'`» | **contraddetto** dal prompt scritto + commenti GH #57/#58/#59 più recenti |

**Canon attuale (non riaprire senza nuovo prompt utente verificabile):**

- Option «altro» = **`''`** (`OTHER_OPTION_KEY`)
- Placeholder Select = **`null`** («non scelto»)
- `note` required solo se `Get(select) === ''`
- Vecchia `ALTRO_KEY = 'altro'` + `dehydrateStateUsing` string→null = **D-7 superseded**

Evidence GH: commenti «Canon raffinato: null ≠ altro» e «Gate `selectIsOther`» su #57/#58.

## Meccanismo attuale (`HasRatingsTrait::buildRatingComponent`)

Verificato su `HasRatingsTrait.php` (~343–373):

1. `is_readonly === true` → `TextEntry`
2. Con figli → **oggi solo** `Select` (options = id figli). **Target:** `Group(Select + Textarea)` sempre
3. Senza figli → `TextInput` numerico (invariato)

Nessuna option «altro» oggi. Campo testo: `rating_morph.note` già in migration — **nessuna migration nuova**.

`ratingFieldName` oggi solo `…pivot.value`; to-be: secondo argomento `$pivotColumn = 'value'|'note'`.

## Decisioni (canone)

### D-1 — Chiave option «altro» (supersede stringa `'altro'`)

```php
private const string OTHER_OPTION_KEY = '';
private static function selectIsOther(Get $get, string $selectField): bool
{
    return $get($selectField) === self::OTHER_OPTION_KEY; // mai blank()
}
```

| Stato Select | Significato | note required |
|--------------|-------------|----------------|
| `null` | non scelto (placeholder) | no |
| `''` | option «altro» | **sì** |
| int > 0 | id figlio | no |

### D-2 — Storage testo

`rating_morph.note`. `ratingFieldName($rating, 'note')`.

### D-3 — Label option

`trans('rating::fields.altro')` nel **valore** dell’array options — non `->label()` sul componente (D-1 story 5.92).

### D-4 — Presenza e validazione

Select + Textarea **sempre** visibili (`Group`). Nessun `->visible()` sulla note.

```php
Textarea::make(self::ratingFieldName($rating, 'note'))
    ->required(static fn (Get $get): bool => self::selectIsOther($get, $field));
```

Coda `nullable|rules|live|afterStateUpdated` resta sul **Select**, non sul Group.

### D-5 — `recalculateRatingFields`

Non reagisce a `note` (solo value numerico readonly).

### D-6 — Opt-in

Nessun flag: con figli, «altro» sempre in coda options.

### D-7 — Mapping form ↔ `value` (**superseded** come sentinella stringa)

Con chiave `''`, dehydrate speciale stringa→null **non serve**: form `''` e DB `null` si allineano in save host (`''`→`null`).  

Hydrate round-trip: se `value===null` e `note` filled → Select state **`''`** (non placeholder). Vedi implementation-spec § fill.

### D-8 — `HasRatingValuesFilter`

`value` null/0 = non valutata oggi → schede solo-note false negative.  
**Issue:** [#60](https://github.com/laraxot/module_rating_fila5/issues/60) · story [5.141](../bmad/stories/5.141-has-rating-values-filter-note.story.md). Preferenza: note filled = valutata.

## Gap host IR

`CompilaIndennitaResponsabilita` fill/save solo `value` (+ cast non-numerici a `0`).  
Story [IR/5.140](../../IndennitaResponsabilita/docs/bmad/stories/5.140-compila-altro-note-smoke.story.md) · issue [IR#36](https://github.com/provtv/module_indennitaresponsabilita_fila5/issues/36).

## Acceptance (implementazione)

- [ ] `OTHER_OPTION_KEY = ''` + `selectIsOther` (Pest: null non required; `''` required)
- [ ] Placeholder Select; Group Select+Textarea; no `->label()`; lang `fields.altro`
- [ ] IR 5.140 pass-through note; no cast a `0`
- [ ] D-8 #60 / 5.141 esplicitato (dopo #58)
- [ ] PHPStan level max Rating

## Fuori scope

- Flag per disabilitare «altro» per rating
- `maxLength` su note
- Cambiare ricalcolo readonly
