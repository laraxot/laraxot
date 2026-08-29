<?php

declare(strict_types=1);

uses(\Modules\Timber\Tests\TestCase::class);
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberCustomerPrice;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberPriceListItem;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Support\TimberPriceResolver;

it('resolves the price from the customer price list item', function (): void {
    $customer = TimberCustomer::factory()->createOne(['price_list_code' => 'ben_b2b']);
    $product = TimberProduct::factory()->createOne(['list_prices' => []]);
    $list = TimberPriceList::query()->where('code', 'ben_b2b')->firstOrFail();
    TimberPriceListItem::query()->create(['price_list_id' => $list->getKey(), 'product_id' => $product->getKey(), 'net_price' => 5.0]);

    expect(app(TimberPriceResolver::class)->priceFor($customer, $product))->toBe(5.0);
});

it('lets a customer-specific price override the list', function (): void {
    $customer = TimberCustomer::factory()->createOne(['price_list_code' => 'ben_b2b']);
    $product = TimberProduct::factory()->createOne();
    $list = TimberPriceList::query()->where('code', 'ben_b2b')->firstOrFail();
    TimberPriceListItem::query()->create(['price_list_id' => $list->getKey(), 'product_id' => $product->getKey(), 'net_price' => 5.0]);
    TimberCustomerPrice::query()->create(['customer_id' => $customer->getKey(), 'product_id' => $product->getKey(), 'net_price' => 3.5]);

    expect(app(TimberPriceResolver::class)->priceFor($customer, $product))->toBe(3.5);
});

it('falls back to the legacy product price for the list code', function (): void {
    $customer = TimberCustomer::factory()->createOne(['price_list_code' => 'ben_b2c']);
    $product = TimberProduct::factory()->createOne([
        'list_prices' => [['price_list_code' => 'ben_b2c', 'net_price' => 2.35]],
    ]);

    expect(app(TimberPriceResolver::class)->priceFor($customer, $product))->toBe(2.35);
});

it('returns null when no price is defined anywhere', function (): void {
    $customer = TimberCustomer::factory()->createOne(['price_list_code' => 'ben_b2b']);
    $product = TimberProduct::factory()->createOne(['list_prices' => []]);

    expect(app(TimberPriceResolver::class)->priceFor($customer, $product))->toBeNull();
});
