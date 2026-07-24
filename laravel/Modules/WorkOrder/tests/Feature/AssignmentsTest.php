<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\AssignUserToWorkOrderAction;
use Modules\WorkOrder\Actions\UnassignUserFromWorkOrderAction;
use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

it('assigns and unassigns a user to a work order idempotently', function (): void {
    // Arrange
    $assignerId = (int) DB::table('users')->insertGetId([
        'name' => 'Assegnatore',
        'email' => 'assigner-'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $assigneeId = (int) DB::table('users')->insertGetId([
        'name' => 'Assegnatario',
        'email' => 'assignee-'.uniqid().'@test.local',
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

    $workOrderId = (int) DB::table('work_orders')->insertGetId([
        'code' => 'WO-ASG-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act (first time)
    $assignment1 = app(AssignUserToWorkOrderAction::class)->execute(
        workOrderId: $workOrderId,
        userId: $assigneeId,
        role: 'technician',
        assignedByUserId: $assignerId,
    );

    // Act (idempotent update)
    $assignment2 = app(AssignUserToWorkOrderAction::class)->execute(
        workOrderId: $workOrderId,
        userId: $assigneeId,
        role: 'lead-technician',
        assignedByUserId: $assignerId,
    );

    // Assert create/update
    expect($assignment1->id)->toBe($assignment2->id);
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->count())->toBe(1);
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->value('role'))->toBe('lead-technician');

    // Act (unassign)
    app(UnassignUserFromWorkOrderAction::class)->execute(
        workOrderId: $workOrderId,
        userId: $assigneeId,
    );

    // Assert delete
    expect(DB::table('work_order_assignments')->where('work_order_id', $workOrderId)->count())->toBe(0);
});
