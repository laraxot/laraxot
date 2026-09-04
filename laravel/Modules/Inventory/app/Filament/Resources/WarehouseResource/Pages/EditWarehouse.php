<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditWarehouse extends XotBaseEditRecord
{
    protected static string $resource = WarehouseResource::class;
}
