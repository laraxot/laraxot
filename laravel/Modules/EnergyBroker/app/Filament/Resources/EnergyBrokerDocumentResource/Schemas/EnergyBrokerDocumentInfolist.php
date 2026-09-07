<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\EnergyBroker\Filament\Resources\EnergyBrokerDocumentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class EnergyBrokerDocumentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = EnergyBrokerDocumentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'source_file_name' => TextEntry::make('source_file_name'),
            'document_type' => TextEntry::make('document_type'),
            'service_type' => TextEntry::make('service_type'),
            'supplier_name' => TextEntry::make('supplier_name'),
            'analysis_status' => TextEntry::make('analysis_status'),
            'confidence_score' => TextEntry::make('confidence_score'),
            'processed_at' => TextEntry::make('processed_at')->dateTime(),
            'customer.legal_name' => TextEntry::make('customer.legal_name'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
