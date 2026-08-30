<?php

declare(strict_types=1);

use Modules\Inventory\Database\Factories\InventoryMovementFactory;
use Modules\Inventory\Database\Factories\WarehouseFactory;
use Modules\Inventory\Database\Factories\WarehouseLocationFactory;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Inventory\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Inventory\Tests\TestCase::class);

it('creates a warehouse', function (): void {
    $warehouse = WarehouseFactory::new()->createOne();

    Assert::assertInstanceOf(Warehouse::class, $warehouse);
    Assert::assertTrue((bool) $warehouse->getAttribute('is_active'));
});

it('links a location to a warehouse', function (): void {
    $warehouse = WarehouseFactory::new()->createOne();
    $location = WarehouseLocationFactory::new()->createOne(['warehouse_id' => $warehouse->id]);

    Assert::assertInstanceOf(WarehouseLocation::class, $location);
    Assert::assertSame(1, $warehouse->locations()->count());
});

it('creates an inventory movement', function (): void {
    $movement = InventoryMovementFactory::new()->createOne();

    Assert::assertInstanceOf(InventoryMovement::class, $movement);
    Assert::assertSame('carico', $movement->getAttribute('movement_type'));
});
