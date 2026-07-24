<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

function makeSearchableWorkOrder(string $code, string $title, string $customerName): WorkOrder
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => $customerName,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return WorkOrder::query()->create([
        'code' => $code,
        'customer_id' => $customerId,
        'title' => $title,
    ]);
}

it('filters work orders by intervention note text', function (): void {
    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Ricercatore',
        'email' => 'search-'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $matchingWorkOrder = makeSearchableWorkOrder('WO-SRCH-001', 'Commessa luci scala', 'Cliente Ricerca 1');
    $otherWorkOrder = makeSearchableWorkOrder('WO-SRCH-002', 'Commessa citofono', 'Cliente Ricerca 2');

    Intervention::query()->create([
        'work_order_id' => $matchingWorkOrder->id,
        'performed_by_user_id' => $userId,
        'scheduled_at' => now(),
        'notes' => 'Sostituzione lampadina LED corridoio piano 2',
    ]);

    Intervention::query()->create([
        'work_order_id' => $otherWorkOrder->id,
        'performed_by_user_id' => $userId,
        'scheduled_at' => now(),
        'notes' => 'Verifica pulsantiera citofono ingresso',
    ]);

    $ids = Intervention::query()
        ->where('work_order_id', $matchingWorkOrder->id)
        ->where('notes', 'like', '%lampadina%')
        ->pluck('id')
        ->all();

    expect($ids)->toHaveCount(1);
});

it('filters work orders by intervention material text', function (): void {
    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Ricercatore Materiali',
        'email' => 'search-mat-'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $matchingWorkOrder = makeSearchableWorkOrder('WO-SRCH-003', 'Commessa lampione', 'Cliente Ricerca 3');
    $otherWorkOrder = makeSearchableWorkOrder('WO-SRCH-004', 'Commessa emergenza', 'Cliente Ricerca 4');

    $matchingIntervention = Intervention::query()->create([
        'work_order_id' => $matchingWorkOrder->id,
        'performed_by_user_id' => $userId,
        'scheduled_at' => now(),
        'notes' => 'Ripristino linea esterna',
    ]);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $matchingIntervention->id,
        'description' => 'Lampione LED 50W',
        'quantity' => 1,
        'unit_price' => 120,
        'line_total' => 120,
    ]);

    $otherIntervention = Intervention::query()->create([
        'work_order_id' => $otherWorkOrder->id,
        'performed_by_user_id' => $userId,
        'scheduled_at' => now(),
        'notes' => 'Controllo batteria tampone',
    ]);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $otherIntervention->id,
        'description' => 'Batteria 12V 7Ah',
        'quantity' => 1,
        'unit_price' => 35,
        'line_total' => 35,
    ]);

    $ids = Intervention::query()
        ->where('work_order_id', $matchingWorkOrder->id)
        ->where(function ($query): void {
            $query
                ->whereHas('materialLines', function ($materialsQuery): void {
                    $materialsQuery->where('description', 'like', '%lampione%');
                })
                ->orWhereHas('inventoryMovements.product', function ($materialsQuery): void {
                    $materialsQuery->where('name', 'like', '%lampione%');
                })
                ->orWhereHas('inventoryMovements', function ($materialsQuery): void {
                    $materialsQuery->where('note', 'like', '%lampione%');
                });
        })
        ->pluck('id')
        ->all();

    expect($ids)->toHaveCount(1)
        ->and($ids[0])->toBe($matchingIntervention->id);
});
