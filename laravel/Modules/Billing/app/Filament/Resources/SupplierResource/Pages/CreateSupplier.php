<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Resources\SupplierResource;

final class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;
}
