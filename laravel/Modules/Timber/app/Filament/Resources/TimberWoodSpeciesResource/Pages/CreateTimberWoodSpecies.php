<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;

final class CreateTimberWoodSpecies extends CreateRecord
{
    protected static string $resource = TimberWoodSpeciesResource::class;
}
