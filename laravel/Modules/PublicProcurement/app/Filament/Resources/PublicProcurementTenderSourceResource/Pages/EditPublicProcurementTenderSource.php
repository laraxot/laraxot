<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;

final class EditPublicProcurementTenderSource extends EditRecord
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;
}
