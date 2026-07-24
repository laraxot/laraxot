<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

function makeWorkOrderWithEffort(?int $plannedMinutes, int $actualMinutes): WorkOrder
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Tempi',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Tecnico Effort',
        'email' => 'effort-'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customerId,
        'title' => 'Impianto radiante',
        'planned_minutes' => $plannedMinutes,
    ]);

    Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $userId,
        'scheduled_at' => now(),
        'work_minutes' => $actualMinutes,
        'amount' => 200,
        'callout_fee' => 30,
    ]);

    return $workOrder;
}

it('flags a work order that is running over the planned time', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: 120, actualMinutes: 200);

    expect(WorkOrderResource::effortColor($workOrder))->toBe('danger')
        ->and(WorkOrderResource::effortBadge($workOrder))
        ->toContain(__('work_orders.effort.over_budget'))
        // 80 minuti oltre il previsto, con il segno che lo dice.
        ->toContain('+1h 20m');
});

it('shows a work order still within the planned time as healthy', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: 300, actualMinutes: 120);

    expect(WorkOrderResource::effortColor($workOrder))->toBe('success')
        ->and(WorkOrderResource::effortBadge($workOrder))
        ->toContain(__('work_orders.effort.within_budget'));
});

it('says nothing was planned instead of pretending the work order is on time', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: null, actualMinutes: 90);

    expect(WorkOrderResource::effortColor($workOrder))->toBe('gray')
        ->and(WorkOrderResource::effortBadge($workOrder))->toBe(__('work_orders.effort.not_planned'))
        ->and(WorkOrderResource::effortSummary($workOrder))->toBe(__('work_orders.effort.not_planned'));
});

it('summarises planned, actual and variance for the work order form', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: 120, actualMinutes: 180);

    $summary = WorkOrderResource::effortSummary($workOrder);

    expect($summary)->toContain('2h 00m')   // previsto
        ->and($summary)->toContain('3h 00m') // consuntivo
        ->and($summary)->toContain('+1h 00m')
        ->and($summary)->toContain('+50,0%');
});

it('warns on the pre-invoice when there are undecided extras', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: 120, actualMinutes: 120);

    $intervention = $workOrder->interventions()->firstOrFail();

    // Materiale fuori preventivo su cui nessuno ha ancora deciso.
    $intervention->materialLines()->create([
        'description' => 'Collettore extra',
        'quantity' => 1,
        'unit' => 'pz',
        'unit_price' => 90,
        'line_total' => 90,
        'extra_quantity' => 1,
        'extra_amount' => 90,
        'extra_status_code' => 'pending',
    ]);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($workOrder);

    expect($draft->hasUndecidedExtras())->toBeTrue()
        ->and($draft->pendingExtraTotal)->toBe(90.0)
        // L'extra non deciso resta fuori dal totale: e' il punto della segnalazione.
        ->and($draft->total)->toBe(230.0);
});

it('puts the extra in the invoice total once it is marked to bill', function (): void {
    $workOrder = makeWorkOrderWithEffort(plannedMinutes: 120, actualMinutes: 120);

    $intervention = $workOrder->interventions()->firstOrFail();

    $line = $intervention->materialLines()->create([
        'description' => 'Collettore extra',
        'quantity' => 1,
        'unit' => 'pz',
        'unit_price' => 90,
        'line_total' => 90,
    ]);

    $line->update(['extra_status_code' => 'to_bill']);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($workOrder->refresh());

    expect($draft->hasUndecidedExtras())->toBeFalse()
        ->and($draft->total)->toBe(320.0);
});
