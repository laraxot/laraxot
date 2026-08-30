<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(\Modules\Quotation\Tests\TestCase::class);

it('creates a quotation and recalculates totals after adding and removing items', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $quotation = Quotation::query()->create([
        'code' => 'Q-CRUD-0001',
        'customer_id' => $customerId,
        'work_order_id' => null,
        'status_code' => 'draft',
        'title' => 'Test quotation',
        'notes' => null,
        'issued_at' => now(),
        'confirmed_at' => null,
        'tax_percent' => 22,
        'subtotal' => 0,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'tax_amount' => 0,
        'total' => 0,
    ]);

    // Act: add two items
    $quotation->items()->create([
        'product_id' => null,
        'description' => 'Item A',
        'quantity' => 1,
        'unit_price' => 100,
        'discount_percent' => 0,
        'line_total' => 0,
        'sort_order' => 1,
    ]);
    $quotation->items()->create([
        'product_id' => null,
        'description' => 'Item B',
        'quantity' => 2,
        'unit_price' => 50,
        'discount_percent' => 10,
        'line_total' => 0,
        'sort_order' => 2,
    ]);

    app(RecalculateQuotationTotalsAction::class)->execute($quotation->refresh());

    // Assert totals are not zero
    $quotation->refresh();
    expect((float) $quotation->subtotal)->toBeGreaterThan(0);
    expect((float) $quotation->total)->toBeGreaterThan(0);
    expect($quotation->items()->count())->toBe(2);

    // Act: remove one item and recalc
    $item = $quotation->items()->orderByDesc('id')->first();
    if ($item !== null) {
        $item->delete();
    }
    app(RecalculateQuotationTotalsAction::class)->execute($quotation->refresh());

    // Assert items decreased and totals updated
    $quotation->refresh();
    expect($quotation->items()->count())->toBe(1);
    expect((float) $quotation->subtotal)->toBeGreaterThan(0);

    // Act: soft-delete quotation
    $quotation->delete();

    // Assert quotation soft deleted
    expect($quotation->refresh()->trashed())->toBeTrue();
});
