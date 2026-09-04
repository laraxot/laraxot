<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Modules\Billing\Filament\Resources\SupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateSupplier extends XotBaseCreateRecord
{
    protected static string $resource = SupplierResource::class;
}
