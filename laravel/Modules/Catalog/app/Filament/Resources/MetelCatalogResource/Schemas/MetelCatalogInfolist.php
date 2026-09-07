<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class MetelCatalogInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = MetelCatalogResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columns(2)
                ->components([
                    TextEntry::make('code')
                        ->label(__('metel.fields.code')),
                    TextEntry::make('brand_code')
                        ->label(__('metel.fields.brand_code')),
                    TextEntry::make('name')
                        ->label(__('metel.fields.name')),
                    TextEntry::make('manufacturer')
                        ->label(__('metel.fields.manufacturer')),
                    TextEntry::make('source_type')
                        ->label(__('metel.fields.source_type'))
                        ->formatStateUsing(fn (string $state): string => __("metel.sources.{$state}")),
                    TextEntry::make('version')
                        ->label(__('metel.fields.version'))
                        ->placeholder('—'),
                    Grid::make(2)->components([
                        TextEntry::make('items_count')
                            ->label(__('metel.fields.items_count')),
                        TextEntry::make('imported_at')
                            ->label(__('metel.fields.imported_at'))
                            ->dateTime(),
                    ]),
                    IconEntry::make('is_active')
                        ->label(__('metel.fields.is_active'))
                        ->boolean(),
                ]),
        ];
    }
}
