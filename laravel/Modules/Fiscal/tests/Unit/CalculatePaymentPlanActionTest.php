<?php

declare(strict_types=1);

use Modules\Fiscal\Actions\CalculatePaymentPlanAction;
use Modules\Fiscal\Tests\TestCase;
use Modules\Quotation\DTO\Money;
use PHPUnit\Framework\Assert;
use Safe\DateTimeImmutable;

uses(TestCase::class);

it('splits the total into equal installments', function (): void {
    $action = new CalculatePaymentPlanAction();

    $plans = $action->execute(
        Money::fromFloat(100.0),
        4,
        new DateTimeImmutable('2026-01-01'),
        30,
    );

    Assert::assertCount(4, $plans);

    $sum = array_reduce($plans, fn (float $carry, $plan) => $carry + $plan->amount->asFloat(), 0.0);
    Assert::assertSame(100.0, $sum);
});

it('rejects fewer than one installment', function (): void {
    $action = new CalculatePaymentPlanAction();

    try {
        $action->execute(Money::fromFloat(100.0), 0, new DateTimeImmutable('2026-01-01'), 30);
        Assert::fail('Expected InvalidArgumentException was not thrown.');
    } catch (InvalidArgumentException $e) {
        Assert::assertInstanceOf(InvalidArgumentException::class, $e);
    }
});
