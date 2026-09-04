<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewWarehouse extends XotBaseViewRecord
{
    protected static string $resource = WarehouseResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
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
