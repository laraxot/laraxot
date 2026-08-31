<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Models\Intervention;
use Modules\Quotation\Actions\CreateQuotationFromInterventionAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\Quotation\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Quotation\Tests\TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function interventionForQuotation(array $attributes = []): Intervention
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Preventivo S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->create(array_merge([
        'work_order_id' => null,
        'customer_id' => $customerId,
        'scheduled_at' => now(),
        'started_at' => now(),
        'work_minutes' => 60,
        'materials_total' => 0,
    ], $attributes));
}

it('turns an intervention report into a draft quotation with materials, labour and callout in order', function (): void {
    $intervention = interventionForQuotation([
        'amount' => 80.0,
        'callout_fee' => 25.0,
    ]);

    $intervention->materialLines()->createMany([
        ['description' => 'Valvola termostatica', 'quantity' => 2, 'unit_price' => 15.5],
        ['description' => 'Raccordo', 'quantity' => 1, 'unit_price' => 3.0],
    ]);

    $quotation = app(CreateQuotationFromInterventionAction::class)->execute($intervention->refresh());

    expect($quotation)->toBeInstanceOf(Quotation::class);
    expect($quotation?->status_code)->toBe('draft');

    $items = ($quotation?->items()->orderBy('sort_order')->get() ?? collect())->values();
    expect($items)->toHaveCount(4);

    $ordered = [];
    foreach ($items as $item) {
        Assert::assertInstanceOf(QuotationItem::class, $item);
        $ordered[] = $item;
    }

    expect((string) $ordered[0]->description)->toBe('Valvola termostatica');
    expect((string) $ordered[1]->description)->toBe('Raccordo');
    expect((float) $ordered[2]->unit_price)->toBe(80.0);
    expect((float) $ordered[3]->unit_price)->toBe(25.0);
});

it('returns null when the intervention has nothing to quote', function (): void {
    $intervention = interventionForQuotation();

    $quotation = app(CreateQuotationFromInterventionAction::class)->execute($intervention);

    expect($quotation)->toBeNull();
});

it('returns null when no customer can be resolved', function (): void {
    $intervention = Intervention::query()->create([
        'work_order_id' => null,
        'customer_id' => null,
        'scheduled_at' => now(),
        'work_minutes' => 0,
        'materials_total' => 0,
        'amount' => 50.0,
    ]);

    expect(app(CreateQuotationFromInterventionAction::class)->execute($intervention))->toBeNull();
});
