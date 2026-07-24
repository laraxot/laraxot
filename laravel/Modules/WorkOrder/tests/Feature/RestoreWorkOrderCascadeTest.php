<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Modules\WorkOrder\Models\WorkOrderStatus;

it('restores a work order and restores related activities and interventions', function (): void {
    // Arrange
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => null,
        'phone' => null,
        'address_line1' => null,
        'address_line2' => null,
        'city' => null,
        'postal_code' => null,
        'province' => null,
        'country_code' => 'IT',
        'payment_method' => null,
        'iban' => null,
        'notes' => null,
        'is_active' => true,
    ]);

    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'draft'],
        ['name' => 'Draft', 'color' => 'gray', 'is_terminal' => 0, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-REST-0001',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $status->id,
        'title' => 'Restore test',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    $activity = WorkOrderActivity::query()->create([
        'work_order_id' => $workOrder->id,
        'created_by_user_id' => null,
        'type' => 'note',
        'body' => 'hello',
        'occurred_at' => now(),
    ]);

    $userId = DB::table('users')->insertGetId([
        'name' => 'U1',
        'email' => 'u1@local.test',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $userId,
        'started_at' => null,
        'ended_at' => null,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
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

