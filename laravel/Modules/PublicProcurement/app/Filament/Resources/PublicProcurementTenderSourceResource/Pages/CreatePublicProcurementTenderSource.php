<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreatePublicProcurementTenderSource extends XotBaseCreateRecord
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;
}
