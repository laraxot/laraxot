<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Modules\Quotation\Domain\Actions\CalculateQuotationTotalsAction;
use Modules\Quotation\Domain\DTO\QuotationItemData;

it('calculates totals with per-line discounts and VAT percent', function (): void {
    // Arrange
    $action = new CalculateQuotationTotalsAction();
    $items = [
        new QuotationItemData(quantity: 2.0, unitPrice: 10.00, discountPercent: 0.0),   // 20.00
        new QuotationItemData(quantity: 1.0, unitPrice: 100.00, discountPercent: 10.0), // 90.00
    ];

    // Act
    $totals = $action->execute($items, taxPercent: 22.0);

    // Assert
    expect($totals->subtotal->asDecimalString())->toBe('120.00');
    expect($totals->discountAmount->asDecimalString())->toBe('10.00');
    expect($totals->taxableAmount->asDecimalString())->toBe('110.00');
    expect($totals->taxAmount->asDecimalString())->toBe('24.20');
    expect($totals->total->asDecimalString())->toBe('134.20');
    expect($totals->lineTotals[0]->asDecimalString())->toBe('20.00');
    expect($totals->lineTotals[1]->asDecimalString())->toBe('90.00');
});

