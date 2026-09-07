<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class ProductInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = ProductResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    TextEntry::make('name')
                        ->label(__('catalog.fields.name')),
                    TextEntry::make('sku')
                        ->label(__('catalog.fields.sku')),
                    TextEntry::make('description')
                        ->label(__('catalog.fields.description'))
                        ->placeholder('—')
                        ->columnSpanFull(),
                    Grid::make(2)->components([
                        TextEntry::make('unit_price')
                            ->label(__('catalog.fields.unit_price'))
                            ->money('EUR')
                            ->placeholder('—'),
                        IconEntry::make('is_active')
                            ->label(__('catalog.fields.is_active'))
                            ->boolean(),
                    ]),
                    Grid::make(2)->components([
                        TextEntry::make('model.name')
                            ->label(__('catalog.model'))
                            ->placeholder('—'),
                        TextEntry::make('partType.name')
                            ->label(__('catalog.part_type'))
                            ->placeholder('—'),
                    ]),
                    Grid::make(2)->components([
                        TextEntry::make('measureUnit.name')
                            ->label(__('catalog.measure_unit'))
                            ->placeholder('—'),
                        TextEntry::make('frequency.name')
                            ->label(__('catalog.frequency'))
                            ->placeholder('—'),
                    ]),
                ]),
        ];
    }
}
