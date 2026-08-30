<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Actions\FinalizeInterventionReportAction;
use Modules\Intervention\Models\Intervention;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

it('finalizes an intervention with outcome completed and updates work order status', function (): void {
    $admin = UserFactory::new()->createOne();

    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => true,
    ]);

    $inProgress = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In progress', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 10],
    );

    $completed = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'completed'],
        ['name' => 'Completed', 'color' => 'green', 'is_terminal' => true, 'sort_order' => 99],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-FIN-001',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $inProgress->id,
        'title' => 'Test work order',
        'description' => null,
        'notes' => null,
        'opened_at' => CarbonImmutable::now(),
        'scheduled_at' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $admin->id,
        'started_at' => CarbonImmutable::now()->subHour(),
        'ended_at' => CarbonImmutable::now(),
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    $finalized = app(FinalizeInterventionReportAction::class)->execute(
        intervention: $intervention,
        validatedByUserId: (int) $admin->id,
        travelKm: 12,
        travelMinutes: 25,
        notes: 'Done',
        outcomeCode: 'completed',
    );

    expect($finalized->outcome_code)->toBe('completed');
    expect($finalized->notes_html)->toBe('Done');
    expect($finalized->validated_by_user_id)->toBe((int) $admin->id);
    expect($finalized->validated_at)->not->toBeNull();

    $workOrder->refresh();
    expect((int) $workOrder->status_id)->toBe((int) $completed->id);
});

it('finalizes an intervention with outcome suspended (keeps work order in progress)', function (): void {
    $user = UserFactory::new()->createOne();

    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => true,
    ]);

    $inProgress = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In progress', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 10],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-FIN-002',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $inProgress->id,
        'title' => 'Test work order',
        'description' => null,
        'notes' => null,
        'opened_at' => CarbonImmutable::now(),
        'scheduled_at' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => CarbonImmutable::now()->subHour(),
        'ended_at' => CarbonImmutable::now(),
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    $finalized = app(FinalizeInterventionReportAction::class)->execute(
        intervention: $intervention,
        validatedByUserId: (int) $user->id,
        travelKm: 0,
        travelMinutes: 0,
        notes: null,
        outcomeCode: 'suspended',
    );

    expect($finalized->outcome_code)->toBe('suspended');

    $workOrder->refresh();
    expect((int) $workOrder->status_id)->toBe((int) $inProgress->id);
});

it('finalizes an intervention with outcome quotation and adds an activity', function (): void {
    $user = UserFactory::new()->createOne();

    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => true,
    ]);

    $inProgress = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In progress', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 10],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-FIN-003',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $inProgress->id,
        'title' => 'Test work order',
        'description' => null,
        'notes' => null,
        'opened_at' => CarbonImmutable::now(),
        'scheduled_at' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'started_at' => CarbonImmutable::now()->subHour(),
        'ended_at' => CarbonImmutable::now(),
        'travel_km' => 0,
        'travel_minutes' => 0,
        'work_minutes' => 0,
        'notes' => null,
    ]);

    $finalized = app(FinalizeInterventionReportAction::class)->execute(
        intervention: $intervention,
        validatedByUserId: (int) $user->id,
        travelKm: 0,
        travelMinutes: 0,
        notes: 'Need a quote',
        outcomeCode: 'quotation',
    );

    expect($finalized->outcome_code)->toBe('quotation');
});
