<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberEInvoices extends XotBaseListRecords
{
    protected static string $resource = TimberEInvoiceResource::class;

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'invoice.number' => Tables\Columns\TextColumn::make('invoice.number')
                ->label('Fattura')
                ->searchable()
                ->sortable(),
            'invoice.customer.business_name' => Tables\Columns\TextColumn::make('invoice.customer.business_name')
                ->label('Cliente')
                ->toggleable(),
            'direction' => Tables\Columns\TextColumn::make('direction')
                ->label('Flusso')
                ->formatStateUsing(fn (?string $state): string => $state === 'in' ? 'Ricevuta' : 'Emessa')
                ->badge(),
            'sdi_status' => Tables\Columns\TextColumn::make('sdi_status')
                ->label('Stato SdI')
                ->badge()
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.sdi_statuses')[$state] ?? (string) $state)
                ->color(fn (?string $state): string => match ($state) {
                    'consegnata' => 'success',
                    'inviata' => 'info',
                    'scartata', 'non_consegnata' => 'danger',
                    default => 'gray',
                }),
            'file_name' => Tables\Columns\TextColumn::make('file_name')
                ->label('File')
                ->toggleable(),
            'transmitted_at' => Tables\Columns\TextColumn::make('transmitted_at')
                ->label('Trasmessa il')
                ->dateTime('d/m/Y H:i')
                ->toggleable(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'sdi_status' => Tables\Filters\SelectFilter::make('sdi_status')
                ->label('Stato SdI')
                ->options(TimberConfigMap::get('timber.sdi_statuses')),
            'direction' => Tables\Filters\SelectFilter::make('direction')
                ->label('Flusso')
                ->options(['out' => 'Emesse', 'in' => 'Ricevute']),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'transmit' => TimberEInvoiceResource::transmitAction(),
            'downloadxml' => TimberEInvoiceResource::downloadXmlAction(),
        ];
    }
}
