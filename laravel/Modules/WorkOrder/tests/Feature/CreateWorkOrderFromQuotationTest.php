<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\WorkOrder\Actions\CreateWorkOrderFromQuotationAction;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Tests\TestCase;
use Modules\WorkOrder\Models\WorkOrderMaterial;
use PHPUnit\Framework\Assert;

/**
 * @param  array<string, mixed>  $attributes
 */
function quotationToConvert(array $attributes = []): Quotation
{
    $customer = Customer::query()->create(['legal_name' => 'Cliente Conversione S.r.l.', 'is_active' => true]);

    $quotation = Quotation::query()->create(array_merge([
        'customer_id' => $customer->id,
        'code' => 'PRE-CONV-1',
        'status_code' => 'confirmed',
        'title' => 'Insegna luminosa vetrina',
        'project' => 'MM Berlino',
        'notes' => 'Montaggio dopo chiusura negozio',
        'subtotal' => 1000.0,
        'total' => 1220.0,
        'cost_total' => 600.0,
    ], $attributes));

    QuotationItem::query()->create([
        'quotation_id' => $quotation->id,
        'description' => 'Lastra ottone OT63 [mq]',
        'quantity' => 2.5,
        'unit_price' => 116.45,
        'line_total' => 291.13,
        'sort_order' => 0,
    ]);
    QuotationItem::query()->create([
        'quotation_id' => $quotation->id,
        'description' => 'STRIPLED 3500K [mt]',
        'quantity' => 4,
        'unit_price' => 4.5,
        'line_total' => 18.0,
        'sort_order' => 1,
    ]);

    return $quotation->refresh();
}

it('carries the quoted lines into the job order as its bill of materials', function (): void {
    $quotation = quotationToConvert();

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->customer_id)->toBe($quotation->customer_id)
        ->and($workOrder->title)->toBe('Insegna luminosa vetrina')
        ->and($workOrder->description)->toBe('MM Berlino')
        ->and($workOrder->notes)->toBe('Montaggio dopo chiusura negozio')
        ->and($quotation->refresh()->work_order_id)->toBe($workOrder->id);

    $materials = $workOrder->materials()->get();

    expect($materials)->toHaveCount(2);
    Assert::assertInstanceOf(WorkOrderMaterial::class, $materials[0]);
    Assert::assertInstanceOf(WorkOrderMaterial::class, $materials[1]);
    expect($materials[0]->description)->toBe('Lastra ottone OT63 [mq]')
        ->and((float) $materials[0]->quantity)->toBe(2.5)
        ->and((float) $materials[0]->unit_cost)->toBe(116.45)
        // Line total is recomputed, never copied blindly: 2,5 × 116,45 = 291,13
        ->and((float) $materials[0]->line_total)->toBe(291.13)
        ->and($materials[0]->source_code)->toBe('quotation')
        ->and($materials[1]->description)->toBe('STRIPLED 3500K [mt]')
        ->and((float) $materials[1]->line_total)->toBe(18.0);
});

it('uses the quotation delivery date for the job order', function (): void {
    $deliveryAt = CarbonImmutable::now()->addWeeks(3)->startOfHour();
    $quotation = quotationToConvert(['delivery_at' => $deliveryAt]);

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->delivery_due_at?->toDateTimeString())->toBe($deliveryAt->toDateTimeString());
});

it('falls back to the agreed lead time when no delivery date was set', function (): void {
    $quotation = quotationToConvert(['delivery_at' => null, 'lead_time_days' => 20]);

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->delivery_due_at?->toDateString())
        ->toBe(CarbonImmutable::now()->addDays(20)->toDateString());
});

it('leaves the delivery date empty when neither a date nor a lead time is known', function (): void {
    $quotation = quotationToConvert(['delivery_at' => null, 'lead_time_days' => null]);

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->delivery_due_at)->toBeNull();
});

it('opens the job order in the requested status', function (): void {
    $status = WorkOrderStatus::query()->create([
        'code' => 'production',
        'name' => 'In produzione',
        'color' => 'amber',
        'is_terminal' => false,
        'sort_order' => 30,
    ]);

    $quotation = quotationToConvert();

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation, [
        'status_code' => 'production',
    ]);

    expect($workOrder->status_id)->toBe($status->id);
});

it('titles the job order from the quotation code when the quotation has no title', function (): void {
    $quotation = quotationToConvert(['title' => null]);

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->title)->toBe('Da preventivo PRE-CONV-1');

    // An override always wins over both.
    $other = app(CreateWorkOrderFromQuotationAction::class)->execute(
        quotationToConvert(['code' => 'PRE-CONV-2', 'title' => null]),
        ['title' => 'Titolo scelto a mano'],
    );

    expect($other->title)->toBe('Titolo scelto a mano');
});

it('titles the job order by id when the quotation has neither title nor code', function (): void {
    $quotation = quotationToConvert(['title' => null, 'code' => null]);

    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation);

    expect($workOrder->title)->toBe('Da preventivo #'.$quotation->id);
});
