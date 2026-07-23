<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;

final class CreatePublicProcurementContract extends CreateRecord
{
    protected static string $resource = PublicProcurementContractResource::class;
}
