<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewPublicProcurementTenderOpportunity extends XotBaseViewRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;
}
