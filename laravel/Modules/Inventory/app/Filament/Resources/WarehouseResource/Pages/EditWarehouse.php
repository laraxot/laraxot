<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inventory\Filament\Resources\WarehouseResource;

final class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;
}

