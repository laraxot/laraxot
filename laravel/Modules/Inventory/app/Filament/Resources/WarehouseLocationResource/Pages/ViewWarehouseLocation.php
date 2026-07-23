<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;

final class ViewWarehouseLocation extends ViewRecord
{
    protected static string $resource = WarehouseLocationResource::class;
}
