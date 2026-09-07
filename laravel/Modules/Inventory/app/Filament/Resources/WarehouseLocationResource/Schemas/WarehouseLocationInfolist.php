<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class WarehouseLocationInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
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
