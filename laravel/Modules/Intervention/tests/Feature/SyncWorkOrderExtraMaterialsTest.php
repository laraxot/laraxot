<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\SyncWorkOrderExtraMaterialsAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @return array{workOrder: WorkOrder, userId: int, customerId: int}
 */
function workOrderExtraScenario(): array
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Condominio Demo',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $userId = (int) UserFactory::new()->createOne()->getKey();

    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customerId,
        'title' => 'Commessa multi-intervento',
    ]);

    $quotationId = (int) DB::table('quotations')->insertGetId([
        'customer_id' => $customerId,
        'work_order_id' => $workOrder->getKey(),
        'status_code' => 'confirmed',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('quotation_items')->insert([
        'quotation_id' => $quotationId,
        'description' => 'Valvola di zona',
        'quantity' => 4,
        'unit_price' => 62,
        'line_total' => 248,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return ['workOrder' => $workOrder, 'userId' => $userId, 'customerId' => $customerId];
}

/**
 * @param  array{workOrder: WorkOrder, userId: int, customerId: int}  $scenario
 */
function interventionWithValves(array $scenario, string $startedAt, float $quantity): InterventionMaterialLine
{
    $intervention = Intervention::query()->create([
        'work_order_id' => $scenario['workOrder']->getKey(),
        'performed_by_user_id' => $scenario['userId'],
        'started_at' => $startedAt,
        'work_minutes' => 240,
    ]);

    return InterventionMaterialLine::query()->create([
        'intervention_id' => $intervention->getKey(),
        'description' => 'Valvola di zona',
        'quantity' => $quantity,
        'unit_price' => 62,
        'line_total' => $quantity * 62,
    ]);
}

it('consumes the quoted quantity across interventions of the same work order', function (): void {
    // Preventivate 4 valvole: 4 nella prima uscita e 2 nella seconda -> 2 extra.
    // Valutando un intervento alla volta, la seconda uscita sembrerebbe coperta.
    $scenario = workOrderExtraScenario();
    $first = interventionWithValves($scenario, '2026-07-01 08:00:00', 4);
    $second = interventionWithValves($scenario, '2026-07-05 08:00:00', 2);

    $total = app(SyncWorkOrderExtraMaterialsAction::class)->execute($scenario['workOrder']);

    expect($total)->toBe(124.0)
        ->and((float) $first->refresh()->extra_quantity)->toBe(0.0)
        ->and((float) $second->refresh()->extra_quantity)->toBe(2.0)
        ->and((float) $second->extra_amount)->toBe(124.0);
});

it('charges the oldest interventions to the quotation first', function (): void {
    $scenario = workOrderExtraScenario();
    $later = interventionWithValves($scenario, '2026-07-10 08:00:00', 3);
    $earlier = interventionWithValves($scenario, '2026-07-02 08:00:00', 3);

    app(SyncWorkOrderExtraMaterialsAction::class)->execute($scenario['workOrder']);

    // Il preventivo copre le 3 della prima uscita e 1 della seconda: 2 restano extra.
    expect((float) $earlier->refresh()->extra_quantity)->toBe(0.0)
        ->and((float) $later->refresh()->extra_quantity)->toBe(2.0);
});

it('recalculates the whole work order starting from one intervention', function (): void {
    $scenario = workOrderExtraScenario();
    interventionWithValves($scenario, '2026-07-01 08:00:00', 4);
    $second = interventionWithValves($scenario, '2026-07-05 08:00:00', 2);

    $intervention = Intervention::query()->findOrFail($second->intervention_id);
    $total = app(SyncWorkOrderExtraMaterialsAction::class)->executeForIntervention($intervention);

    expect($total)->toBe(124.0)
        ->and((float) $second->refresh()->extra_quantity)->toBe(2.0);
});
