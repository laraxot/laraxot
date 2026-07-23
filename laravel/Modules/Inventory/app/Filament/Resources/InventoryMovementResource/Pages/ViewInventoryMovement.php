<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;

final class ViewInventoryMovement extends ViewRecord
{
    protected static string $resource = InventoryMovementResource::class;
}

