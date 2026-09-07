<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class PublicProcurementContractInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = PublicProcurementContractResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'status' => TextEntry::make('status'),
            'title' => TextEntry::make('title'),
            'contracting_authority' => TextEntry::make('contracting_authority'),
            'procedure_type' => TextEntry::make('procedure_type'),
            'cig' => TextEntry::make('cig'),
            'cup' => TextEntry::make('cup'),
            'rup_name' => TextEntry::make('rup_name'),
            'works_director_name' => TextEntry::make('works_director_name'),
            'safety_coordinator_name' => TextEntry::make('safety_coordinator_name'),
            'contract_amount' => TextEntry::make('contract_amount')->money('EUR'),
            'security_costs' => TextEntry::make('security_costs')->money('EUR'),
            'vat_rate' => TextEntry::make('vat_rate'),
            'retention_rate' => TextEntry::make('retention_rate'),
            'awarded_on' => TextEntry::make('awarded_on')->date(),
            'signed_on' => TextEntry::make('signed_on')->date(),
            'site_handover_on' => TextEntry::make('site_handover_on')->date(),
            'works_start_on' => TextEntry::make('works_start_on')->date(),
            'works_due_on' => TextEntry::make('works_due_on')->date(),
            'works_completed_on' => TextEntry::make('works_completed_on')->date(),
            'description' => TextEntry::make('description'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
