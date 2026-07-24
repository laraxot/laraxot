<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\ApplyQuotationTemplateToQuotationAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationTemplate;

it('applies a quotation template to a quotation and recalculates totals', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var QuotationTemplate $template */
    $template = QuotationTemplate::query()->create([
        'title' => 'Template A',
        'notes' => null,
    ]);

    $template->items()->create([
        'product_id' => null,
        'description' => 'Line 1',
        'quantity' => 1,
        'unit_price' => 100,
        'discount_percent' => 0,
        'line_total' => 0,
        'sort_order' => 1,
    ]);
    $template->items()->create([
        'product_id' => null,
        'description' => 'Line 2',
        'quantity' => 2,
        'unit_price' => 50,
        'discount_percent' => 10,
        'line_total' => 0,
        'sort_order' => 2,
    ]);

    /** @var Quotation $quotation */
    $quotation = Quotation::query()->create([
        'code' => 'Q-TPL-0001',
        'customer_id' => $customerId,
        'work_order_id' => null,
        'status_code' => 'draft',
        'title' => 'Q',
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

    // Act
    app(ApplyQuotationTemplateToQuotationAction::class)->execute($quotation, $template);

    // Assert
    $quotation->refresh();
    expect($quotation->items()->count())->toBe(2);
    expect((float) $quotation->total)->toBeGreaterThan(0);
});

