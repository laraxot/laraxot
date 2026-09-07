<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Actions\PriceSignCatalogItemAction;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SignCatalogItemInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SignCatalogItemResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        /** @var view-string $pricingView */
        $pricingView = 'signage::filament.catalog-item-pricing';

        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('priceList.name'),
                        TextEntry::make('code'),
                    ]),
                    TextEntry::make('name')
                        ->columnSpanFull(),
                    TextEntry::make('description')
                        ->placeholder('—')
                        ->columnSpanFull(),
                    Grid::make(4)->components([
                        TextEntry::make('family_code')->placeholder('—'),
                        TextEntry::make('color_code')->placeholder('—'),
                        TextEntry::make('thickness_mm')->placeholder('—'),
                        TextEntry::make('led_temperature_code')->placeholder('—'),
                    ]),
                    Grid::make(4)->components([
                        TextEntry::make('letter_height_min_mm')->placeholder('—'),
                        TextEntry::make('letter_height_max_mm')->placeholder('—'),
                        TextEntry::make('unit_code'),
                        TextEntry::make('sort_order'),
                    ]),
                    Grid::make(2)->components([
                        TextEntry::make('markup_percent')
                            ->suffix('%')
                            ->placeholder('—'),
                        TextEntry::make('manual_sale_price')
                            ->money('EUR')
                            ->placeholder('—'),
                    ]),
                    TextEntry::make('notes')->placeholder('—')->columnSpanFull(),
                    IconEntry::make('is_active')->boolean(),
                ]),
            'pricing' => Section::make(__('signage::signage.pricing.title'))
                ->columnSpanFull()
                ->components([
                    ViewEntry::make('pricing')
                        ->view($pricingView, static fn (SignCatalogItem $record): array => [
                            'price' => app(PriceSignCatalogItemAction::class)->execute($record),
                        ])
                        ->columnSpanFull(),
                ]),
        ];
    }
}
