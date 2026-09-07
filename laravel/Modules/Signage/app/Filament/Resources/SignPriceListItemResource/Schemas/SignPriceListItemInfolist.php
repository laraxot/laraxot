<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SignPriceListItemInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SignPriceListItemResource::class;

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
                        TextEntry::make('code'),
                        TextEntry::make('category_code')
                            ->formatStateUsing(fn (string $state): string => __('signage::signage.categories.'.$state)),
                    ]),
                    TextEntry::make('work_center_code')->placeholder('—'),
                    TextEntry::make('name')->columnSpanFull(),
                    Grid::make(3)->components([
                        TextEntry::make('unit_code'),
                        TextEntry::make('unit_cost')->money('EUR', divideBy: 1),
                        TextEntry::make('currency'),
                    ]),
                    Grid::make(2)->components([
                        TextEntry::make('supplier_name')->placeholder('—'),
                        TextEntry::make('supplier_code')->placeholder('—'),
                    ]),
                    TextEntry::make('notes')->placeholder('—'),
                    IconEntry::make('is_active')->boolean(),
                ]),
        ];
    }
}
