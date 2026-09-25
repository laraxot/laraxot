---
title: "Brainstorm — home di ratingXlsFields vs RatingData"
type: brainstorming
module: Rating
status: closed
created: 2026-09-23
updated: 2026-09-23
qmd: "brainstorm getXlsFields RatingData trait schemaless percentuali"
related:
  - ../architecture/rating-xls-fields-reusable.md
  - ../stories/18.60-rating-xls-fields-catalog.story.md
---

# Brainstorm — dove vive il pezzo riusabile

## Domanda

Spostare il blocco “foreach rating → path/label (+ note se children)” fuori da
`IndennitaResponsabilitaResource::getXlsFields`. Dove?

## Voti interni (peso decisione)

| Casa | Peso | Motivo in una riga |
|------|------|--------------------|
| HasRatingsTrait | 62% | già path/label/sync + schemaless |
| Action Spatie | 48% | religione Action; forse dopo |
| BaseRating static | 40% | modello giusto, grasso |
| RatingData | 28% | preferenza utente ma DTO già bifronte |
| Solo Resource | 12% | status quo anti-DRY |

## Obiezione utente su RatingData

Valida come *istinto* (“roba dati rating”). Debole come *file attuale*:
`RatingData` = blocco UI + `updateColumns` migration. Terzo ruolo senza split
= debito. Canon: o split Block/Entity, o non usare quel file per query export.

## Schemaless

Non è più una decisione aperta: `withExtraAttributes` è il verbo. Eventuale
allineamento di `getRatingsWhere` (ancora `extra_attributes->{$key}` raw) è
igiene separata.
