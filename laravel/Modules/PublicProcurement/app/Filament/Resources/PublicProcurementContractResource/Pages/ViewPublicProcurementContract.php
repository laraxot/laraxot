<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewPublicProcurementContract extends XotBaseViewRecord
{
    protected static string $resource = PublicProcurementContractResource::class;
}
