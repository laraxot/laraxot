<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberPaymentInstallmentsTable extends XotBaseResourceTable
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'due_date' => TextColumn::make('due_date')->date()->sortable(),
            'direction' => TextColumn::make('direction')->badge(),
            'customer.business_name' => TextColumn::make('customer.business_name')->searchable()->sortable(),
            'invoice.number' => TextColumn::make('invoice.number')->searchable()->toggleable(),
            'amount' => TextColumn::make('amount')->money('EUR')->sortable(),
            'status_code' => TextColumn::make('status_code')->badge(),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status_code' => SelectFilter::make('status_code')->options(TimberConfigMap::get('timber.payment_statuses')),
            'direction' => SelectFilter::make('direction')->options(['in' => 'Entrata', 'out' => 'Uscita']),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
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
