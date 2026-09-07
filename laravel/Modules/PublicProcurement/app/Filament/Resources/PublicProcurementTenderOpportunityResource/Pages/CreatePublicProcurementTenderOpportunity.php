<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreatePublicProcurementTenderOpportunity extends XotBaseCreateRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;
}
