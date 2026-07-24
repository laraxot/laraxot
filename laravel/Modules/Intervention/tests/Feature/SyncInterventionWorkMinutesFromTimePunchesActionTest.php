<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\SyncInterventionWorkMinutesFromTimePunchesAction;
use Modules\Intervention\Domain\Actions\ComputeWorkMinutesAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;

it('syncs intervention work minutes from closed time punches', function (): void {
    // Arrange
    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-TEST-0001',
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

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'started_at' => '2026-02-16 09:00:00',
        'ended_at' => '2026-02-16 12:00:00',
        'travel_km' => null,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    TimePunch::query()->create([
        'intervention_id' => $intervention->id,
        'user_id' => $user->id,
        'started_at' => '2026-02-16 09:00:00',
        'ended_at' => '2026-02-16 10:00:00',
    ]);

    TimePunch::query()->create([
        'intervention_id' => $intervention->id,
        'user_id' => $user->id,
        'started_at' => '2026-02-16 10:15:00',
        'ended_at' => '2026-02-16 11:00:00',
    ]);

    // Open punch should be ignored.
    TimePunch::query()->create([
        'intervention_id' => $intervention->id,
        'user_id' => $user->id,
        'started_at' => '2026-02-16 11:00:00',
        'ended_at' => null,
    ]);

    $action = new SyncInterventionWorkMinutesFromTimePunchesAction(new ComputeWorkMinutesAction);

    // Act
    $updated = $action->execute($intervention->refresh());

    // Assert
    expect($updated->work_minutes)->toBe(105);
    expect($intervention->refresh()->work_minutes)->toBe(105);
});
