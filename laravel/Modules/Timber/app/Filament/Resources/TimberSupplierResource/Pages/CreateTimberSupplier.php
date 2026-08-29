<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberSupplierResource;

final class CreateTimberSupplier extends XotBaseCreateRecord
{
    protected static string $resource = TimberSupplierResource::class;
}
