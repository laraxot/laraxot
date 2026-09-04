<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Filament\Actions;
use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListVehicles extends XotBaseListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
