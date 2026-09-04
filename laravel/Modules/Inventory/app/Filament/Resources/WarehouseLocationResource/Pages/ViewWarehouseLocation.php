<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewWarehouseLocation extends XotBaseViewRecord
{
    protected static string $resource = WarehouseLocationResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'warehouse.name' => TextEntry::make('warehouse.name'),
            'code' => TextEntry::make('code'),
            'label' => TextEntry::make('label'),
            'row' => TextEntry::make('row'),
            'column' => TextEntry::make('column'),
            'level' => TextEntry::make('level'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
