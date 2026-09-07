<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Filament\Actions;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListPublicProcurementTenderOpportunities extends XotBaseListRecords
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
