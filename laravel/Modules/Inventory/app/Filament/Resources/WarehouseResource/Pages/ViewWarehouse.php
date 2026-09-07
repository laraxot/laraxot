<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewWarehouse extends XotBaseViewRecord
{
    protected static string $resource = WarehouseResource::class;
}
