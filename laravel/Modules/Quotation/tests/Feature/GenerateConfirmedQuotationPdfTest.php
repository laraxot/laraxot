<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\GenerateConfirmedQuotationPdfAction;
use Modules\Quotation\Models\Quotation;

it('generates a confirmed quotation PDF', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var Quotation $quotation */
    $quotation = Quotation::query()->create([
        'code' => 'Q-CNF-0001',
        'customer_id' => $customerId,
        'work_order_id' => null,
        'status_code' => 'confirmed',
        'title' => 'Confirmed quote',
        'notes' => null,
        'issued_at' => now()->subDay(),
        'confirmed_at' => now(),
        'subtotal' => 100.00,
        'discount_amount' => 0.00,
        'taxable_amount' => 100.00,
        'tax_percent' => 22.00,
        'tax_amount' => 22.00,
        'total' => 122.00,
    ]);

    // Act
    $pdf = app(GenerateConfirmedQuotationPdfAction::class)->execute($quotation);

    // Assert
    expect($pdf)->toStartWith('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});

