<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;

final class EditPublicProcurementContract extends EditRecord
{
    protected static string $resource = PublicProcurementContractResource::class;
}
