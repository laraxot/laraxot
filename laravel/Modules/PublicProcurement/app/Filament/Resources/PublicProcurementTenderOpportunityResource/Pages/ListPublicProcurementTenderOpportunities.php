<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;

final class ListPublicProcurementTenderOpportunities extends ListRecords
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
