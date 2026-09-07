<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberCustomerPricesTable extends XotBaseResourceTable
{
    protected static string $resource = TimberCustomerPriceResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'customer.business_name' => TextColumn::make('customer.business_name')->searchable()->sortable(),
            'product.description' => TextColumn::make('product.description')->searchable()->sortable(),
            'net_price' => TextColumn::make('net_price')->money('EUR')->sortable(),
        ];
    }

    /**
     * @return array<string, EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
