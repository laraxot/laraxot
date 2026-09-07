<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class MetalRawMaterialInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = MetalRawMaterialResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'code' => TextEntry::make('code'),
            'description' => TextEntry::make('description'),
            'family_code' => TextEntry::make('family_code'),
            'unit_code' => TextEntry::make('unit_code'),
            'steel_grade' => TextEntry::make('steel_grade'),
            'thickness_mm' => TextEntry::make('thickness_mm'),
            'width_mm' => TextEntry::make('width_mm'),
            'height_mm' => TextEntry::make('height_mm'),
            'length_mm' => TextEntry::make('length_mm'),
            'density_kg_dm3' => TextEntry::make('density_kg_dm3'),
            'weight_per_meter_kg' => TextEntry::make('weight_per_meter_kg'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
