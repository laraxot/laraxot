<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateWarehouse extends XotBaseCreateRecord
{
    protected static string $resource = WarehouseResource::class;
}
