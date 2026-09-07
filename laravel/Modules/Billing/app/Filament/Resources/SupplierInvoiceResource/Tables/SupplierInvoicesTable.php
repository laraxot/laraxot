<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SupplierInvoicesTable extends XotBaseResourceTable
{
    protected static string $resource = SupplierInvoiceResource::class;

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
            'issued_on' => TextColumn::make('issued_on')
                ->date('d/m/Y')
                ->sortable(),
            'total' => TextColumn::make('total')
                ->money('EUR')
                ->sortable(),
            'status_code' => TextColumn::make('status_code')
                ->badge()
                ->color(fn (string $state): string => SupplierInvoiceResource::statusColor($state))
                ->formatStateUsing(fn (string $state): string => __("billing::suppliers.statuses.{$state}"))
                ->sortable(),
            'lines_count' => TextColumn::make('lines_count')
                ->counts('lines')
                ->badge(),
        ];
    }

    /**
     * @return array<int|string, \Filament\Tables\Filters\Filter>
     */
    public function getTableFilters(): array
    {
        return [
            'status_code' => SelectFilter::make('status_code')
                ->options(SupplierInvoiceResource::statusOptions()),
            'supplier_id' => SelectFilter::make('supplier_id')
                ->options(fn (): array => DeliveryNoteResource::supplierOptions())
                ->searchable(),
        ];
    }

    /**
     * @return array<int|string, \Filament\Actions\Action|\Filament\Actions\ActionGroup>
     */
    public function getTableActions(): array
    {
        return [
            SupplierInvoiceResource::reconcileAction(),
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\EditAction::make(),
        ];
    }
}
