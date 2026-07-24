<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource;

final class EditTimberSupplier extends EditRecord
{
    protected static string $resource = TimberSupplierResource::class;
}
