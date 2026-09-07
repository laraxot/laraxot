<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberInvoicesTable extends XotBaseResourceTable
{
    protected static string $resource = TimberInvoiceResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'invoice_date' => TextColumn::make('invoice_date')->date()->sortable(),
            'number' => TextColumn::make('number')->searchable()->sortable(),
            'kind' => TextColumn::make('kind')->badge(),
            'customer.business_name' => TextColumn::make('customer.business_name')->searchable()->toggleable(),
            'total_amount' => TextColumn::make('total_amount')->money('EUR')->sortable(),
            'issued_at' => TextColumn::make('issued_at')->dateTime()->sortable()->toggleable(),
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
