<?php

declare(strict_types=1);

use Modules\Fiscal\Actions\CalculateInvoiceTotalsAction;
use Modules\Fiscal\Datas\InvoiceLineData;
use Modules\Fiscal\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('computes taxable, vat and total for a single line', function (): void {
    $action = new CalculateInvoiceTotalsAction();

    $totals = $action->execute([
        new InvoiceLineData(
            quantity: 2,
            unitPrice: 100.0,
            discountPercent: 0,
            vatRatePercent: 22,
        ),
    ]);

    Assert::assertSame(200.0, $totals->taxable->asFloat());
    Assert::assertSame(44.0, $totals->vat->asFloat());
    Assert::assertSame(244.0, $totals->total->asFloat());
});

it('groups vat by rate across multiple lines', function (): void {
    $action = new CalculateInvoiceTotalsAction();

    $totals = $action->execute([
        new InvoiceLineData(quantity: 1, unitPrice: 100.0, discountPercent: 0, vatRatePercent: 22),
        new InvoiceLineData(quantity: 1, unitPrice: 100.0, discountPercent: 0, vatRatePercent: 4),
    ]);

    Assert::assertArrayHasKey('22', $totals->vatByRate);
    Assert::assertArrayHasKey('4', $totals->vatByRate);
});
