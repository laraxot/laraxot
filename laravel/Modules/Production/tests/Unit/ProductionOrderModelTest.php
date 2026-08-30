<?php

declare(strict_types=1);

use Modules\Production\Database\Factories\ProductionOrderFactory;
use Modules\Production\Database\Factories\ProductionPhaseFactory;
use Modules\Production\Models\ProductionOrder;
use Modules\Production\Models\ProductionPhase;
use Modules\Production\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Production\Tests\TestCase::class);

it('creates a production order', function (): void {
    $order = ProductionOrderFactory::new()->createOne();

    Assert::assertInstanceOf(ProductionOrder::class, $order);
    Assert::assertSame('planned', $order->status);
});

it('links a phase to a production order', function (): void {
    $order = ProductionOrderFactory::new()->createOne();
    $phase = ProductionPhaseFactory::new()->createOne(['production_order_id' => $order->id]);

    Assert::assertInstanceOf(ProductionPhase::class, $phase);
    Assert::assertSame(1, $order->phases()->count());
});
