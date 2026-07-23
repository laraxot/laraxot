<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Inventory\Filament\Resources\WarehouseResource;

final class ViewWarehouse extends ViewRecord
{
    protected static string $resource = WarehouseResource::class;
}

