<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\GetWorkOrderDetailsAction;

it('loads work order details with related records', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $statusId = DB::table('work_order_statuses')->insertGetId([
        'code' => 'open',
        'name' => 'Open',
        'color' => null,
        'is_terminal' => 0,
        'sort_order' => 10,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-VIEW-0001',
        'customer_id' => $customerId,
        'status_id' => $statusId,
        'title' => 'Test work order',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act
    $workOrder = app(GetWorkOrderDetailsAction::class)->execute((int) $workOrderId);

    // Assert
    expect($workOrder->customer)->not->toBeNull();
    expect($workOrder->status)->not->toBeNull();
    expect($workOrder->code)->toBe('WO-VIEW-0001');
});

