<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class InventoryMovementInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
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
