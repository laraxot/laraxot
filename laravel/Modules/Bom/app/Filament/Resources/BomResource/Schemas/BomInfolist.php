<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class BomInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = BomResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('id')->label('#'),
                        TextEntry::make('product.name')
                            ->label(__('boms.fields.product_id')),
                    ]),
                    TextEntry::make('name')
                        ->label(__('boms.fields.name'))
                        ->placeholder('—'),
                    IconEntry::make('is_active')
                        ->label(__('boms.fields.is_active'))
                        ->boolean(),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->label(__('boms.fields.updated_at')),
                ]),
        ];
    }
}
