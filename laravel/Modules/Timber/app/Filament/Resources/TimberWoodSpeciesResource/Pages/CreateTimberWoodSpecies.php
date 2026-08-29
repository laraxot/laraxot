<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberWoodSpecies extends XotBaseCreateRecord
{
    protected static string $resource = TimberWoodSpeciesResource::class;
}
