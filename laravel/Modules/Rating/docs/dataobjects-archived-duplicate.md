---
title: DataObjects/RatingData archiviata — duplicato morto, usare Datas
type: decision
tags: [datas, dto, archive, duplicate, naming]
created: 2026-07-14
---

# DataObjects/RatingData rimossa definitivamente

La cartella `app/DataObjects/` e il file `RatingData.php.old` sono stati
**rimossi definitivamente** dal modulo Rating. Conteneva un duplicato morto
(final readonly class RatingData, non estende `Spatie\LaravelData\Data`)
senza nessun utilizzatore nel repo.

## Azione

- Eliminata `Modules/Rating/app/DataObjects/`
- Eliminato `Modules/Rating/app/DataObjects/RatingData.php.old`

## Regola

- Solo `Modules\<M>\Datas\<Nome>Data extends Spatie\LaravelData\Data`.
- Vedi `Modules/UI/docs/datas-not-dtos-convention.md`.
