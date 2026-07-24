<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use InvalidArgumentException;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\RegisterTimberStockMovementAction;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;
use Modules\TimberBilling\Queries\GetTimberStockMovementsQuery;

it('registers a load movement and increases product stock', function (): void {
    $product = TimberProduct::factory()->create(['unit_of_measure' => 'quintali']);

    $movement = app(RegisterTimberStockMovementAction::class)->execute(
        product: $product,
        directionCode: 'load',
        quantity: 100.0,
        unitOfMeasure: 'quintali',
        documentReference: 'DDT 244 del 3/7',
        lotCode: 'LOT-2026-0001',
    );

    expect($movement->direction_code)->toBe('load')
        ->and((float) $movement->quantity)->toBe(100.0)
        ->and($movement->category)->toBe($product->category)
        ->and(app(GetTimberStockMovementsQuery::class)->stockForProduct($product->getKey()))->toBe(100.0);
});

it('decreases stock on an unload movement', function (): void {
    $product = TimberProduct::factory()->create();
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($product, 'load', 100.0, 'quintali');
    $action->execute($product, 'unload', 30.0, 'quintali');

    expect(app(GetTimberStockMovementsQuery::class)->stockForProduct($product->getKey()))->toBe(70.0);
});

it('applies a signed adjustment to stock', function (): void {
    $product = TimberProduct::factory()->create();
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($product, 'load', 50.0, 'quintali');
    $action->execute($product, 'adjustment', -12.0, 'quintali');

    expect(app(GetTimberStockMovementsQuery::class)->stockForProduct($product->getKey()))->toBe(38.0);
});

it('rejects an unknown direction', function (): void {
    $product = TimberProduct::factory()->create();

    app(RegisterTimberStockMovementAction::class)->execute($product, 'teleport', 10.0, 'quintali');
})->throws(InvalidArgumentException::class);

it('rejects a non-positive load quantity', function (): void {
    $product = TimberProduct::factory()->create();

    app(RegisterTimberStockMovementAction::class)->execute($product, 'load', 0.0, 'quintali');
})->throws(InvalidArgumentException::class);

it('rejects a zero adjustment', function (): void {
    $product = TimberProduct::factory()->create();

    app(RegisterTimberStockMovementAction::class)->execute($product, 'adjustment', 0.0, 'quintali');
})->throws(InvalidArgumentException::class);

it('updates last and weighted average cost on a valued load', function (): void {
    $product = TimberProduct::factory()->create(['average_cost' => null, 'last_cost' => null]);
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($product, 'load', 100.0, 'quintali', unitCost: 8.0);
    $action->execute($product, 'load', 100.0, 'quintali', unitCost: 10.0);

    $product->refresh();

    expect((float) $product->last_cost)->toBe(10.0)
        ->and((float) $product->average_cost)->toBe(9.0);
});

it('filters movements by direction, product and lot', function (): void {
    $productA = TimberProduct::factory()->create();
    $productB = TimberProduct::factory()->create();
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($productA, 'load', 10.0, 'quintali', lotCode: 'LOT-A');
    $action->execute($productA, 'unload', 4.0, 'quintali', lotCode: 'LOT-A');
    $action->execute($productB, 'load', 7.0, 'quintali', lotCode: 'LOT-B');

    $query = app(GetTimberStockMovementsQuery::class);

    expect($query->execute(directionCode: 'load')->count())->toBe(2)
        ->and($query->execute(productId: $productA->getKey())->count())->toBe(2)
        ->and($query->execute(lotCode: 'LOT-B')->count())->toBe(1);
});

it('links a movement to a customer', function (): void {
    $product = TimberProduct::factory()->create();
    $customer = TimberCustomer::factory()->create();

    $movement = app(RegisterTimberStockMovementAction::class)->execute(
        product: $product,
        directionCode: 'unload',
        quantity: 5.0,
        unitOfMeasure: 'quintali',
        customer: $customer,
    );

    expect($movement->customer_id)->toBe($customer->getKey())
        ->and(TimberStockMovement::query()->where('customer_id', $customer->getKey())->exists())->toBeTrue();
});
