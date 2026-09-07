<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class WarehouseInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'code' => TextEntry::make('code'),
            'vehicle.plate_number' => TextEntry::make('vehicle.plate_number'),
            'is_active' => TextEntry::make('is_active'),
            'is_final_destination' => TextEntry::make('is_final_destination'),
            'updated_at' => TextEntry::make('updated_at'),
        ];
    }
}
