<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Models\WorkOrder;

it('changes a work order status and records a status event and activity', function (): void {
    // Arrange
    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $openId = DB::table('work_order_statuses')->insertGetId([
        'code' => 'open',
        'name' => 'Open',
        'color' => null,
        'is_terminal' => 0,
        'sort_order' => 10,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $doneId = DB::table('work_order_statuses')->insertGetId([
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
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    // Act
    $event = app(ChangeWorkOrderStatusAction::class)->execute(
        workOrder: $workOrder,
        toStatusId: (int) $doneId,
        changedByUserId: $user->id,
        note: 'Completed by test',
    );

    // Assert
    $workOrder->refresh();
    expect((int) $workOrder->status_id)->toBe((int) $doneId);

    expect((int) $event->work_order_id)->toBe((int) $workOrder->id);
    expect((int) $event->from_status_id)->toBe((int) $openId);
    expect((int) $event->to_status_id)->toBe((int) $doneId);
    expect((int) $event->changed_by_user_id)->toBe((int) $user->id);

    expect(DB::table('work_order_status_events')->where('work_order_id', $workOrder->id)->count())->toBe(1);
    expect(DB::table('work_order_activities')->where('work_order_id', $workOrder->id)->where('type', 'status')->count())->toBe(1);
});

