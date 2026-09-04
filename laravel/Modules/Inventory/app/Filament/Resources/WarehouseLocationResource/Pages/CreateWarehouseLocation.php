<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Modules\Inventory\Filament\Resources\WarehouseLocationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateWarehouseLocation extends XotBaseCreateRecord
{
    protected static string $resource = WarehouseLocationResource::class;
}
