<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ReprocessEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class EnergyBrokerDocumentResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = EnergyBrokerDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): string
    {
        return __('energy_broker.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('energy_broker.document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('energy_broker.documents');
    }

    /** @return array<int, Component> */
    public static function getFormSchema(): array
    {
        return [
            Section::make(__('energy_broker.sections.document_summary'))->columns(3)->columnSpanFull()->components([
                Placeholder::make('source_file_name')
                    ->label(__('energy_broker.fields.source_file_name'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) ($record->source_file_name ?? '-')),
                Placeholder::make('document_type')
                    ->label(__('energy_broker.fields.document_type'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.document_types.'.($record->document_type ?? 'unknown'))),
                Placeholder::make('service_type')
                    ->label(__('energy_broker.fields.service_type'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.service_types.'.($record->service_type ?? 'unknown'))),
                Placeholder::make('supplier_name')
                    ->label(__('energy_broker.fields.supplier_name'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) ($record->supplier_name ?? '-')),
                Placeholder::make('analysis_status')
                    ->label(__('energy_broker.fields.analysis_status'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.statuses.'.($record->analysis_status ?? 'draft'))),
                Placeholder::make('confidence_score')
                    ->label(__('energy_broker.fields.confidence_score'))
                    ->content(fn (?EnergyBrokerDocument $record): string => $record?->confidence_score !== null ? (string) $record->confidence_score : '-'),
                Placeholder::make('file')
                    ->label(__('energy_broker.fields.pdf_file'))
                    ->content(fn (?EnergyBrokerDocument $record): string => (string) ($record->media->title ?? '-')),
            ]),
            Section::make(__('energy_broker.sections.customer_data'))->columns(3)->columnSpanFull()->components([
                Placeholder::make('payload_customer_legal_name')
                    ->label(__('energy_broker.fields.legal_name'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.legal_name')),
                Placeholder::make('payload_customer_tax_code')
                    ->label(__('energy_broker.fields.tax_code'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.tax_code')),
                Placeholder::make('payload_customer_email')
                    ->label(__('energy_broker.fields.email'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.email')),
                Placeholder::make('payload_customer_address')
                    ->label(__('energy_broker.fields.address_line1'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.address_line1')),
                Placeholder::make('payload_customer_city')
                    ->label(__('energy_broker.fields.city'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.city')),
                Placeholder::make('payload_customer_postal_code')
                    ->label(__('energy_broker.fields.postal_code'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.postal_code')),
                Placeholder::make('payload_customer_province')
                    ->label(__('energy_broker.fields.province'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'customer.province')),
            ]),
            Section::make(__('energy_broker.sections.billing_data'))->columns(3)->columnSpanFull()->components([
                Placeholder::make('payload_document_number')
                    ->label(__('energy_broker.fields.document_number'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.document_number')),
                Placeholder::make('payload_issued_at')
                    ->label(__('energy_broker.fields.issued_at'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.issued_at')),
                Placeholder::make('payload_period_label')
                    ->label(__('energy_broker.fields.period_label'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.period_label')),
                Placeholder::make('payload_due_at')
                    ->label(__('energy_broker.fields.due_at'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.due_at')),
                Placeholder::make('payload_total_amount')
                    ->label(__('energy_broker.fields.total_amount'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.total_amount')),
                Placeholder::make('payload_customer_code')
                    ->label(__('energy_broker.fields.customer_code'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.customer_code')),
            ]),
            Section::make(__('energy_broker.sections.supply_data'))->columns(3)->columnSpanFull()->components([
                Placeholder::make('payload_pod')
                    ->label(__('energy_broker.fields.pod'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.pod')),
                Placeholder::make('payload_pdr')
                    ->label(__('energy_broker.fields.pdr'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.pdr')),
                Placeholder::make('payload_supply_code')
                    ->label(__('energy_broker.fields.supply_code'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.supply_code')),
                Placeholder::make('payload_supply_address')
                    ->label(__('energy_broker.fields.supply_address'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.supply_address')),
                Placeholder::make('payload_power_kw')
                    ->label(__('energy_broker.fields.power_kw'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.power_kw')),
                Placeholder::make('payload_annual_consumption')
                    ->label(__('energy_broker.fields.annual_consumption'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.annual_consumption')),
                Placeholder::make('payload_annual_consumption_electricity')
                    ->label(__('energy_broker.fields.annual_consumption_electricity'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.annual_consumption_electricity')),
                Placeholder::make('payload_annual_consumption_gas')
                    ->label(__('energy_broker.fields.annual_consumption_gas'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.annual_consumption_gas')),
            ]),
            Section::make(__('energy_broker.sections.contract_data'))->columns(3)->columnSpanFull()->components([
                Placeholder::make('payload_current_supplier')
                    ->label(__('energy_broker.fields.current_supplier'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.current_supplier')),
                Placeholder::make('payload_proposed_supplier')
                    ->label(__('energy_broker.fields.proposed_supplier'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.proposed_supplier')),
                Placeholder::make('payload_offer_name')
                    ->label(__('energy_broker.fields.offer_name'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.offer_name')),
                Placeholder::make('payload_offer_code_electricity')
                    ->label(__('energy_broker.fields.offer_code_electricity'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.offer_code_electricity')),
                Placeholder::make('payload_offer_code_gas')
                    ->label(__('energy_broker.fields.offer_code_gas'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.offer_code_gas')),
                Placeholder::make('payload_payment_method')
                    ->label(__('energy_broker.fields.payment_method'))
                    ->content(fn (?EnergyBrokerDocument $record): string => self::payloadValue($record, 'contract.payment_method')),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_file_name')->label(__('energy_broker.fields.source_file_name'))->searchable(),
                Tables\Columns\TextColumn::make('document_type')->label(__('energy_broker.fields.document_type'))->badge(),
                Tables\Columns\TextColumn::make('service_type')->label(__('energy_broker.fields.service_type'))->badge(),
                Tables\Columns\TextColumn::make('supplier_name')->label(__('energy_broker.fields.supplier_name'))->searchable(),
                Tables\Columns\TextColumn::make('payload_pod')
                    ->label(__('energy_broker.fields.pod'))
                    ->state(fn (EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.pod'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payload_pdr')
                    ->label(__('energy_broker.fields.pdr'))
                    ->state(fn (EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.pdr'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payload_total_amount')
                    ->label(__('energy_broker.fields.total_amount'))
                    ->state(fn (EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.total_amount'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payload_due_at')
                    ->label(__('energy_broker.fields.due_at'))
                    ->state(fn (EnergyBrokerDocument $record): string => self::payloadValue($record, 'document.due_at'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payload_annual_consumption')
                    ->label(__('energy_broker.fields.annual_consumption'))
                    ->state(fn (EnergyBrokerDocument $record): string => self::payloadValue($record, 'supply.annual_consumption'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('analysis_status')->label(__('energy_broker.fields.analysis_status'))->badge(),
                Tables\Columns\TextColumn::make('failure_reason')
                    ->label(__('energy_broker.fields.failure_reason'))
                    ->state(function (EnergyBrokerDocument $record): string {
                        $error = data_get($record->analysis_payload, 'error');
                        return is_string($error) ? $error : '';
                    })
                    ->limit(80)
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('customer.legal_name')->label(__('energy_broker.fields.customer'))->searchable(),
                Tables\Columns\TextColumn::make('media.title')->label(__('energy_broker.fields.pdf_file'))->toggleable(),
                Tables\Columns\TextColumn::make('processed_at')->label(__('energy_broker.fields.status'))->dateTime()->toggleable(),
            ])
            ->actions([
                Action::make('downloadPdf')
                    ->label(__('energy_broker.actions.download_pdf'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (EnergyBrokerDocument $record): bool => self::hasDownloadablePdf($record))
                    ->url(fn (EnergyBrokerDocument $record): string => route('energy-broker.documents.download', $record))
                    ->openUrlInNewTab(),
                Action::make('confirmImport')
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
                Action::make('reprocessWithDocumentAi')
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('reprocessWithDocumentAi')
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
                BulkAction::make('deletePoorlyAnalyzed')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnergyBrokerDocuments::route('/'),
            'view' => Pages\ViewEnergyBrokerDocument::route('/{record}'),
            'edit' => Pages\EditEnergyBrokerDocument::route('/{record}/edit'),
        ];
    }

    public static function payloadValue(?EnergyBrokerDocument $record, string $path): string
    {
        $value = data_get($record->normalized_payload ?? [], $path);

        if (is_bool($value)) {
            return $value ? __('Yes') : __('No');
        }

        if (! is_scalar($value)) {
            return '-';
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : '-';
    }

    public static function hasDownloadablePdf(EnergyBrokerDocument $record): bool
    {
        $media = $record->media;
        if ($media === null || $media->path === null) {
            return false;
        }

        return Storage::disk((string) ($media->disk ?: 'public'))->exists($media->path);
    }
}
