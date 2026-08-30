<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\FinalizeInterventionReportAction;
use Modules\Intervention\Actions\StartTimePunchAction;
use Modules\Intervention\Actions\StopTimePunchAction;
use Modules\Intervention\Actions\SyncInterventionWorkMinutesFromTimePunchesAction;
use Modules\Intervention\Exceptions\OpenTimePunchAlreadyExists;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

it('starts and stops a time punch and computes work minutes', function (): void {
    // Arrange
    $user = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $statusId = WorkOrderStatus::query()->where('code', 'in_progress')->value('id');
    if (! $statusId) {
        $statusId = WorkOrderStatus::query()->create([
            'code' => 'in_progress',
            'name' => 'In progress',
            'color' => 'amber',
            'is_terminal' => 0,
            'sort_order' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ])->id;
    }

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-OP-0001',
        'customer_id' => $customerId,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $statusId,
        'title' => 'Test',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => null,
        'ended_at' => null,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    // Act
    $startedAt = CarbonImmutable::parse('2026-02-16 09:00:00');
    $endedAt = CarbonImmutable::parse('2026-02-16 10:30:00');

    app(StartTimePunchAction::class)->execute($intervention, $user->id, $startedAt);
    app(StopTimePunchAction::class)->execute($intervention, $user->id, $endedAt);
    app(SyncInterventionWorkMinutesFromTimePunchesAction::class)->execute($intervention->refresh());

    // Assert
    $intervention->refresh();
    expect($intervention->work_minutes)->toBe(90);
    expect($intervention->ended_at?->toDateTimeString())->toBe($endedAt->toDateTimeString());
    expect(TimePunch::query()->where('intervention_id', $intervention->id)->count())->toBe(1);
    expect(TimePunch::query()->where('intervention_id', $intervention->id)->whereNull('ended_at')->count())->toBe(0);
});

it('prevents multiple open punches for the same user', function (): void {
    // Arrange
    $user = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $statusId = WorkOrderStatus::query()->where('code', 'in_progress')->value('id');
    if (! $statusId) {
        $statusId = WorkOrderStatus::query()->create([
            'code' => 'in_progress',
            'name' => 'In progress',
            'color' => 'amber',
            'is_terminal' => 0,
            'sort_order' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ])->id;
    }

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-OP-0002',
        'customer_id' => $customerId,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $statusId,
        'title' => 'Test',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    $a = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => null,
        'ended_at' => null,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);
    $b = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => null,
        'ended_at' => null,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    // Act
    app(StartTimePunchAction::class)->execute($a, $user->id, CarbonImmutable::parse('2026-02-16 09:00:00'));

    // Assert
    expect(fn () => app(StartTimePunchAction::class)->execute($b, $user->id, CarbonImmutable::parse('2026-02-16 09:10:00')))
        ->toThrow(OpenTimePunchAlreadyExists::class);
});

it('finalizes the intervention report and updates the work order status', function (): void {
    // Arrange
    $user = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $completed = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'completed'],
        ['name' => 'Completed', 'color' => 'green', 'is_terminal' => 1, 'sort_order' => 40, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-OP-0003',
        'customer_id' => $customerId,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $completed->id, // will stay completed, but we still want event
        'title' => 'Test',
        'description' => null,
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => null,
        'ended_at' => null,
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    // Act
    $updated = app(FinalizeInterventionReportAction::class)->execute(
        intervention: $intervention,
        validatedByUserId: $user->id,
        travelKm: 12,
        travelMinutes: 30,
        notes: 'All good',
        outcomeCode: 'completed',
    );

    // Assert
    expect($updated->travel_km)->toBe(12);
    expect($updated->travel_minutes)->toBe(30);
    expect($updated->outcome_code)->toBe('completed');
    expect($updated->validated_by_user_id)->toBe($user->id);
    expect($updated->validated_at)->not()->toBeNull();
});
