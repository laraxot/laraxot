<?php

declare(strict_types=1);

uses(\Modules\Billing\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction;
use Modules\Billing\Domain\DTO\PreInvoiceLine;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Quotation\Models\Quotation;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @return array{workOrder: WorkOrder, intervention: Intervention}
 */
function preInvoiceScenario(float $labor = 0, float $callout = 0): array
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Fatturazione',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $userId = (int) DB::table('users')->insertGetId([
        'name' => 'Tecnico',
        'email' => 'tec'.uniqid().'@test.local',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customerId,
        'title' => 'Commessa da fatturare',
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->getKey(),
        'performed_by_user_id' => $userId,
        'work_minutes' => 120,
        'amount' => $labor,
        'callout_fee' => $callout,
    ]);

    return ['workOrder' => $workOrder, 'intervention' => $intervention];
}

/**
 * Preventiva una quantita' di un materiale sulla commessa.
 *
 * L'extra preventivo non e' piu' un campo che si scrive a mano: viene ricalcolato
 * dall'observer sulle righe materiale ogni volta che cambiano. Perche' una riga
 * risulti "2 pezzi fuori preventivo" deve quindi esistere davvero un preventivo
 * che ne copre gli altri, altrimenti l'intera riga e' extra.
 */
function quoteMaterialForPreInvoice(WorkOrder $workOrder, string $description, float $quantity): void
{
    /** @var Quotation $quotation */
    $quotation = Quotation::query()->create([
        'code' => 'Q-'.uniqid(),
        'customer_id' => $workOrder->customer_id,
        'work_order_id' => $workOrder->getKey(),
        'status_code' => 'confirmed',
        'tax_percent' => 22,
        'subtotal' => 0,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'tax_amount' => 0,
        'total' => 0,
    ]);

    $quotation->items()->create([
        'description' => $description,
        'quantity' => $quantity,
        'unit_price' => 0,
        'discount_percent' => 0,
        'line_total' => 0,
        'sort_order' => 1,
    ]);
}

it('proposes labor, callout and materials as separate lines', function (): void {
    $scenario = preInvoiceScenario(labor: 200, callout: 30);

    // Tutto il tubo usato e' coperto dal preventivo: nessun extra.
    quoteMaterialForPreInvoice($scenario['workOrder'], 'Tubo', 10);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Tubo',
        'quantity' => 10,
        'unit_price' => 5,
        'line_total' => 50,
    ]);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($scenario['workOrder']);

    expect($draft->total)->toBe(280.0)
        ->and($draft->lines)->toHaveCount(3)
        ->and(array_map(static fn (PreInvoiceLine $l): string => $l->sourceCode, $draft->lines))
        ->toBe(['labor', 'callout', 'material'])
        ->and($draft->hasUndecidedExtras())->toBeFalse();
});

it('keeps undecided extras out of the total but reports them', function (): void {
    $scenario = preInvoiceScenario(labor: 100);

    // Il preventivo copre 3 raccordi, in cantiere ne servono 5: extra = 2.
    quoteMaterialForPreInvoice($scenario['workOrder'], 'Raccordo', 3);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Raccordo',
        'quantity' => 5,
        'unit_price' => 4,
        'line_total' => 20,
        'extra_quantity' => 2,
        'extra_amount' => 8,
        'extra_status_code' => 'pending',
    ]);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($scenario['workOrder']);

    // 100 manodopera + 12 di materiale coperto (20 - 8 di extra non deciso).
    expect($draft->total)->toBe(112.0)
        ->and($draft->pendingExtraTotal)->toBe(8.0)
        ->and($draft->hasUndecidedExtras())->toBeTrue();
});

it('bills extras marked as to_bill', function (): void {
    $scenario = preInvoiceScenario(labor: 100);

    // Il preventivo copre 3 raccordi, in cantiere ne servono 5: extra = 2.
    quoteMaterialForPreInvoice($scenario['workOrder'], 'Raccordo', 3);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Raccordo',
        'quantity' => 5,
        'unit_price' => 4,
        'line_total' => 20,
        'extra_quantity' => 2,
        'extra_amount' => 8,
        'extra_status_code' => 'to_bill',
    ]);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($scenario['workOrder']);

    expect($draft->total)->toBe(120.0)
        ->and($draft->pendingExtraTotal)->toBe(0.0)
        ->and($draft->hasUndecidedExtras())->toBeFalse();

    $extraLines = array_values(array_filter(
        $draft->lines,
        static fn (PreInvoiceLine $l): bool => $l->sourceCode === 'extra',
    ));

    expect($extraLines)->toHaveCount(1)
        ->and($extraLines[0]->description)->toBe('Extra preventivo: Raccordo')
        ->and($extraLines[0]->amount)->toBe(8.0);
});

it('excludes extras that were deliberately waived', function (): void {
    $scenario = preInvoiceScenario(labor: 100);

    InterventionMaterialLine::query()->create([
        'intervention_id' => $scenario['intervention']->getKey(),
        'description' => 'Omaggio',
        'quantity' => 1,
        'unit_price' => 30,
        'line_total' => 30,
        'extra_quantity' => 1,
        'extra_amount' => 30,
        'extra_status_code' => 'waived',
    ]);

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($scenario['workOrder']);

    expect($draft->total)->toBe(100.0)
        ->and($draft->hasUndecidedExtras())->toBeFalse();
});

it('returns an empty draft for a work order with no interventions', function (): void {
    $scenario = preInvoiceScenario();
    $scenario['intervention']->delete();

    $draft = app(BuildWorkOrderPreInvoiceAction::class)->execute($scenario['workOrder']);

    expect($draft->lines)->toBe([])
        ->and($draft->total)->toBe(0.0);
});
