<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;

final class ListTimberWoodSpecies extends ListRecords
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuova specie'),
        ];
    }
}
