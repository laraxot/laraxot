<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\AssignUserToWorkOrderAction;
use Modules\WorkOrder\Actions\UnassignUserFromWorkOrderAction;

it('assigns and unassigns a user to a work order idempotently', function (): void {
    // Arrange
    $assigner = User::factory()->create();
    $assignee = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-ASG-0001',
        'customer_id' => $customerId,
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

    // Act (first time)
    $assignment1 = app(AssignUserToWorkOrderAction::class)->execute(
        workOrderId: (int) $workOrderId,
        userId: $assignee->id,
        role: 'technician',
        assignedByUserId: $assigner->id,
    );

    // Act (idempotent update)
    $assignment2 = app(AssignUserToWorkOrderAction::class)->execute(
        workOrderId: (int) $workOrderId,
        userId: $assignee->id,
        role: 'lead-technician',
        assignedByUserId: $assigner->id,
    );

    // Assert create/update
    expect((int) $assignment1->id)->toBe((int) $assignment2->id);
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->count())->toBe(1);
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->value('role'))->toBe('lead-technician');

    // Act (unassign)
    app(UnassignUserFromWorkOrderAction::class)->execute(
        workOrderId: (int) $workOrderId,
        userId: $assignee->id,
    );

    // Assert delete
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->count())->toBe(0);
});

