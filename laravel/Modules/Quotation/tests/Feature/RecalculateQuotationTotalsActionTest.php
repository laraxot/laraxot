<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\CalculateQuotationTotalsAction;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

it('recalculates totals and persists line totals', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $quotation = Quotation::query()->create([
        'customer_id' => $customerId,
        'work_order_id' => null,
        'code' => 'Q-TEST-0001',
        'status_code' => 'draft',
        'title' => 'Test quotation',
        'notes' => null,
        'subtotal' => 0,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'tax_percent' => 22.00,
        'tax_amount' => 0,
        'total' => 0,
        'issued_at' => null,
        'confirmed_at' => null,
    ]);

    QuotationItem::query()->create([
        'quotation_id' => $quotation->id,
        'product_id' => null,
        'description' => 'Line 1',
        'quantity' => 2.00,
        'unit_price' => 10.00,
        'discount_percent' => 0.00,
        'line_total' => 0,
        'sort_order' => 0,
    ]);

    QuotationItem::query()->create([
        'quotation_id' => $quotation->id,
        'product_id' => null,
        'description' => 'Line 2',
        'quantity' => 1.00,
        'unit_price' => 100.00,
        'discount_percent' => 10.00,
        'line_total' => 0,
        'sort_order' => 1,
    ]);

    $action = new RecalculateQuotationTotalsAction(new CalculateQuotationTotalsAction());

    // Act
    $updated = $action->execute($quotation->refresh());

    // Assert
    expect($updated->subtotal)->toBe('120.00');
    expect($updated->discount_amount)->toBe('10.00');
    expect($updated->taxable_amount)->toBe('110.00');
    expect($updated->tax_amount)->toBe('24.20');
    expect($updated->total)->toBe('134.20');

    $items = $updated->items()->orderBy('sort_order')->get()->values();
    expect($items[0]?->line_total)->toBe('20.00');
    expect($items[1]?->line_total)->toBe('90.00');
});
