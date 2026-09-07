<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Actions\TransmitTimberEInvoiceAction;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource;
use Modules\Timber\Models\TimberEInvoice;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberEInvoicesTable extends XotBaseResourceTable
{
    protected static string $resource = TimberEInvoiceResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'invoice.number' => TextColumn::make('invoice.number')->searchable()->sortable(),
            'sdi_status' => TextColumn::make('sdi_status')->badge(),
            'sdi_recipient_code' => TextColumn::make('sdi_recipient_code')->toggleable(),
            'transmitted_at' => TextColumn::make('transmitted_at')->dateTime()->sortable()->toggleable(),
            'delivered_at' => TextColumn::make('delivered_at')->dateTime()->sortable()->toggleable(),
            'failed_at' => TextColumn::make('failed_at')->dateTime()->sortable()->toggleable(),
        ];
    }

    /**
     * @return array<string, Action|ViewAction>
     */
    public function getTableActions(): array
    {
        return [
            'transmit' => Action::make('transmit')
                ->icon('heroicon-o-paper-airplane')->color('info')->requiresConfirmation()
                ->visible(fn (TimberEInvoice $record): bool => $record->sdi_status === 'da_inviare')
                ->action(function (TimberEInvoice $record): void {
                    try {
                        app(TransmitTimberEInvoiceAction::class)->execute($record);
                        Notification::make()->title('Fattura elettronica trasmessa a SdI')->success()->send();
                    } catch (\RuntimeException $exception) {
                        Notification::make()->title($exception->getMessage())->warning()->send();
                    }
                }),
            'downloadXml' => Action::make('downloadXml')->icon('heroicon-o-arrow-down-tray')
                ->visible(fn (TimberEInvoice $record): bool => is_string($record->xml_content) && $record->xml_content !== '')
                ->url(fn (TimberEInvoice $record): string => route('timber.e-invoices.download', $record), shouldOpenInNewTab: true),
            'view' => ViewAction::make(),
        ];
    }
}
