<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class ProductionOrderInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'product.name' => TextEntry::make('product.name'),
            'quantity' => TextEntry::make('quantity')->numeric(),
            'status' => TextEntry::make('status')->badge(),
            'due_at' => TextEntry::make('due_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
