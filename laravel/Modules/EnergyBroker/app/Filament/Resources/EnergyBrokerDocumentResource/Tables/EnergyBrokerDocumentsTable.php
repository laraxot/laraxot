<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ReprocessEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class EnergyBrokerDocumentsTable extends XotBaseResourceTable
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'source_file_name' => TextColumn::make('source_file_name')->searchable(),
            'document_type' => TextColumn::make('document_type')->badge(),
            'service_type' => TextColumn::make('service_type')->badge(),
            'supplier_name' => TextColumn::make('supplier_name')->searchable(),
            'payload_pod' => TextColumn::make('payload_pod')

                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pod'))
                ->toggleable(),
            'payload_pdr' => TextColumn::make('payload_pdr')

                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pdr'))
                ->toggleable(),
            'payload_total_amount' => TextColumn::make('payload_total_amount')

                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.total_amount'))
                ->toggleable(),
            'payload_due_at' => TextColumn::make('payload_due_at')

                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.due_at'))
                ->toggleable(),
            'payload_annual_consumption' => TextColumn::make('payload_annual_consumption')

                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.annual_consumption'))
                ->toggleable(isToggledHiddenByDefault: true),
            'analysis_status' => TextColumn::make('analysis_status')->badge(),
            'failure_reason' => TextColumn::make('failure_reason')

                ->state(function (EnergyBrokerDocument $record): string {
                    $error = data_get($record->analysis_payload, 'error');

                    return is_string($error) ? $error : '';
                })
                ->limit(80)
                ->wrap()
                ->toggleable(),
            'customer.legal_name' => TextColumn::make('customer.legal_name')->searchable(),
            'media.title' => TextColumn::make('media.title')->toggleable(),
            'processed_at' => TextColumn::make('processed_at')->dateTime()->toggleable(),
        ];
    }

    /**
     * @return array<string, Action|ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'downloadPdf' => Action::make('downloadPdf')

                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn (EnergyBrokerDocument $record): bool => EnergyBrokerDocumentResource::hasDownloadablePdf($record))
                ->url(fn (EnergyBrokerDocument $record): string => route('energy-broker.documents.download', $record))
                ->openUrlInNewTab(),
            'confirmImport' => Action::make('confirmImport')

                ->icon('heroicon-o-check-circle')
                ->action(function (EnergyBrokerDocument $record): void {
                    try {
                        $result = app(ConfirmEnergyBrokerDocumentImportAction::class)->execute($record);
                        Notification::make()
                            ->title($result['status'] === 'needs_review'
                                ? __('energy_broker.messages.import_requires_review')
                                : __('energy_broker.messages.import_confirmed'))
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title(__('energy_broker.messages.analysis_failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            'reprocessWithDocumentAi' => Action::make('reprocessWithDocumentAi')

                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function (EnergyBrokerDocument $record): void {
                    try {
                        app(ReprocessEnergyBrokerDocumentPdfAction::class)->executeFromMedia($record);
                        Notification::make()
                            ->title(__('energy_broker.messages.reprocess_completed'))
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title(__('energy_broker.messages.reprocess_failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'reprocessWithDocumentAi' => BulkAction::make('reprocessWithDocumentAi')

                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (Collection $records): void {
                    $processed = 0;
                    $failed = 0;

                    foreach ($records as $record) {
                        if (! $record instanceof EnergyBrokerDocument) {
                            continue;
                        }

                        try {
                            app(ReprocessEnergyBrokerDocumentPdfAction::class)->executeFromMedia($record);
                            $processed++;
                        } catch (\Throwable) {
                            $failed++;
                        }
                    }

                    Notification::make()
                        ->title(__('energy_broker.messages.bulk_reprocess_completed'))
                        ->body(__('energy_broker.messages.bulk_reprocess_completed_body', [
                            'processed' => $processed,
                            'failed' => $failed,
                        ]))
                        ->success()
                        ->send();
                }),
            'deletePoorlyAnalyzed' => BulkAction::make('deletePoorlyAnalyzed')

                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('energy_broker.actions.delete_poorly_analyzed'))
                ->modalDescription(__('energy_broker.messages.delete_poorly_analyzed_confirmation'))
                ->action(function (Collection $records): void {
                    $result = app(DeletePoorlyAnalyzedEnergyBrokerDocumentsAction::class)->execute($records);

                    Notification::make()
                        ->title(__('energy_broker.messages.delete_poorly_analyzed_completed'))
                        ->body(__('energy_broker.messages.delete_poorly_analyzed_completed_body', [
                            'deleted' => $result['deleted'],
                            'skipped' => $result['skipped'],
                        ]))
                        ->success()
                        ->send();
                }),
        ];
    }
}
