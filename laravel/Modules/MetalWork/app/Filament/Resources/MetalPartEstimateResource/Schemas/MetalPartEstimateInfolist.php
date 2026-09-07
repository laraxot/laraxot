<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalPartEstimateResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class MetalPartEstimateInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = MetalPartEstimateResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'part_code' => TextEntry::make('part_code'),
            'name' => TextEntry::make('name'),
            'quantity' => TextEntry::make('quantity'),
            'gross_weight_kg' => TextEntry::make('gross_weight_kg'),
            'total_cost' => TextEntry::make('total_cost'),
            'sale_price' => TextEntry::make('sale_price'),
            'rawMaterial.code' => TextEntry::make('rawMaterial.code'),
            'quotation.code' => TextEntry::make('quotation.code'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
        ];
    }
}
