<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

// NOTE: contains Platform deps — individual tests may need skip until Platform replica ready.

use Modules\User\Models\User;
use Livewire\Livewire;
use Modules\Auth\Database\Seeders\ShieldPermissionsSeeder;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Filament\Resources\InterventionResource\Pages\EditIntervention;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\InventoryMovementsRelationManager;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Infrastructure\Models\PlatformSetting;
use Modules\WorkOrder\Models\WorkOrderStatus;

use function Pest\Laravel\actingAs;

it('hides intervention materials section when inventory link to interventions is disabled', function (): void {
    app(ShieldPermissionsSeeder::class)->run();

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    PlatformSetting::query()->updateOrCreate(
        ['key' => 'inventory.allow_link_interventions'],
        ['value_json' => ['value' => false], 'updated_by_user_id' => $user->id],
    );

    $customerId = \Illuminate\Support\Facades\DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Test Materiali',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrderId = \Illuminate\Support\Facades\DB::table('work_orders')->insertGetId([
        'code' => 'WO-MAT-OFF',
        'customer_id' => $customerId,
        'status_id' => $status->id,
        'title' => 'Intervento senza materiali',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $interventionId = \Illuminate\Support\Facades\DB::table('interventions')->insertGetId([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'scheduled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    actingAs($user)
        ->get('/admin/interventions/'.$interventionId)
        ->assertOk()
        ->assertDontSee('Materiali impiegati');
});

it('saves intervention materials with warehouse location from the relation manager', function (): void {
    app(ShieldPermissionsSeeder::class)->run();

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    PlatformSetting::query()->updateOrCreate(
        ['key' => 'inventory.allow_link_interventions'],
        ['value_json' => ['value' => true], 'updated_by_user_id' => $user->id],
    );
    PlatformSetting::query()->updateOrCreate(
        ['key' => 'inventory.enable_storage_zones'],
        ['value_json' => ['value' => true], 'updated_by_user_id' => $user->id],
    );

    $customerId = \Illuminate\Support\Facades\DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Test Materiali Con Ubicazione',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrderId = \Illuminate\Support\Facades\DB::table('work_orders')->insertGetId([
        'code' => 'WO-MAT-LOC',
        'customer_id' => $customerId,
        'status_id' => $status->id,
        'title' => 'Intervento con materiali da ubicazione',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $interventionId = \Illuminate\Support\Facades\DB::table('interventions')->insertGetId([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'scheduled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $product = Product::query()->create([
        'name' => 'Morsetto din',
        'sku' => 'MOR-DIN',
        'unit_price' => 2.5,
        'is_active' => true,
    ]);

    $warehouse = Warehouse::query()->create([
        'name' => 'Furgone 1',
        'code' => 'VAN-1',
        'is_active' => true,
        'is_final_destination' => false,
    ]);

    $location = WarehouseLocation::query()->create([
        'warehouse_id' => $warehouse->id,
        'code' => 'A-01-01',
        'is_active' => true,
    ]);

    InventoryMovement::query()->create([
        'product_id' => $product->id,
        'movement_type' => 'carico',
        'quantity' => 5,
        'to_warehouse_id' => $warehouse->id,
        'to_location_id' => $location->id,
        'occurred_at' => now()->subHour(),
        'unit_price' => 2.5,
        'line_total' => 12.5,
    ]);

    filament()->setCurrentPanel('admin');

    actingAs($user);

    Livewire::test(InventoryMovementsRelationManager::class, [
        'ownerRecord' => \Modules\Intervention\Models\Intervention::query()->findOrFail($interventionId),
        'pageClass' => EditIntervention::class,
    ])->callTableAction('create', data: [
        'product_id' => $product->id,
        'from_warehouse_id' => $warehouse->id,
        'from_location_id' => $location->id,
        'quantity' => 2,
        'unit_price' => '2.50',
        'note' => 'Prelievo per intervento',
    ]);

    expect(InventoryMovement::query()
        ->where('intervention_id', $interventionId)
        ->where('product_id', $product->id)
        ->where('from_warehouse_id', $warehouse->id)
        ->where('from_location_id', $location->id)
        ->where('movement_type', 'scarico')
        ->exists())->toBeTrue();
});
