<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditPublicProcurementTenderSource extends XotBaseEditRecord
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;
}
