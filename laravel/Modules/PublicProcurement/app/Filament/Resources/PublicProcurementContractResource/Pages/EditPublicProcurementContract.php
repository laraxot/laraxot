<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditPublicProcurementContract extends XotBaseEditRecord
{
    protected static string $resource = PublicProcurementContractResource::class;
}
