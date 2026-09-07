<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberStockMovementsTable extends XotBaseResourceTable
{
    protected static string $resource = TimberStockMovementResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'movement_date' => TextColumn::make('movement_date')->date()->sortable(),
            'direction_code' => TextColumn::make('direction_code')->badge(),
            'product.description' => TextColumn::make('product.description')->searchable()->sortable(),
            'quantity' => TextColumn::make('quantity')->numeric()->sortable(),
            'unit_of_measure' => TextColumn::make('unit_of_measure'),
            'customer.business_name' => TextColumn::make('customer.business_name')->searchable()->toggleable(),
            'lot_code' => TextColumn::make('lot_code')->searchable()->toggleable(),
        ];
    }

    /**
     * @return array<string, ViewAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
        ];
    }
}
