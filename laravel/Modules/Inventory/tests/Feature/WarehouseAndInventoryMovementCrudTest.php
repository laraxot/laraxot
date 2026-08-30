<?php

declare(strict_types=1);

use Modules\Inventory\Actions\GetInventoryOverviewRowsAction;
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

uses(\Modules\Inventory\Tests\TestCase::class);

it('validates warehouse attributes without persisting', function (): void {
    $warehouse = inventoryMakeWarehouse(['name' => 'Deposito Nord', 'code' => 'DEP-NORD']);

    expect($warehouse->name)->toBe('Deposito Nord')
        ->and($warehouse->code)->toBe('DEP-NORD')
        ->and($warehouse->is_active)->toBeTrue()
        ->and($warehouse->exists)->toBeFalse();
});

it('creates a warehouse and reads it back', function (): void {
    $warehouse = inventoryCreateWarehouse(['name' => 'Deposito Sud', 'code' => 'DEP-SUD']);

    $found = Warehouse::query()->find($warehouse->id);

    expect($found)->not->toBeNull()
        ->and($found?->name)->toBe('Deposito Sud');
});

it('updates a warehouse', function (): void {
    $warehouse = inventoryCreateWarehouse(['is_active' => true]);

    $warehouse->update(['is_active' => false]);

    expect($warehouse->refresh()->is_active)->toBeFalse();
});

it('deletes a warehouse', function (): void {
    $warehouse = inventoryCreateWarehouse();
    $id = $warehouse->id;

    $warehouse->delete();

    expect(Warehouse::query()->find($id))->toBeNull();
});

it('links a warehouse location to its warehouse and cascades the relation', function (): void {
    $warehouse = inventoryCreateWarehouse();
    $location = inventoryCreateLocation(['warehouse_id' => $warehouse->id]);

    expect($location->warehouse_id)->toBe($warehouse->id)
        ->and($warehouse->locations()->count())->toBe(1)
        ->and(WarehouseLocation::query()->find($location->id)?->warehouse_id)->toBe($warehouse->id);
});

it('creates an inventory movement of type carico bound to a warehouse', function (): void {
    $warehouse = inventoryCreateWarehouse();
    $movement = inventoryCreateMovement([
        'to_warehouse_id' => $warehouse->id,
        'movement_type' => 'carico',
        'quantity' => 12,
    ]);

    expect($movement->to_warehouse_id)->toBe($warehouse->id)
        ->and((int) $movement->quantity)->toBe(12)
        ->and(InventoryMovement::query()->find($movement->id))->not->toBeNull();
});

it('computes aggregate stock across warehouses via the overview action', function (): void {
    $warehouseA = inventoryCreateWarehouse();
    $warehouseB = inventoryCreateWarehouse();

    $movement = inventoryCreateMovement([
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

    $rowA = $rows[(string) $warehouseA->id] ?? null;
    $rowB = $rows[(string) $warehouseB->id] ?? null;

    if ($rowA === null || $rowB === null) {
        throw new RuntimeException('Expected both warehouse overview rows to be present.');
    }

    expect((int) $rowA->aggregate_stock)->toBe(6);
    expect((int) $rowB->aggregate_stock)->toBe(4);
});
