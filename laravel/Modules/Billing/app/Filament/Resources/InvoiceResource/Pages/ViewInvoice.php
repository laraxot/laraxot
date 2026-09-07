<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Modules\Billing\Actions\GenerateInvoicePdfAction;
use Modules\Billing\Actions\SendElectronicInvoiceAction;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Billing\Models\Invoice;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewInvoice extends XotBaseViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'downloadPdf' => Action::make('downloadPdf')
                ->label(__('billing::billing.actions.download_pdf'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    /** @var Invoice $invoice */
                    $invoice = $this->getRecord();

                    return response()->streamDownload(
                        function () use ($invoice): void {
                            echo app(GenerateInvoicePdfAction::class)->execute($invoice);
                        },
                        sprintf('invoice-%s.pdf', $invoice->number ?: $invoice->id),
                    );
                }),
            'sendElectronicInvoice' => Action::make('sendElectronicInvoice')
                ->label(__('billing::billing.actions.send_einvoice'))
                ->icon('heroicon-o-paper-airplane')
                ->requiresConfirmation()
                ->visible(fn (): bool => request()->is('admin/*'))
                ->action(function (): void {
                    /** @var Invoice $invoice */
                    $invoice = $this->getRecord();

                    try {
                        $result = app(SendElectronicInvoiceAction::class)->execute($invoice);

                        $externalId = isset($result['external_id']) && is_string($result['external_id'])
                            ? $result['external_id']
                            : null;

                        Notification::make()
                            ->success()
                            ->title(__('billing::billing.notifications.einvoice_sent_title'))
                            ->body(
                                __('billing::billing.notifications.einvoice_sent_body', [
                                    'provider' => (string) ($result['provider'] ?? '-'),
                                    'external_id' => $externalId ?? '-',
                                ]),
                            )
                            ->send();
                    } catch (\Throwable $exception) {
                        Notification::make()
                            ->danger()
                            ->title(__('billing::billing.notifications.einvoice_failed_title'))
                            ->body($exception->getMessage())
                            ->send();
                    }
                }),
        ];
    }
}
