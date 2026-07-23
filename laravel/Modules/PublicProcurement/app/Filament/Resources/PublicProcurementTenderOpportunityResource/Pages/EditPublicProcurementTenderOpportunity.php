<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;

final class EditPublicProcurementTenderOpportunity extends EditRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;
}
