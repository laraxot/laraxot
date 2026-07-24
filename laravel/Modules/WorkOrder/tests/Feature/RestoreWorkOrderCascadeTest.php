<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

it('restores a work order and restores related activities and interventions', function (): void {
    // Arrange
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'draft'],
        ['name' => 'Draft', 'color' => 'gray', 'is_terminal' => 0, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-REST-0001',
        'customer_id' => $customerId,
        'status_id' => $status->id,
        'title' => 'Restore test',
    ]);

    $activity = WorkOrderActivity::query()->create([
        'work_order_id' => $workOrder->id,
        'created_by_user_id' => null,
        'type' => 'note',
        'body' => 'hello',
        'occurred_at' => now(),
    ]);

    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'U1',
        'email' => 'u1-'.uniqid().'@local.test',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $userId,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
    ]);

    $workOrder->delete();
    $activity->delete();
    $intervention->delete();

    expect($workOrder->refresh()->trashed())->toBeTrue();
    expect(WorkOrderActivity::query()->onlyTrashed()->whereKey($activity->id)->exists())->toBeTrue();
    expect(Intervention::query()->onlyTrashed()->whereKey($intervention->id)->exists())->toBeTrue();

    // Act
    app(RestoreWorkOrderCascadeAction::class)->execute($workOrder->refresh());

    // Assert
    expect($workOrder->refresh()->trashed())->toBeFalse();
    expect(WorkOrderActivity::query()->whereKey($activity->id)->exists())->toBeTrue();
    expect(Intervention::query()->whereKey($intervention->id)->exists())->toBeTrue();
});
