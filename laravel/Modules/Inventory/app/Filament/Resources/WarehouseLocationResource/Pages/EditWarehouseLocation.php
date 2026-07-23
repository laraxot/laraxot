<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;

final class EditWarehouseLocation extends EditRecord
{
    protected static string $resource = WarehouseLocationResource::class;
}
