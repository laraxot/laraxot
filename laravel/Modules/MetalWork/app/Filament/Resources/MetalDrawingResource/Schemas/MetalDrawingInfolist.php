<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalDrawingResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalDrawingResource;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class MetalDrawingInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = MetalDrawingResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'part_code' => TextEntry::make('part_code'),
            'name' => TextEntry::make('name'),
            'bbox' => TextEntry::make('bbox')
                ->state(fn (?MetalDrawing $record): ?string => $record?->bbox_length_mm === null
                    ? null
                    : sprintf(
                        '%s × %s × %s',
                        number_format((float) $record->bbox_length_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_width_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_thickness_mm, 1, ',', '.'),
                    )),
            'solid_count' => TextEntry::make('solid_count'),
            'cylinder_count' => TextEntry::make('cylinder_count'),
            'is_assembly' => TextEntry::make('is_assembly'),
            'source_file' => TextEntry::make('source_file'),
            'cad_system' => TextEntry::make('cad_system'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
        ];
    }
}
