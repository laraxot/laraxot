<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Resources\SupplierResource;

final class EditSupplier extends EditRecord
{
    protected static string $resource = SupplierResource::class;
}
