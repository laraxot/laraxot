<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberInvoices extends XotBaseListRecords
{
    protected static string $resource = TimberInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo documento'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'kind' => Tables\Columns\TextColumn::make('kind')
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.invoice_kinds')[$state] ?? (string) $state)
                ->toggleable(),
            'number' => Tables\Columns\TextColumn::make('number')
                ->searchable()
                ->sortable(),
            'invoice_date' => Tables\Columns\TextColumn::make('invoice_date')
                ->date('d/m/Y')
                ->sortable(),
            'customer.business_name' => Tables\Columns\TextColumn::make('customer.business_name')
                ->searchable()
                ->toggleable(),
            'status_code' => Tables\Columns\TextColumn::make('status_code')
                ->badge()
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.invoice_statuses')[$state] ?? (string) $state),
            'taxable_amount' => Tables\Columns\TextColumn::make('taxable_amount')
                ->money('EUR')
                ->toggleable(),
            'vat_amount' => Tables\Columns\TextColumn::make('vat_amount')
                ->money('EUR')
                ->toggleable(),
            'total_amount' => Tables\Columns\TextColumn::make('total_amount')
                ->money('EUR')
                ->sortable(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'kind' => Tables\Filters\SelectFilter::make('kind')
                ->options(TimberConfigMap::get('timber.invoice_kinds')),
            'status_code' => Tables\Filters\SelectFilter::make('status_code')
                ->options(TimberConfigMap::get('timber.invoice_statuses')),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'downloadpdf' => TimberInvoiceResource::downloadPdfAction(),
            'sendemail' => TimberInvoiceResource::sendEmailAction(),
            'whatsapp' => TimberInvoiceResource::whatsAppAction(),
            'issue' => TimberInvoiceResource::issueAction(),
            'prepareeinvoice' => TimberInvoiceResource::prepareEInvoiceAction(),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
