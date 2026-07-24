<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use DateTimeImmutable;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\PostTimberDocumentStockAction;
use Modules\TimberBilling\Actions\RegisterTimberStockMovementAction;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Queries\GetTimberProductStockByLotQuery;

it('returns available stock per lot for a product in FIFO order', function (): void {
    $product = TimberProduct::factory()->create();
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($product, 'load', 100.0, 'quintali', lotCode: 'LOT-A', movementDate: new DateTimeImmutable('2026-07-01'));
    $action->execute($product, 'load', 50.0, 'quintali', lotCode: 'LOT-B', movementDate: new DateTimeImmutable('2026-07-02'));
    $action->execute($product, 'unload', 30.0, 'quintali', lotCode: 'LOT-A', movementDate: new DateTimeImmutable('2026-07-03'));

    $rows = app(GetTimberProductStockByLotQuery::class)->execute($product->getKey());

    expect($rows)->toHaveCount(2)
        ->and($rows[0])->toBe(['lot_code' => 'LOT-A', 'available' => 70.0]) // FIFO: LOT-A first
        ->and($rows[1])->toBe(['lot_code' => 'LOT-B', 'available' => 50.0]);
});

it('excludes fully-consumed lots and totals the availability', function (): void {
    $product = TimberProduct::factory()->create();
    $action = app(RegisterTimberStockMovementAction::class);

    $action->execute($product, 'load', 40.0, 'quintali', lotCode: 'LOT-X');
    $action->execute($product, 'unload', 40.0, 'quintali', lotCode: 'LOT-X'); // esaurito
    $action->execute($product, 'load', 25.0, 'quintali', lotCode: 'LOT-Y');

    $query = app(GetTimberProductStockByLotQuery::class);

    expect($query->execute($product->getKey()))->toBe([['lot_code' => 'LOT-Y', 'available' => 25.0]])
        ->and($query->totalAvailable($product->getKey()))->toBe(25.0);
});

it('returns nothing for a product without stock', function (): void {
    $product = TimberProduct::factory()->create();

    expect(app(GetTimberProductStockByLotQuery::class)->execute($product->getKey()))->toBe([])
        ->and(app(GetTimberProductStockByLotQuery::class)->totalAvailable($product->getKey()))->toBe(0.0);
});

it('a DDT unloads from the indicated lot and the availability drops', function (): void {
    $product = TimberProduct::factory()->create();
    app(RegisterTimberStockMovementAction::class)->execute($product, 'load', 100.0, 'quintali', lotCode: 'LOT-A');

    $ddt = TimberDocument::factory()->create([
        'document_type_code' => 'ddt',
        'status_code' => 'confirmed',
        'number' => 'DDT-2026-0900',
    ]);
    $ddt->lines()->create([
        'product_id' => $product->getKey(),
        'description' => $product->description,
        'quantity' => 30,
        'unit_of_measure' => 'quintali',
        'unit_price' => 0,
        'discount_percent' => 0,
        'line_total' => 0,
        'lot_code' => 'LOT-A',
    ]);

    app(PostTimberDocumentStockAction::class)->execute($ddt);

    expect(app(GetTimberProductStockByLotQuery::class)->execute($product->getKey()))
        ->toBe([['lot_code' => 'LOT-A', 'available' => 70.0]]);
});
