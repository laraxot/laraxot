<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditPublicProcurementTenderOpportunity extends XotBaseEditRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;
}
