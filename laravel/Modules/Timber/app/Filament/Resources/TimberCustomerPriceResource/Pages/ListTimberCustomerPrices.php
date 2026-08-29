<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberCustomerPrices extends XotBaseListRecords
{
    protected static string $resource = TimberCustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo prezzo cliente'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'customer.business_name' => Tables\Columns\TextColumn::make('customer.business_name')
                ->label('Cliente')
                ->searchable()
                ->sortable(),
            'product.description' => Tables\Columns\TextColumn::make('product.description')
                ->label('Prodotto')
                ->searchable(),
            'net_price' => Tables\Columns\TextColumn::make('net_price')
                ->label('Prezzo netto')
                ->money('EUR'),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'customer_id' => Tables\Filters\SelectFilter::make('customer_id')
                ->label('Cliente')
                ->relationship('customer', 'business_name')
                ->searchable()
                ->preload(),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
