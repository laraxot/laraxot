<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;

it('adds a work order activity', function (): void {
    // Arrange
    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-ACT-0001',
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

    // Act
    $activity = app(AddWorkOrderActivityAction::class)->execute(
        workOrderId: (int) $workOrderId,
        type: 'note',
        body: 'Hello activity',
        createdByUserId: $user->id,
    );

    // Assert
    expect((int) $activity->work_order_id)->toBe((int) $workOrderId);
    expect((int) $activity->created_by_user_id)->toBe((int) $user->id);
    expect($activity->type)->toBe('note');
    expect($activity->body)->toBe('Hello activity');
    expect(DB::table('work_order_activities')->where('work_order_id', $workOrderId)->count())->toBe(1);
});

