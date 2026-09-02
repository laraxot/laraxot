<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentsFromZipAction;
use Modules\EnergyBroker\Actions\ReprocessEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListEnergyBrokerDocuments extends XotBaseListRecords
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'importBillPdf' => $this->importPdfAction('importBillPdf', __('energy_broker.actions.import_bill_pdf')),
            'importContractPdf' => $this->importPdfAction('importContractPdf', __('energy_broker.actions.import_contract_pdf')),
            'importLargeFile' => Action::make('importLargeFile')
                ->label(__('energy_broker.actions.import_large_file'))
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('warning')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Chiudi')
                ->modalContent(fn () => view()->file($this->largeUploadModalViewPath())),
            'importZip' => Action::make('importZip')
                ->label(__('energy_broker.actions.import_zip'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->form([
                    FileUpload::make('zip_file')
                        ->label(__('energy_broker.fields.zip_file'))
                        ->disk('local')
                        ->directory('energy-broker/imports')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'application/octet-stream'])
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = $data['zip_file'] ?? null;
                    if (! is_string($path) || $path === '') {
                        return;
                    }

                    $result = app(ImportEnergyBrokerDocumentsFromZipAction::class)->execute(
                        zipPath: Storage::disk('local')->path($path),
                        uploadedByUserId: is_int(Auth::id()) ? Auth::id() : null,
                    );

                    Notification::make()
                        ->title(__('energy_broker.messages.zip_import_completed'))
                        ->body(__('energy_broker.messages.zip_import_completed_body', [
                            'total' => $result['total'],
                            'imported' => $result['imported'],
                            'failed' => $result['failed'],
                        ]))
                        ->success()
                        ->send();
                }),
            'deletePoorlyAnalyzed' => Action::make('deletePoorlyAnalyzed')
                ->label(__('energy_broker.actions.delete_poorly_analyzed_all'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('energy_broker.actions.delete_poorly_analyzed_all'))
                ->modalDescription(__('energy_broker.messages.delete_poorly_analyzed_all_confirmation'))
                ->action(function (): void {
                    $result = app(DeletePoorlyAnalyzedEnergyBrokerDocumentsAction::class)->executeAll();

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

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'source_file_name' => Tables\Columns\TextColumn::make('source_file_name')->label(__('energy_broker.fields.source_file_name'))->searchable(),
            'document_type' => Tables\Columns\TextColumn::make('document_type')->label(__('energy_broker.fields.document_type'))->badge(),
            'service_type' => Tables\Columns\TextColumn::make('service_type')->label(__('energy_broker.fields.service_type'))->badge(),
            'supplier_name' => Tables\Columns\TextColumn::make('supplier_name')->label(__('energy_broker.fields.supplier_name'))->searchable(),
            'payload_pod' => Tables\Columns\TextColumn::make('payload_pod')
                ->label(__('energy_broker.fields.pod'))
                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pod'))
                ->toggleable(),
            'payload_pdr' => Tables\Columns\TextColumn::make('payload_pdr')
                ->label(__('energy_broker.fields.pdr'))
                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pdr'))
                ->toggleable(),
            'payload_total_amount' => Tables\Columns\TextColumn::make('payload_total_amount')
                ->label(__('energy_broker.fields.total_amount'))
                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.total_amount'))
                ->toggleable(),
            'payload_due_at' => Tables\Columns\TextColumn::make('payload_due_at')
                ->label(__('energy_broker.fields.due_at'))
                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.due_at'))
                ->toggleable(),
            'payload_annual_consumption' => Tables\Columns\TextColumn::make('payload_annual_consumption')
                ->label(__('energy_broker.fields.annual_consumption'))
                ->state(fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.annual_consumption'))
                ->toggleable(isToggledHiddenByDefault: true),
            'analysis_status' => Tables\Columns\TextColumn::make('analysis_status')->label(__('energy_broker.fields.analysis_status'))->badge(),
            'failure_reason' => Tables\Columns\TextColumn::make('failure_reason')
                ->label(__('energy_broker.fields.failure_reason'))
                ->state(fn (EnergyBrokerDocument $record): string => (string) (data_get($record->analysis_payload, 'error') ?? ''))
                ->limit(80)
                ->wrap()
                ->toggleable(),
            'customer.legal_name' => Tables\Columns\TextColumn::make('customer.legal_name')->label(__('energy_broker.fields.customer'))->searchable(),
            'media.title' => Tables\Columns\TextColumn::make('media.title')->label(__('energy_broker.fields.pdf_file'))->toggleable(),
            'processed_at' => Tables\Columns\TextColumn::make('processed_at')->label(__('energy_broker.fields.status'))->dateTime()->toggleable(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'downloadPdf' => Action::make('downloadPdf')
                ->label(__('energy_broker.actions.download_pdf'))
                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn (EnergyBrokerDocument $record): bool => EnergyBrokerDocumentResource::hasDownloadablePdf($record))
                ->url(fn (EnergyBrokerDocument $record): string => route('energy-broker.documents.download', $record))
                ->openUrlInNewTab(),
            'confirmImport' => Action::make('confirmImport')
                ->label(__('energy_broker.actions.confirm_import'))
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
                ->label(__('energy_broker.actions.reprocess_with_document_ai'))
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
                ->label(__('energy_broker.actions.reprocess_with_document_ai'))
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
                ->label(__('energy_broker.actions.delete_poorly_analyzed'))
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

    private function largeUploadModalViewPath(): string
    {
        return __DIR__.'/../../../../../resources/views/filament/large-upload-modal.blade.php';
    }

    private function importPdfAction(string $name, string $label): Action
    {
        return Action::make($name)
            ->label($label)
            ->icon('heroicon-o-document-arrow-up')
            ->form([
                FileUpload::make('pdf_file')
                    ->label(__('energy_broker.fields.pdf_file'))
                    ->disk('local')
                    ->directory('energy-broker/imports')
                    ->acceptedFileTypes(['application/pdf'])
                    ->preserveFilenames()
                    ->required(),
            ])
            ->action(function (array $data): void {
                $path = $data['pdf_file'] ?? null;
                if (! is_string($path) || $path === '') {
                    return;
                }

                $disk = Storage::disk('local');
                $document = app(ImportEnergyBrokerDocumentPdfAction::class)->execute(
                    filename: basename($path),
                    binary: (string) $disk->get($path),
                    uploadedByUserId: is_int(Auth::id()) ? Auth::id() : null,
                    mediaMeta: [
                        'source' => 'single_pdf_upload',
                    ],
                );

                Notification::make()
                    ->title(__('energy_broker.messages.pdf_import_completed'))
                    ->body(__('energy_broker.messages.pdf_import_completed_body', [
                        'file' => $document->source_file_name,
                    ]))
                    ->success()
                    ->send();
            });
    }
}
