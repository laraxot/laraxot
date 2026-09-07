<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberStockMovementInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberStockMovementResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'movement_date' => TextEntry::make('movement_date')->date(),
            'direction_code' => TextEntry::make('direction_code'),
            'product.description' => TextEntry::make('product.description'),
            'quantity' => TextEntry::make('quantity'),
            'unit_of_measure' => TextEntry::make('unit_of_measure'),
            'customer.business_name' => TextEntry::make('customer.business_name'),
            'document_type_code' => TextEntry::make('document_type_code'),
            'document_reference' => TextEntry::make('document_reference'),
            'lot_code' => TextEntry::make('lot_code'),
            'unit_cost' => TextEntry::make('unit_cost'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
