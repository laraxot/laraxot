<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource;

final class CreateTimberSupplier extends CreateRecord
{
    protected static string $resource = TimberSupplierResource::class;
}
