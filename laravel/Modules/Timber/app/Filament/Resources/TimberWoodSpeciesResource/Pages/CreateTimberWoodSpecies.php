<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;

final class CreateTimberWoodSpecies extends XotBaseCreateRecord
{
    protected static string $resource = TimberWoodSpeciesResource::class;
}
