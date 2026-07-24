<?php

declare(strict_types=1);

use Modules\Inventory\Actions\GetInventoryOverviewRowsAction;
use Modules\Inventory\Database\Factories\InventoryMovementFactory;
use Modules\Inventory\Database\Factories\WarehouseFactory;
use Modules\Inventory\Database\Factories\WarehouseLocationFactory;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Inventory\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Migrato da gestionale_commesse (tests/Feature/Inventory) — 2026-07-24
|--------------------------------------------------------------------------
|
| Adattato ai namespace correnti del modulo (Models\ invece di
| Infrastructure\Models\, Actions\ invece di Application\Queries\).
| Copertura CRUD Warehouse/WarehouseLocation/InventoryMovement, non
| duplica Modules/Inventory/tests/Unit/InventoryModelTest.php.
*/

uses(TestCase::class);

it('validates warehouse attributes without persisting', function (): void {
    $warehouse = WarehouseFactory::new()->make(['name' => 'Deposito Nord', 'code' => 'DEP-NORD']);

    expect($warehouse->name)->toBe('Deposito Nord')
        ->and($warehouse->code)->toBe('DEP-NORD')
        ->and($warehouse->is_active)->toBeTrue()
        ->and($warehouse->exists)->toBeFalse();
});

it('creates a warehouse and reads it back', function (): void {
    $warehouse = WarehouseFactory::new()->createOne(['name' => 'Deposito Sud', 'code' => 'DEP-SUD']);

    $found = Warehouse::query()->find($warehouse->id);

    expect($found)->not->toBeNull()
        ->and($found?->name)->toBe('Deposito Sud');
});

it('updates a warehouse', function (): void {
    $warehouse = WarehouseFactory::new()->createOne(['is_active' => true]);

    $warehouse->update(['is_active' => false]);

    expect($warehouse->refresh()->is_active)->toBeFalse();
});

it('deletes a warehouse', function (): void {
    $warehouse = WarehouseFactory::new()->createOne();
    $id = $warehouse->id;

    $warehouse->delete();

    expect(Warehouse::query()->find($id))->toBeNull();
});

it('links a warehouse location to its warehouse and cascades the relation', function (): void {
    $warehouse = WarehouseFactory::new()->createOne();
    $location = WarehouseLocationFactory::new()->createOne(['warehouse_id' => $warehouse->id]);

    expect($location->warehouse_id)->toBe($warehouse->id)
        ->and($warehouse->locations()->count())->toBe(1)
        ->and(WarehouseLocation::query()->find($location->id)?->warehouse_id)->toBe($warehouse->id);
});

it('creates an inventory movement of type carico bound to a warehouse', function (): void {
    $warehouse = WarehouseFactory::new()->createOne();
    $movement = InventoryMovementFactory::new()->createOne([
        'to_warehouse_id' => $warehouse->id,
        'movement_type' => 'carico',
        'quantity' => 12,
    ]);

    expect($movement->to_warehouse_id)->toBe($warehouse->id)
        ->and((int) $movement->quantity)->toBe(12)
        ->and(InventoryMovement::query()->find($movement->id))->not->toBeNull();
});

it('computes aggregate stock across warehouses via the overview action', function (): void {
    $warehouseA = WarehouseFactory::new()->createOne();
    $warehouseB = WarehouseFactory::new()->createOne();

    $movement = InventoryMovementFactory::new()->createOne([
        'to_warehouse_id' => $warehouseA->id,
        'movement_type' => 'carico',
        'quantity' => 10,
    ]);

    InventoryMovement::query()->create([
        'product_id' => $movement->product_id,
        'movement_type' => 'trasferimento',
        'quantity' => 4,
        'from_warehouse_id' => $warehouseA->id,
        'to_warehouse_id' => $warehouseB->id,
        'origin_auto' => false,
        'occurred_at' => now(),
    ]);

    $rows = app(GetInventoryOverviewRowsAction::class)->execute(false)->get()->keyBy(
        fn ($row): string => (string) $row->warehouse_id
    );

    expect((int) $rows[(string) $warehouseA->id]->aggregate_stock)->toBe(6)
        ->and((int) $rows[(string) $warehouseB->id]->aggregate_stock)->toBe(4);
});
