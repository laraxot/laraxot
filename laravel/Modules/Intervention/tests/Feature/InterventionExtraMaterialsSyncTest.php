<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Quotation\Models\Quotation;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Il ricalcolo dell'extra preventivo e' agganciato a un observer sulle righe
 * materiale: qui si verifica che scatti davvero, perche' prima non lo faceva
 * nessuno e i campi restavano a zero.
 */
function makeInterventionWithQuotation(float $quotedQuantity): Intervention
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Extra',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var WorkOrder $workOrder */
    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customerId,
        'title' => 'Sostituzione caldaia',
    ]);

    /** @var Quotation $quotation */
    $quotation = Quotation::query()->create([
        'code' => 'Q-EXTRA-1',
        'customer_id' => $customerId,
        'work_order_id' => $workOrder->id,
        'status_code' => 'confirmed',
        'tax_percent' => 22,
        'subtotal' => 0,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'tax_amount' => 0,
        'total' => 0,
    ]);

    $quotation->items()->create([
        'description' => 'Tubo multistrato 16mm',
        'quantity' => $quotedQuantity,
        'unit_price' => 10,
        'discount_percent' => 0,
        'line_total' => 0,
        'sort_order' => 1,
    ]);

    /** @var Intervention $intervention */
    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => User::factory()->create()->id,
        'scheduled_at' => now(),
        'work_minutes' => 60,
    ]);

    return $intervention;
}

it('computes the out-of-quotation extra as soon as a material line is created', function (): void {
    $intervention = makeInterventionWithQuotation(quotedQuantity: 3);

    // In cantiere ne servono 5: il preventivo ne copre 3, l'extra e' 2.
    $line = $intervention->materialLines()->create([
        'description' => 'Tubo multistrato 16mm',
        'quantity' => 5,
        'unit' => 'mt',
        'unit_price' => 10,
        'line_total' => 50,
    ]);

    $line->refresh();

    expect((float) $line->extra_quantity)->toBe(2.0)
        ->and((float) $line->extra_amount)->toBe(20.0)
        ->and($line->isExtra())->toBeTrue();
});

it('recomputes the extra when the material line quantity changes', function (): void {
    $intervention = makeInterventionWithQuotation(quotedQuantity: 3);

    $line = $intervention->materialLines()->create([
        'description' => 'Tubo multistrato 16mm',
        'quantity' => 5,
        'unit' => 'mt',
        'unit_price' => 10,
        'line_total' => 50,
    ]);

    expect((float) $line->refresh()->extra_quantity)->toBe(2.0);

    // Rientra nel preventivato: l'extra deve sparire, non restare appiccicato.
    $line->update(['quantity' => 3, 'line_total' => 30]);

    expect((float) $line->refresh()->extra_quantity)->toBe(0.0)
        ->and((float) $line->refresh()->extra_amount)->toBe(0.0);
});

it('leaves no extra when the material is fully covered by the quotation', function (): void {
    $intervention = makeInterventionWithQuotation(quotedQuantity: 10);

    $line = $intervention->materialLines()->create([
        'description' => 'Tubo multistrato 16mm',
        'quantity' => 4,
        'unit' => 'mt',
        'unit_price' => 10,
        'line_total' => 40,
    ]);

    expect((float) $line->refresh()->extra_quantity)->toBe(0.0)
        ->and($line->isExtra())->toBeFalse();
});

it('defaults a fresh extra line to "to be decided"', function (): void {
    $intervention = makeInterventionWithQuotation(quotedQuantity: 0);

    $line = $intervention->materialLines()->create([
        'description' => 'Guarnizione',
        'quantity' => 2,
        'unit' => 'pz',
        'unit_price' => 5,
        'line_total' => 10,
    ]);

    expect((float) $line->refresh()->extra_amount)->toBe(10.0)
        ->and($line->extra_status_code)->toBe('pending');
});

it('offers the three extra decisions with a colour that reads at a glance', function (): void {
    expect(array_keys(InterventionResource::extraStatusOptions()))
        ->toBe(['pending', 'to_bill', 'waived'])
        ->and(InterventionResource::extraStatusColor('to_bill'))->toBe('success')
        ->and(InterventionResource::extraStatusColor('waived'))->toBe('gray')
        // "Da decidere" resta giallo: e' il caso in cui si sta per regalare.
        ->and(InterventionResource::extraStatusColor('pending'))->toBe('warning');
});
