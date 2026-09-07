<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberPriceListResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberPriceListInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberPriceListResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'name' => TextEntry::make('name'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
