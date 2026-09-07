<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class DeliveryNotesTable extends XotBaseResourceTable
{
    protected static string $resource = DeliveryNoteResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'number' => TextColumn::make('number')
                ->weight(\Filament\Support\Enums\FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'supplier.legal_name' => TextColumn::make('supplier.legal_name')
                ->searchable()
                ->sortable(),
            'received_on' => TextColumn::make('received_on')
                ->date('d/m/Y')
                ->sortable(),
            'lines_count' => TextColumn::make('lines_count')
                ->counts('lines')
                ->badge(),
            'notes' => TextColumn::make('notes')
                ->limit(50)
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'supplier_id' => SelectFilter::make('supplier_id')
                ->options(fn (): array => DeliveryNoteResource::supplierOptions())
                ->searchable(),
        ];
    }
}
