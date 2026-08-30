<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Actions\PriceSignCatalogItemAction;
use Modules\Signage\Datas\SignCatalogItemPrice;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignCatalogItemComponent;
use Modules\Signage\Models\SignPriceList;
use Modules\Signage\Support\SignageCosting;
use Modules\Signage\Tests\TestCase;

function priceCatalogItem(SignCatalogItem $item): SignCatalogItemPrice
{
    return app(PriceSignCatalogItemAction::class)->execute($item);
}

it('takes the quantity of a square-metre row from the piece of material consumed', function (): void {
    $item = SignCatalogItem::factory()->create();

    // The real row from Signage's distinta: 116,450 EUR/mq on 1800 x 200 mm.
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'mq',
        'quantity' => 1,
        'width_mm' => 1800,
        'height_mm' => 200,
        'unit_cost' => 116.4500,
    ]);

    $price = priceCatalogItem($item);

    expect($price->lines[0]->quantity)->toEqualWithDelta(0.36, 0.0001)
        ->and($price->totalCost)->toBe(41.92);
});

it('multiplies the quantity directly on rows that are not square metres', function (): void {
    $item = SignCatalogItem::factory()->create();

    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'labor',
        'unit_code' => 'min',
        'quantity' => 780,
        'width_mm' => null,
        'height_mm' => null,
        'unit_cost' => 0.5000,
    ]);

    expect(priceCatalogItem($item)->totalCost)->toBe(390.0);
});

it('ignores width and height when the unit is not a surface unit', function (): void {
    $item = SignCatalogItem::factory()->create();

    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'milling',
        'unit_code' => 'mt',
        'quantity' => 10,
        'width_mm' => 1800,
        'height_mm' => 200,
        'unit_cost' => 2.1000,
    ]);

    expect(priceCatalogItem($item)->totalCost)->toBe(21.0);
});

it('resolves the markup from the work centre when the item says nothing', function (): void {
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'material', 'default_markup_percent' => 50]);

    $item = SignCatalogItem::factory()->create(['markup_percent' => null]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 100,
    ]);

    $price = priceCatalogItem($item);

    expect($price->lines[0]->markupSourceCode)->toBe('work_center')
        ->and($price->salePrice)->toBe(150.0);
});

it('lets the item override the work centre markup', function (): void {
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'material', 'default_markup_percent' => 50]);

    $item = SignCatalogItem::factory()->create(['markup_percent' => 10]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 100,
    ]);

    $price = priceCatalogItem($item);

    expect($price->lines[0]->markupSourceCode)->toBe('item')
        ->and($price->salePrice)->toBe(110.0);
});

it('falls back to the price list when neither item nor work centre says anything', function (): void {
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'material', 'default_markup_percent' => null]);

    $list = SignPriceList::factory()->create(['default_markup_percent' => 25]);
    $item = SignCatalogItem::factory()->for($list, 'priceList')->create(['markup_percent' => null]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 100,
    ]);

    $price = priceCatalogItem($item);

    expect($price->lines[0]->markupSourceCode)->toBe('price_list')
        ->and($price->salePrice)->toBe(125.0);
});

it('falls back to the configured default when nothing else is set', function (): void {
    config()->set('signage.default_markup_percent', 30);

    $item = SignCatalogItem::factory()->create(['markup_percent' => null]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'unmapped',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 100,
    ]);

    $price = priceCatalogItem($item);

    expect($price->lines[0]->markupSourceCode)->toBe('config')
        ->and($price->salePrice)->toBe(130.0);
});

it('applies a different markup per work centre inside the same distinta', function (): void {
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'material', 'default_markup_percent' => 50]);
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'labor', 'default_markup_percent' => 100]);

    $item = SignCatalogItem::factory()->create(['markup_percent' => null]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'position' => 10, 'work_center_code' => 'material', 'unit_code' => 'nr', 'quantity' => 1, 'unit_cost' => 100,
    ]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'position' => 20, 'work_center_code' => 'labor', 'unit_code' => 'nr', 'quantity' => 1, 'unit_cost' => 100,
    ]);

    $price = priceCatalogItem($item);

    expect($price->totalCost)->toBe(200.0)
        ->and($price->salePrice)->toBe(350.0);
});

it('lets a hand-written price win and derives the percentages from it', function (): void {
    WorkCenter::factory()->create(['context_code' => SignageCosting::CONTEXT, 'code' => 'material', 'default_markup_percent' => 50]);

    $item = SignCatalogItem::factory()->create([
        'markup_percent' => null,
        'manual_sale_price' => 1000,
    ]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 400,
    ]);

    $price = priceCatalogItem($item);

    expect($price->isManualPrice)->toBeTrue()
        ->and($price->salePrice)->toBe(1000.0)
        ->and($price->totalCost)->toBe(400.0)
        ->and($price->margin())->toBe(600.0)
        ->and($price->marginPercent())->toBe(60.0)
        ->and($price->markupOnCostPercent())->toBe(150.0);
});

it('reports the percentages Signage shows next to the price', function (): void {
    // The figures on their screen: price 7.680,00 on a cost of 2.638,47.
    $item = SignCatalogItem::factory()->create(['manual_sale_price' => 7680.00]);
    SignCatalogItemComponent::factory()->for($item, 'catalogItem')->create([
        'work_center_code' => 'material',
        'unit_code' => 'nr',
        'quantity' => 1,
        'unit_cost' => 2638.47,
    ]);

    $price = priceCatalogItem($item);

    expect($price->margin())->toBe(5041.53)
        ->and($price->marginPercent())->toBe(65.64)
        ->and($price->markupOnCostPercent())->toBe(191.08);
});

it('returns no margin percentages when there is nothing to divide by', function (): void {
    $item = SignCatalogItem::factory()->create(['manual_sale_price' => null]);

    $price = priceCatalogItem($item);

    expect($price->lines)->toBe([])
        ->and($price->totalCost)->toBe(0.0)
        ->and($price->salePrice)->toBe(0.0)
        ->and($price->marginPercent())->toBeNull()
        ->and($price->markupOnCostPercent())->toBeNull();
});
