<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Actions\PriceSignCatalogItemAction;
use Modules\Signage\Datas\SignCatalogItemPrice;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * The catalog item with its price broken down line by line — the customer asked
 * to always have the calculation of every single row in sight, not just the
 * total.
 */
final class ViewSignCatalogItem extends XotBaseViewRecord
{
    protected static string $resource = SignCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        /** @var view-string $pricingView */
        $pricingView = 'signage::filament.catalog-item-pricing';

        return [
            'summary' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        TextEntry::make('code')->weight('bold'),
                        TextEntry::make('priceList.name'),
                        TextEntry::make('led_temperature_code')->placeholder('—'),
                    ]),
                    TextEntry::make('name')->columnSpanFull(),
                    TextEntry::make('description')->placeholder('—')->columnSpanFull(),
                ]),

            'pricing' => Section::make(__('signage::signage.pricing.title'))
                ->columnSpanFull()
                ->components([
                    ViewEntry::make('pricing')
                        ->view($pricingView, ['price' => $this->price()])
                        ->columnSpanFull(),
                ]),
        ];
    }

    private function price(): SignCatalogItemPrice
    {
        /** @var SignCatalogItem $item */
        $item = $this->getRecord();

        return app(PriceSignCatalogItemAction::class)->execute($item);
    }
}
