<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages;

use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewInventoryMovement extends XotBaseViewRecord
{
    protected static string $resource = InventoryMovementResource::class;
}
