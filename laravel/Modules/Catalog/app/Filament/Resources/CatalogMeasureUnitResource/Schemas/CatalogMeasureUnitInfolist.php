<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class CatalogMeasureUnitInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = CatalogMeasureUnitResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextEntry::make('name')
                        ->label(__('catalog.fields.name')),
                    TextEntry::make('symbol')
                        ->label(__('catalog.fields.symbol')),
                ]),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label(__('catalog.fields.updated_at')),
            ]),
        ];
    }
}
