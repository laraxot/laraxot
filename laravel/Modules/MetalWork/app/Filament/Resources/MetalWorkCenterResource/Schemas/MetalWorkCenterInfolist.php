<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalWorkCenterResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\MetalWork\Filament\Resources\MetalWorkCenterResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class MetalWorkCenterInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = MetalWorkCenterResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'code' => TextEntry::make('code'),
            'name' => TextEntry::make('name'),
            'hourly_rate' => TextEntry::make('hourly_rate'),
            'default_markup_percent' => TextEntry::make('default_markup_percent'),
            'sort_order' => TextEntry::make('sort_order'),
            'is_internal' => TextEntry::make('is_internal'),
            'context_code' => TextEntry::make('context_code'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
