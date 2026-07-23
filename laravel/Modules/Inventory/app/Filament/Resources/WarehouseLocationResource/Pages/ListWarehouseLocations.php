<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;

final class ListWarehouseLocations extends ListRecords
{
    protected static string $resource = WarehouseLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
