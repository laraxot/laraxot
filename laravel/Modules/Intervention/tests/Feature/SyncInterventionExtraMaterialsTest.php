<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\SyncInterventionExtraMaterialsAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\User\Database\Factories\UserFactory;

/**
 * @return array{intervention: Intervention, workOrderId: int, customerId: int}
 */
function extraMaterialsScenario(): array
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Condominio Parco Verde',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = (int) DB::table('work_orders')->insertGetId([
        'customer_id' => $customerId,
        'code' => 'CM-2026-001',
        'title' => 'Sostituzione caldaia',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $userId = UserFactory::new()->createOne()->id;

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $userId,
        'work_minutes' => 60,
    ]);

    return ['intervention' => $intervention, 'workOrderId' => $workOrderId, 'customerId' => $customerId];
}

function quoteMaterial(int $workOrderId, int $customerId, string $description, float $quantity): int
{
    $quotationId = (int) DB::table('quotations')->insertGetId([
        'customer_id' => $customerId,
        'work_order_id' => $workOrderId,
        'status_code' => 'confirmed',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return (int) DB::table('quotation_items')->insertGetId([
        'quotation_id' => $quotationId,
        'description' => $description,
        'quantity' => $quantity,
        'unit_price' => 0,
        'line_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('flags material used beyond the quotation as extra', function (): void {
    $scenario = extraMaterialsScenario();
    quoteMaterial($scenario['workOrderId'], $scenario['customerId'], 'Raccordo a T', 3);

    $line = InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Raccordo a T',
        'quantity' => 5,
        'unit_price' => 4,
        'line_total' => 20,
    ]);

    $total = app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']);

    $line->refresh();

    expect($total)->toBe(8.0)
        ->and((float) $line->extra_quantity)->toBe(2.0)
        ->and((float) $line->extra_amount)->toBe(8.0)
        ->and($line->isExtra())->toBeTrue()
        ->and($line->extra_status_code)->toBe('pending');
});

it('treats everything as extra when the work order has no quotation', function (): void {
    $scenario = extraMaterialsScenario();

    $line = InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Valvola',
        'quantity' => 2,
        'unit_price' => 15,
        'line_total' => 30,
    ]);

    $total = app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']);

    expect($total)->toBe(30.0)
        ->and((float) $line->refresh()->extra_quantity)->toBe(2.0);
});

it('does not cover materials with a rejected quotation', function (): void {
    $scenario = extraMaterialsScenario();

    $quotationId = (int) DB::table('quotations')->insertGetId([
        'customer_id' => $scenario['customerId'],
        'work_order_id' => $scenario['workOrderId'],
        'status_code' => 'rejected',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('quotation_items')->insert([
        'quotation_id' => $quotationId,
        'description' => 'Pompa',
        'quantity' => 10,
        'unit_price' => 0,
        'line_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $line = InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Pompa',
        'quantity' => 1,
        'unit_price' => 200,
        'line_total' => 200,
    ]);

    $total = app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']);

    expect($total)->toBe(200.0)
        ->and($line->refresh()->isExtra())->toBeTrue();
});

it('links the material line to the quotation item that covers it', function (): void {
    $scenario = extraMaterialsScenario();
    $itemId = quoteMaterial($scenario['workOrderId'], $scenario['customerId'], 'Tubo multistrato', 10);

    $line = InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Tubo multistrato',
        'quantity' => 10,
        'unit_price' => 3,
        'line_total' => 30,
    ]);

    $total = app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']);

    $line->refresh();

    expect($total)->toBe(0.0)
        ->and($line->isExtra())->toBeFalse()
        ->and($line->quotation_item_id)->toBe($itemId)
        ->and($line->quotationItem?->description)->toBe('Tubo multistrato');
});

it('returns zero extra when the intervention has no material lines', function (): void {
    $scenario = extraMaterialsScenario();

    expect(app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']))->toBe(0.0);
});

it('ignores quotations of other work orders', function (): void {
    $scenario = extraMaterialsScenario();
    $otherWorkOrderId = (int) DB::table('work_orders')->insertGetId([
        'customer_id' => $scenario['customerId'],
        'code' => 'CM-2026-002',
        'title' => 'Altra commessa',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    quoteMaterial($otherWorkOrderId, $scenario['customerId'], 'Caldaia', 1);

    $line = InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Caldaia',
        'quantity' => 1,
        'unit_price' => 900,
        'line_total' => 900,
    ]);

    expect(app(SyncInterventionExtraMaterialsAction::class)->execute($scenario['intervention']))->toBe(900.0)
        ->and($line->refresh()->isExtra())->toBeTrue();
});
