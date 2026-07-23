<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;

final class CreateWarehouseLocation extends CreateRecord
{
    protected static string $resource = WarehouseLocationResource::class;
}
