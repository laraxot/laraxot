<?php

declare(strict_types=1);

use Modules\Inventory\Database\Factories\InventoryMovementFactory;
use Modules\Inventory\Database\Factories\WarehouseFactory;
use Modules\Inventory\Database\Factories\WarehouseLocationFactory;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;

/**
 * Helper Pest/PHPStan — modulo Inventory.
 *
 * @see Modules/Document/tests/PestHelpers.php
 */

/**
 * @param  array<string, mixed>  $attributes
 */
function inventoryMakeWarehouse(array $attributes = []): Warehouse
{
    $warehouse = WarehouseFactory::new()->makeOne($attributes);

    if (! $warehouse instanceof Warehouse) {
        throw new RuntimeException('WarehouseFactory::makeOne() did not return a Warehouse instance.');
    }

    return $warehouse;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function inventoryCreateWarehouse(array $attributes = []): Warehouse
{
    return WarehouseFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function inventoryCreateMovement(array $attributes = []): InventoryMovement
{
    return InventoryMovementFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function inventoryCreateLocation(array $attributes = []): WarehouseLocation
{
    return WarehouseLocationFactory::new()->createOne($attributes);
}
