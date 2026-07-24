<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\PostTimberDocumentStockAction;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;
use Modules\TimberBilling\Queries\GetTimberStockMovementsQuery;
use Modules\TimberBilling\Support\TimberDocumentNumberGenerator;

it('computes line and document totals', function (): void {
    $document = TimberDocument::factory()->create();
    $document->lines()->create([
        'description' => 'Legna abete',
        'quantity' => 10,
        'unit_of_measure' => 'quintali',
        'unit_price' => 9.0,
        'discount_percent' => 10,
        'line_total' => 0,
    ]);
    $document->lines()->create([
        'description' => 'Pellet',
        'quantity' => 5,
        'unit_of_measure' => 'quintali',
        'unit_price' => 4.0,
        'discount_percent' => 0,
        'line_total' => 0,
    ]);

    $document->recalculateTotals();

    // 10 * 9 * 0.9 = 81 ; 5 * 4 = 20 ; total 101
    expect((float) $document->lines()->sum('line_total'))->toBe(101.0)
        ->and((float) $document->fresh()->total_amount)->toBe(101.0);
});

it('generates unload stock movements when a DDT is posted', function (): void {
    $customer = TimberCustomer::factory()->create();
    $product = TimberProduct::factory()->create();

    // Give the product some stock first.
    TimberStockMovement::factory()->create([
        'product_id' => $product->getKey(),
        'direction_code' => 'load',
        'quantity' => 100,
    ]);

    $document = TimberDocument::factory()->create([
        'document_type_code' => 'ddt',
        'customer_id' => $customer->getKey(),
        'number' => 'DDT-2026-0005',
        'status_code' => 'confirmed',
    ]);
    $document->lines()->create([
        'product_id' => $product->getKey(),
        'description' => $product->description,
        'quantity' => 12,
        'unit_of_measure' => 'quintali',
        'unit_price' => 9.0,
        'discount_percent' => 0,
        'line_total' => 108,
    ]);

    $posted = app(PostTimberDocumentStockAction::class)->execute($document);

    expect($posted->status_code)->toBe('delivered')
        ->and($posted->stock_posted_at)->not->toBeNull();

    $movement = TimberStockMovement::query()
        ->where('document_reference', 'DDT-2026-0005')
        ->firstOrFail();

    expect($movement->direction_code)->toBe('unload')
        ->and((float) $movement->quantity)->toBe(12.0)
        ->and($movement->customer_id)->toBe($customer->getKey())
        ->and(app(GetTimberStockMovementsQuery::class)->stockForProduct($product->getKey()))->toBe(88.0);
});

it('generates load stock movements when an inbound goods document is posted', function (): void {
    $product = TimberProduct::factory()->create();

    $document = TimberDocument::factory()->create([
        'document_type_code' => 'arrivo_merce',
        'number' => 'AM-2026-0001',
        'status_code' => 'confirmed',
    ]);
    $document->lines()->create([
        'product_id' => $product->getKey(),
        'description' => $product->description,
        'quantity' => 40,
        'unit_of_measure' => 'quintali',
        'unit_price' => 7.5,
        'discount_percent' => 0,
        'line_total' => 300,
    ]);

    app(PostTimberDocumentStockAction::class)->execute($document);

    expect(app(GetTimberStockMovementsQuery::class)->stockForProduct($product->getKey()))->toBe(40.0)
        ->and((float) $product->refresh()->last_cost)->toBe(7.5);
});

it('does not post an order document to the warehouse', function (): void {
    $document = TimberDocument::factory()->create(['document_type_code' => 'ordine_cliente']);

    app(PostTimberDocumentStockAction::class)->execute($document);
})->throws(RuntimeException::class);

it('refuses to post the same document twice', function (): void {
    $product = TimberProduct::factory()->create();
    $document = TimberDocument::factory()->create([
        'document_type_code' => 'arrivo_merce',
        'status_code' => 'confirmed',
    ]);
    $document->lines()->create([
        'product_id' => $product->getKey(),
        'description' => $product->description,
        'quantity' => 5,
        'unit_of_measure' => 'quintali',
        'unit_price' => 1,
        'discount_percent' => 0,
        'line_total' => 5,
    ]);

    $action = app(PostTimberDocumentStockAction::class);
    $action->execute($document);
    $action->execute($document->refresh());
})->throws(RuntimeException::class);

it('generates sequential document numbers per type and year', function (): void {
    $generator = app(TimberDocumentNumberGenerator::class);

    $first = $generator->next('ddt');
    TimberDocument::factory()->create(['document_type_code' => 'ddt', 'number' => $first]);
    $second = $generator->next('ddt');

    expect($first)->toBe('DDT-'.now()->format('Y').'-0001')
        ->and($second)->toBe('DDT-'.now()->format('Y').'-0002');
});
