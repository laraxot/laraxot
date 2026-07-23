<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;

final class CreatePublicProcurementTenderSource extends CreateRecord
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;
}
