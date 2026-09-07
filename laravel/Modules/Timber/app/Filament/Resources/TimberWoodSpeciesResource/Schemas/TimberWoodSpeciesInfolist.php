<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberWoodSpeciesInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'code' => TextEntry::make('code'),
            'sort_order' => TextEntry::make('sort_order'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
