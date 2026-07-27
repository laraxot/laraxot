<?php

declare(strict_types=1);

use Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages\CreateTimberPriceList;
use Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages\ListTimberPriceLists;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberPriceListItem;
use Modules\TimberBilling\Models\TimberPriceListVersion;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    filament()->setCurrentPanel('timber');
    actingAsTimberAdmin();
});

it('lists the seeded price lists', function (): void {
    testTimberLivewire(ListTimberPriceLists::class)
        ->assertOk()
        ->assertSee('Ben B2B');
});

it('creates a price list with product prices and snapshots a version', function (): void {
    $product = TimberProduct::factory()->createOne(['description' => 'Legna abete']);

    testTimberLivewire(CreateTimberPriceList::class)
        ->fillForm([
            'code' => 'estate_2026',
            'name' => 'Estate 2026',
            'items' => [
                ['product_id' => $product->getKey(), 'net_price' => 9.5],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $list = TimberPriceList::query()->where('code', 'estate_2026')->firstOrFail();
    $firstItem = $list->items()->first();
    $firstVersion = $list->versions()->first();
    expect($firstItem)->not->toBeNull();
    expect($firstVersion)->not->toBeNull();
    assert($firstItem instanceof TimberPriceListItem);
    assert($firstVersion instanceof TimberPriceListVersion);

    expect($list->items()->count())->toBe(1)
        ->and((float) $firstItem->net_price)->toBe(9.5)
        ->and($list->versions()->count())->toBe(1)
        ->and($firstVersion->items)->toHaveCount(1);
});
