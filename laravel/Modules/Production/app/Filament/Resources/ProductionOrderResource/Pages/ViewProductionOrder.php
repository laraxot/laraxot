<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewProductionOrder extends XotBaseViewRecord
{
    protected static string $resource = ProductionOrderResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
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
