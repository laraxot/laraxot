<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignWorkCenterResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Signage\Filament\Resources\SignWorkCenterResource;

final class ListSignWorkCenters extends ListRecords
{
    protected static string $resource = SignWorkCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
