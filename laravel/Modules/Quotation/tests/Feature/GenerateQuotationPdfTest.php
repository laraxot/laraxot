<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\GenerateQuotationPdfAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;

it('generates a quotation PDF', function (): void {
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
        'code' => 'Q-PDF-0001',
        'status_code' => 'draft',
        'title' => 'Test quotation',
        'notes' => "Notes\nLine 2",
        'subtotal' => 0,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'tax_percent' => 22.00,
        'tax_amount' => 0,
        'total' => 0,
        'issued_at' => now(),
        'confirmed_at' => null,
    ]);

    QuotationItem::query()->create([
        'quotation_id' => $quotation->id,
        'product_id' => null,
        'description' => 'Line 1',
        'quantity' => 2.00,
        'unit_price' => 10.00,
        'discount_percent' => 0.00,
        'line_total' => 20.00,
        'sort_order' => 0,
    ]);

    $quotation->forceFill([
        'subtotal' => 20.00,
        'discount_amount' => 0.00,
        'taxable_amount' => 20.00,
        'tax_amount' => 4.40,
        'total' => 24.40,
    ])->save();

    // Act
    $pdf = app(GenerateQuotationPdfAction::class)->execute($quotation->refresh());

    // Assert
    expect($pdf)->toBeString();
    expect(substr($pdf, 0, 4))->toBe('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});

