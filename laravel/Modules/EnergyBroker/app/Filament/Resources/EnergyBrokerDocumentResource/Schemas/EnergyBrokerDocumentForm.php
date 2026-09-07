<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class EnergyBrokerDocumentForm extends XotBaseResourceForm
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    public function getFormColumns(): int
    {
        return 3;
    }

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make(__('energy_broker.sections.document_summary'))->columns(3)->columnSpanFull()->components([
                TextEntry::make('source_file_name')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) ($record->source_file_name ?? '-')),
                TextEntry::make('document_type')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.document_types.'.($record->document_type ?? 'unknown'))),
                TextEntry::make('service_type')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.service_types.'.($record->service_type ?? 'unknown'))),
                TextEntry::make('supplier_name')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) ($record->supplier_name ?? '-')),
                TextEntry::make('analysis_status')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) __('energy_broker.statuses.'.($record->analysis_status ?? 'draft'))),
                TextEntry::make('confidence_score')

                    ->state(fn (?EnergyBrokerDocument $record): string => $record?->confidence_score !== null ? (string) $record->confidence_score : '-'),
                TextEntry::make('file')

                    ->state(fn (?EnergyBrokerDocument $record): string => (string) ($record->media->title ?? '-')),
            ]),
            Section::make(__('energy_broker.sections.customer_data'))->columns(3)->columnSpanFull()->components([
                TextEntry::make('payload_customer_legal_name')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.legal_name')),
                TextEntry::make('payload_customer_tax_code')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.tax_code')),
                TextEntry::make('payload_customer_email')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.email')),
                TextEntry::make('payload_customer_address')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.address_line1')),
                TextEntry::make('payload_customer_city')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.city')),
                TextEntry::make('payload_customer_postal_code')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.postal_code')),
                TextEntry::make('payload_customer_province')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.province')),
            ]),
            Section::make(__('energy_broker.sections.billing_data'))->columns(3)->columnSpanFull()->components([
                TextEntry::make('payload_document_number')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.document_number')),
                TextEntry::make('payload_issued_at')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.issued_at')),
                TextEntry::make('payload_period_label')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.period_label')),
                TextEntry::make('payload_due_at')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.due_at')),
                TextEntry::make('payload_total_amount')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.total_amount')),
                TextEntry::make('payload_customer_code')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'document.customer_code')),
            ]),
            Section::make(__('energy_broker.sections.supply_data'))->columns(3)->columnSpanFull()->components([
                TextEntry::make('payload_pod')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pod')),
                TextEntry::make('payload_pdr')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.pdr')),
                TextEntry::make('payload_supply_code')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.supply_code')),
                TextEntry::make('payload_supply_address')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.supply_address')),
                TextEntry::make('payload_power_kw')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.power_kw')),
                TextEntry::make('payload_annual_consumption')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.annual_consumption')),
                TextEntry::make('payload_annual_consumption_electricity')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.annual_consumption_electricity')),
                TextEntry::make('payload_annual_consumption_gas')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'supply.annual_consumption_gas')),
            ]),
            Section::make(__('energy_broker.sections.contract_data'))->columns(3)->columnSpanFull()->components([
                TextEntry::make('payload_current_supplier')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.current_supplier')),
                TextEntry::make('payload_proposed_supplier')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.proposed_supplier')),
                TextEntry::make('payload_offer_name')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.offer_name')),
                TextEntry::make('payload_offer_code_electricity')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.offer_code_electricity')),
                TextEntry::make('payload_offer_code_gas')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.offer_code_gas')),
                TextEntry::make('payload_payment_method')

                    ->state(fn (?EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'contract.payment_method')),
            ]),
        ];
    }
}
