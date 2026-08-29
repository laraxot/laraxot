<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Timber\Filament\Resources\TimberSupplierResource;

final class EditTimberSupplier extends XotBaseEditRecord
{
    protected static string $resource = TimberSupplierResource::class;
}
