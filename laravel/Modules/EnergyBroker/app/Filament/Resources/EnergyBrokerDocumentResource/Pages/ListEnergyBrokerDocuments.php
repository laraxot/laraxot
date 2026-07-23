<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentsFromZipAction;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;

final class ListEnergyBrokerDocuments extends ListRecords
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->importPdfAction('importBillPdf', __('energy_broker.actions.import_bill_pdf')),
            $this->importPdfAction('importContractPdf', __('energy_broker.actions.import_contract_pdf')),
            Action::make('importLargeFile')
                ->label(__('energy_broker.actions.import_large_file'))
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('warning')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Chiudi')
                ->modalContent(fn () => view('energybroker::filament.large-upload-modal')),
            Action::make('importZip')
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
                        uploadedByUserId: Auth::id(),
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
            Action::make('deletePoorlyAnalyzed')
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
                    uploadedByUserId: Auth::id(),
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
