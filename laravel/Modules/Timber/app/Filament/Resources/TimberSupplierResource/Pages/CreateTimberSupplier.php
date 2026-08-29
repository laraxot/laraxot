<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Modules\Timber\Filament\Resources\TimberSupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberSupplier extends XotBaseCreateRecord
{
    protected static string $resource = TimberSupplierResource::class;
}
