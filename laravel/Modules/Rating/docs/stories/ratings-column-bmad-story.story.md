---
id: ratings-column-bmad-story
slug: ratings-column-bmad-story
title: "Colonna Ratings: introduzione di RatingsColumn in BaseSchedasTable"
description: "Story BMAD per l'aggiunta di RatingsColumn al modello BaseSchedasTable per mostrare medie valutazioni"
document_type: story
category: ptvx
status: ready-for-dev
version: 1.0.0
language: it-IT
project: PTVX
ecosystem: Laraxot
domain: performance-evaluation
priority: medium
source_of_truth: true
scope: [modules]
audience: [ai-agents, developers, maintainers]
depends_on:
- ../md-conventions-rules.md
- bmad-method.md
related:
- ../../app/Filament/Tables/Columns/RatingsColumn.php
- ../../app/Filament/Resources/SchedaResource/Tables/BaseSchedasTable.php
- ../../docs/chat/claim-worker-type-filter.md
tags:
- ptvx
- ratings
- column
- bmad
- filament
- naming-conventions
- no-dates-in-filename
- frontmatter-yaml
- laraxot
---
# RatingsColumn — story BMAD

## Contesto

`BaseSchedasTable` (modulo Ptv) gestisce la lista delle schede di valutazione. La relazione con `Rating` (`BaseRating`) aggiunge le colonne aggregate `ratings_avg` e `ratings_count`. Serve un filtro per capire se le valutazioni sono state popolate con valore diverso da zero, e una colonna per mostrare la media.

## Proposta di colonna

File: `laravel/Modules/Rating/app/Filament/Tables/Columns/RatingsColumn.php`

- Tipo: `TextColumn` Filament 5 esteso (non estendere `Column` generico)
- Label: "Media valutazioni"
- Formattazione: `float` con 1 cifra decimale o trattino (`—`) quando vuoto
- Ordinabile (usando `ratings_avg` come valore di ordinamento)
- Non richiede join con tabella separata: usa il campo `ratings_avg` già presente nel modello

## Implementazione

File: `laravel/Modules/Rating/app/Filament/Tables/Columns/RatingsColumn.php`

```php
use Filament\Tables\Columns\TextColumn;

class RatingsColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'ratings_avg')
            ->label('Media valutazioni')
            ->formatStateUsing(static function (?float $state): string {
                return $state !== null && $state > 0
                    ? number_format($state, 1, '.', ',')
                    : '—';
            })
            ->numeric()
            ->sortable();
    }
}