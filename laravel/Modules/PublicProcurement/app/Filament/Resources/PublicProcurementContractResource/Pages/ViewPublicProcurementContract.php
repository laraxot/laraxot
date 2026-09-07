<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewPublicProcurementContract extends XotBaseViewRecord
{
    protected static string $resource = PublicProcurementContractResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'status' => TextEntry::make('status'),
            'code' => TextEntry::make('code'),
            'title' => TextEntry::make('title'),
            'contracting_authority' => TextEntry::make('contracting_authority'),
            'cig' => TextEntry::make('cig'),
            'cup' => TextEntry::make('cup'),
            'contract_amount' => TextEntry::make('contract_amount'),
            'works_due_on' => TextEntry::make('works_due_on'),
        ];
    }
}
