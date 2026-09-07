<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignPriceListResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SignPriceListInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SignPriceListResource::class;

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
                        TextEntry::make('name'),
                    ]),
                    Grid::make(3)->components([
                        TextEntry::make('brand')->placeholder('—'),
                        TextEntry::make('customer.name')->placeholder('—'),
                        TextEntry::make('default_markup_percent')
                            ->suffix('%')
                            ->placeholder('—'),
                    ]),
                    IconEntry::make('is_active')->boolean(),
                ]),
        ];
    }
}
