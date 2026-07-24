<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\ComputeWorkOrderEffortAction;
use Modules\WorkOrder\Domain\DTO\WorkOrderEffort;
use Modules\WorkOrder\Models\WorkOrder;

function workOrderWithInterventions(?int $plannedMinutes, array $interventionMinutes): WorkOrder
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Commessa',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Tecnico',
        'email' => 'tecnico'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customerId,
        'title' => 'Impianto termoidraulico',
        'planned_minutes' => $plannedMinutes,
    ]);

    foreach ($interventionMinutes as $minutes) {
        DB::table('interventions')->insert([
            'work_order_id' => $workOrder->getKey(),
            'performed_by_user_id' => $userId,
            'work_minutes' => $minutes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return $workOrder;
}

it('sums the minutes of all interventions as actual effort', function (): void {
    $workOrder = workOrderWithInterventions(600, [120, 180, 90]);

    $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);

    expect($effort->actualMinutes)->toBe(390)
        ->and($effort->plannedMinutes)->toBe(600)
        ->and($effort->varianceMinutes())->toBe(-210)
        ->and($effort->isOverBudget())->toBeFalse();
});

it('flags a work order that went over the planned time', function (): void {
    $workOrder = workOrderWithInterventions(300, [240, 180]);

    $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);

    expect($effort->actualMinutes)->toBe(420)
        ->and($effort->varianceMinutes())->toBe(120)
        ->and($effort->variancePercent())->toBe(40.0)
        ->and($effort->isOverBudget())->toBeTrue();
});

it('reports no variance when nothing was planned', function (): void {
    $workOrder = workOrderWithInterventions(null, [60]);

    $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);

    expect($effort->varianceMinutes())->toBeNull()
        ->and($effort->variancePercent())->toBeNull()
        ->and($effort->isOverBudget())->toBeFalse();
});

it('reports zero actual effort when there are no interventions yet', function (): void {
    $workOrder = workOrderWithInterventions(480, []);

    $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);

    expect($effort->actualMinutes)->toBe(0)
        ->and($effort->varianceMinutes())->toBe(-480);
});

it('avoids dividing by zero when the planned time is zero', function (): void {
    $effort = new WorkOrderEffort(plannedMinutes: 0, actualMinutes: 120);

    expect($effort->variancePercent())->toBeNull()
        ->and($effort->varianceMinutes())->toBe(120)
        ->and($effort->isOverBudget())->toBeTrue();
});
