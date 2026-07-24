<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

it('changes a work order status and records a status event and activity', function (): void {
    // Arrange
    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Utente Test',
        'email' => 'status-user-'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $openId = (int) DB::table('work_order_statuses')->insertGetId([
        'code' => 'open',
        'name' => 'Open',
        'color' => null,
        'is_terminal' => 0,
        'sort_order' => 10,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $doneId = (int) DB::table('work_order_statuses')->insertGetId([
        'code' => 'done',
        'name' => 'Done',
        'color' => null,
        'is_terminal' => 1,
        'sort_order' => 90,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-STATUS-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'status_id' => $openId,
    ]);

    // Act
    $event = app(ChangeWorkOrderStatusAction::class)->execute(
        workOrder: $workOrder,
        toStatusId: $doneId,
        changedByUserId: $userId,
        note: 'Completed by test',
    );

    // Assert
    $workOrder->refresh();
    expect($workOrder->status_id)->toBe($doneId);

    expect($event->work_order_id)->toBe($workOrder->id);
    expect($event->from_status_id)->toBe($openId);
    expect($event->to_status_id)->toBe($doneId);
    expect($event->changed_by_user_id)->toBe($userId);

    expect(DB::table('work_order_status_events')->where('work_order_id', $workOrder->id)->count())->toBe(1);
    expect(DB::table('work_order_activities')->where('work_order_id', $workOrder->id)->where('type', 'status')->count())->toBe(1);
});
