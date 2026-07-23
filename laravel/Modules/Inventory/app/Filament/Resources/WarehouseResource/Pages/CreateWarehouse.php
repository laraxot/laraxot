<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inventory\Filament\Resources\WarehouseResource;

final class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;
}

