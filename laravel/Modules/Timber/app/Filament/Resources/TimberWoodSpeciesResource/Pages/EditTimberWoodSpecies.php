<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;

final class EditTimberWoodSpecies extends EditRecord
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
