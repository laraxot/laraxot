<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberWoodSpecies extends XotBaseEditRecord
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
