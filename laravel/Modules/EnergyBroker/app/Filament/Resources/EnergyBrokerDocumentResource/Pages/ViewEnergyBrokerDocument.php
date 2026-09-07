<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewEnergyBrokerDocument extends XotBaseViewRecord
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'source_file_name' => TextEntry::make('source_file_name'),
            'document_type' => TextEntry::make('document_type')

                ->formatStateUsing(static fn (?string $state): string => __('energy_broker.document_types.'.($state ?? 'unknown'))),
            'service_type' => TextEntry::make('service_type')

                ->formatStateUsing(static fn (?string $state): string => __('energy_broker.service_types.'.($state ?? 'unknown'))),
            'supplier_name' => TextEntry::make('supplier_name'),
            'analysis_status' => TextEntry::make('analysis_status')

                ->formatStateUsing(static fn (?string $state): string => __('energy_broker.statuses.'.($state ?? 'draft'))),
            'confidence_score' => TextEntry::make('confidence_score'),
            'customer_legal_name' => TextEntry::make('customer_legal_name')

                ->state(static fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.legal_name')),
            'customer_tax_code' => TextEntry::make('customer_tax_code')

                ->state(static fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.tax_code')),
            'customer_email' => TextEntry::make('customer_email')

                ->state(static fn (EnergyBrokerDocument $record): string => EnergyBrokerDocumentResource::payloadValue($record, 'customer.email')),
        ];
    }
}
