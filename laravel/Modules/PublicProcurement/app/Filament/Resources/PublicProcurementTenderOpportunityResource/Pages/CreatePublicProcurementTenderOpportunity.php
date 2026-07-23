<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;

final class CreatePublicProcurementTenderOpportunity extends CreateRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;
}
