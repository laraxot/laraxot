<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberProductInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberProductResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'description' => TextEntry::make('description'),
            'category' => TextEntry::make('category'),
            'subcategory' => TextEntry::make('subcategory'),
            'unit_of_measure' => TextEntry::make('unit_of_measure'),
            'barcode' => TextEntry::make('barcode'),
            'manufacturer' => TextEntry::make('manufacturer'),
            'is_active' => TextEntry::make('is_active'),
            'minimum_stock' => TextEntry::make('minimum_stock'),
            'warehouse_location' => TextEntry::make('warehouse_location'),
        ];
    }
}
