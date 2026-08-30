<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Signage\Actions\PriceSignCatalogItemAction;
use Modules\Signage\Datas\SignCatalogItemPrice;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignCatalogItem;

/**
 * The catalog item with its price broken down line by line — the customer asked
 * to always have the calculation of every single row in sight, not just the
 * total.
 */
final class ViewSignCatalogItem extends ViewRecord
{
    protected static string $resource = SignCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        TextEntry::make('code')->label(__('signage::signage.fields.code'))->weight('bold'),
                        TextEntry::make('priceList.name')->label(__('signage::signage.catalog_fields.price_list')),
                        TextEntry::make('led_temperature_code')->label(__('signage::signage.catalog_fields.led_temperature_code'))->placeholder('—'),
                    ]),
                    TextEntry::make('name')->label(__('signage::signage.fields.name'))->columnSpanFull(),
                    TextEntry::make('description')->label(__('signage::signage.catalog_fields.description'))->placeholder('—')->columnSpanFull(),
                ]),

            Section::make(__('signage::signage.pricing.title'))
                ->columnSpanFull()
                ->components([
                    ViewEntry::make('pricing')
                        ->view('signage::filament.catalog-item-pricing')
                        ->viewData(['price' => $this->price()])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    private function price(): SignCatalogItemPrice
    {
        /** @var SignCatalogItem $item */
        $item = $this->getRecord();

        return app(PriceSignCatalogItemAction::class)->execute($item);
    }
}
