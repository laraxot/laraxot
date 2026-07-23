<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;

final class ViewPublicProcurementContract extends ViewRecord
{
    protected static string $resource = PublicProcurementContractResource::class;
}
