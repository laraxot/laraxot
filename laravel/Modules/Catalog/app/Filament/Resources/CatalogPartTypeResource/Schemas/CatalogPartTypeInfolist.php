<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class CatalogPartTypeInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = CatalogPartTypeResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()->columnSpanFull()->components([
                TextEntry::make('name')
                    ->label(__('catalog.fields.name')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label(__('catalog.fields.updated_at')),
            ]),
        ];
    }
}
