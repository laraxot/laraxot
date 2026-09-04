<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages;

use Filament\Actions;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListInventoryMovements extends XotBaseListRecords
{
    protected static string $resource = InventoryMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
