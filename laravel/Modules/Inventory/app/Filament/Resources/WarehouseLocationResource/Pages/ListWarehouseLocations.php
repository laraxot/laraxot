<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Actions;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListWarehouseLocations extends XotBaseListRecords
{
    protected static string $resource = WarehouseLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
