---
title: "RatingData::getXlsFields($where) — resolve subclass dal backtrace statico"
type: architecture
module: Rating
status: superseded
created: 2026-09-23
updated: 2026-09-23
qmd: "RatingData getXlsFields resolveRatingClassFromCaller backtrace Filament Resources connection"
related:
  - ../stories/5.230-ratingdata-resolve-caller-static.story.md
  - ./form-field-label-and-xls-path-home.md
  - ../../../../IndennitaResponsabilita/docs/bmad/stories/5.179-rating-xls-fields-reusable-component.story.md
  - ../../../../IndennitaResponsabilita/docs/prompts/04.md
---

> **Superseded (2026-09-23)** — vedi
> [`5.232`](../stories/5.234-ratingdata-ratingclass-required-revert-backtrace.story.md).
> `$ratingClass` torna required, `resolveRatingClassFromCaller()` rimosso:
> il backtrace non porta il frame originale nella call chain asincrona
> (`XotBaseExporter::resolveColumns()` dopo deserializzazione job).

# Perché `...RatingData::getXlsFields($where)` senza FQCN

## Preferenza utente

```php
$where = ['anno' => $anno, 'type' => $type];
return [...$fields, ...RatingData::getXlsFields($where)];
```

## Perché non bastava `type` nel `$where` su Rating piattaforma

`Modules\IndennitaResponsabilita\Models\Rating` ha
`protected $connection = 'indennita_responsabilita'`. Il Rating del modulo Rating
usa il DB default. Stesso `type=dip` sulla connection sbagliata = export vuoto/errato.

## Perché `Rating::getClassName()` non bastava

Cerca solo frame con **`object`**. `Resource::getXlsFields` è statico → exception.
Debate: [explore getClassName](b9bef0aa-1779-42d6-a10b-848d114c1699).

## Soluzione

`RatingData::resolveRatingClassFromCaller()` legge anche `['class']` nei frame
sotto `Modules\*\Filament\` o `Models\`, costruisce `{ns}\Models\Rating`.

Override esplicito resta: `getXlsFields($where, $ratingClass)`.

## API Resource (allineata al prompt)

Solo `$where` + spread. Nessun `array_merge`, nessun `HasRatingsTrait` nel Resource.
