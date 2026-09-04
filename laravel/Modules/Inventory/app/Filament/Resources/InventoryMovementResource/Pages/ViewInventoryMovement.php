<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewInventoryMovement extends XotBaseViewRecord
{
    protected static string $resource = InventoryMovementResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'movement_type' => TextEntry::make('movement_type'),
            'product.name' => TextEntry::make('product.name'),
            'quantity' => TextEntry::make('quantity'),
            'unit_price' => TextEntry::make('unit_price'),
            'line_total' => TextEntry::make('line_total'),
            'fromWarehouse.name' => TextEntry::make('fromWarehouse.name'),
            'toWarehouse.name' => TextEntry::make('toWarehouse.name'),
            'fromLocation.code' => TextEntry::make('fromLocation.code'),
            'toLocation.code' => TextEntry::make('toLocation.code'),
        ];
    }
}
