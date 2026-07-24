<?php

declare(strict_types=1);

use Modules\Fiscal\Actions\CalculateVatLiquidationAction;
use Modules\Fiscal\Datas\VatableAmount;
use Modules\Fiscal\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('computes the period balance between sales and purchases', function (): void {
    $action = new CalculateVatLiquidationAction();

    $liquidation = $action->execute(
        '2026-01-01',
        '2026-01-31',
        [new VatableAmount(ratePercent: 22, taxable: 1000.0)],
        [new VatableAmount(ratePercent: 22, taxable: 500.0)],
    );

    Assert::assertSame(220.0, $liquidation->totalDebit->asFloat());
    Assert::assertSame(110.0, $liquidation->totalCredit->asFloat());
    Assert::assertSame(110.0, $liquidation->balance->asFloat());
    Assert::assertTrue($liquidation->isPayable());
});
