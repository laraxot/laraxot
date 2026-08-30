<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Modules\WorkOrder\Tests\TestCase;

uses(\Modules\WorkOrder\Tests\TestCase::class);

it('adds a work order activity', function (): void {
    // Arrange
    $userId = UserFactory::new()->createOne(['name' => 'Autore Attivita', 'email' => 'activity-'.uniqid().'@test.local'])->id;

    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = (int) DB::table('work_orders')->insertGetId([
        'code' => 'WO-ACT-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act
    $activity = app(AddWorkOrderActivityAction::class)->execute(
        workOrderId: $workOrderId,
        type: 'note',
        body: 'Hello activity',
        createdByUserId: $userId,
    );

    // Assert
    expect($activity->work_order_id)->toBe($workOrderId);
    expect($activity->created_by_user_id)->toBe($userId);
    expect($activity->type)->toBe('note');
    expect($activity->body)->toBe('Hello activity');
    expect(DB::table('work_order_activities')->where('work_order_id', $workOrderId)->count())->toBe(1);
});
